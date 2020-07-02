<?php

namespace App\Services;

use App\Helpers\NodeParser;
use App\Models\LocalRegion;
use App\Models\Social\SocialPost;
use App\Models\Social\SocialStories;
use App\Models\Social\SocialUser;
use App\Models\Social\SocialUserRegion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class Sender
{
    protected $response;
    protected $debug = false;
    private $username = 'aleksandr.kravchuk@ukr.net';
    private $password = 'testpassword1';
    protected $enableQueryRotate = true;
    protected $proxyList = [];
    private $url;

    public function __construct()
    {
        $this->url = 'https://hypeauditor.com/login/';
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    protected function log($message)
    {
        if ($this->debug) {
            echo $message . "\n";
        }
    }

    public function setProxyList($list)
    {
        $this->proxyList = $list;
        $cache = Cache::store('redis');
        $key = 'proxylisttop';
        $cache->getRedis()->command('DEL', [$key]);
        foreach ($list AS $item) {
            $cache->getRedis()->command('rPush', [$key, json_encode($item)]);
        }
        $this->log('Added: ' . count($list));
        return $this;
    }

    public function getProxy()
    {
        if ($this->enableQueryRotate) {
            $cache = Cache::store('redis');
            $key = 'proxylisttop';
            $data = $cache->getRedis()->command('lPop', [$key]);
            $cache->getRedis()->command('rPush', [$key, $data]);
            $proxyItem = json_decode($data, true);
            return $proxyItem;
        } else {
            if (is_array($this->proxyList) && count($this->proxyList)) {
                return $this->proxyList[array_rand($this->proxyList, 1)];
            }
        }
    }

    public function fetch($url, $page = 0)
    {
        $ch = curl_init();

        if ($page == 0) {
            $headers = [
                'Accept: application/json',
                'Content-Type: application/json',
            ];
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
            $proxy = $this->getProxy();
            if ($proxy) {
                $this->log('Proxy: ' . print_r($proxy, true));
                curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy['type']);
                curl_setopt($ch, CURLOPT_PROXY, $proxy['host'] . ':' . $proxy['port']);
                if (isset($proxy['username']) && isset($proxy['password']))
                    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ':' . $proxy['password']);
            }
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status != 200) {
                $response = false;
            }
            $this->log('status: ' . $status);
            curl_close($ch);

        } else {
            $loginUrl = $this->url;
            $postInfo = "email=" . $this->username . "&password=" . $this->password;
            $cookie_file_path = "/tmp/cookie.txt";
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_NOBODY, false);

            $proxy = $this->getProxy();
            if ($proxy) {
                $this->log('Proxy: ' . print_r($proxy, true));
                curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy['type']);
                curl_setopt($ch, CURLOPT_PROXY, $proxy['host'] . ':' . $proxy['port']);
                if (isset($proxy['username']) && isset($proxy['password']))
                    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'] . ':' . $proxy['password']);
            }

            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_URL, $loginUrl);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
            curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postInfo);
            curl_exec($ch);

            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status != 200) {
                curl_close($ch);
                return false;
            }
            $this->log('Login status: ' . $status);

            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);

            curl_close($ch);
        }

        return $response;
    }

    /**
     * Parse 1000 SocialAccounts per every regions from hypeauditor.com
     */
    public function request()
    {
//        $deleted_count = SocialUserRegion::where('id', '>', 0)->delete();
//        $this->log('Deleted: ' . $deleted_count);
        $popularUserCount = [];

        $regions = LocalRegion::all();
        foreach ($regions as $reg) {
            $popularUserCount[$reg->id] = 0;
        }

        for ($page = 1; $page <= 20; $page++) {
            $linkTop = [
                ['region_id' => LocalRegion::where('prefix', 'us')->first()->id,
                    'url' => 'https://hypeauditor.com/top-instagram-all-united-states/' . '?p=' . $page],
                ['region_id' => LocalRegion::whereNull('prefix')->first()->id,
                    'url' => 'https://hypeauditor.com/top-instagram-all/' . '?p=' . $page],
                ['region_id' => LocalRegion::where('prefix', 'germany')->first()->id,
                    'url' => 'https://hypeauditor.com/top-instagram-all-germany/' . '?p=' . $page],
                ['region_id' => LocalRegion::where('prefix', 'russia')->first()->id,
                    'url' => 'https://hypeauditor.com/top-instagram-all-russia/' . '?p=' . $page],
                ['region_id' => LocalRegion::where('prefix', 'ukraine')->first()->id,
                    'url' => 'https://hypeauditor.com/top-instagram-all-ukraine/' . '?p=' . $page],
                ['region_id' => LocalRegion::where('prefix', 'uk')->first()->id,
                    'url' => 'https://hypeauditor.com/top-instagram-all-united-kingdom/' . '?p=' . $page],
            ];

            foreach ($linkTop as $item) {

                $url = $item['url'];
                $this->log('Request: ' . $url);
                $response = $this->fetch($url, $page);
                //$popularUserCount[$item['region_id']] = 0;
                $region_id = $item['region_id'];
                if (preg_match_all('~kyb-ellipsis.*\s*\S*?>(.*?)</a~', $response, $match) && $response != '') {
                    foreach ($match[1] as $items) {
                        $path = ltrim($items, '@');
                        $link = 'https://www.instagram.com/' . $path;
                        $socialAccount = SocialUser::where('originId', $path)
                            ->where('socialMediaId', 3)
                            ->first();
                        $this->log('account: ' . $items);
                        if (!$socialAccount) {
                            $response = NodeParser::getInstagramAccount($link);
                            if (isset($response['data'])) {
                                $data = $response['data'];
                                $addInstaUser = new SocialUser();
                                $addInstaUser->originId = $data['username'];
                                $addInstaUser->name = $data['fullName'];
                                $addInstaUser->local_region_id = $region_id;
                                $addInstaUser->followers = $data['followersCount'];
                                $addInstaUser->isVerified = $data['isVerified'] ? 1 : 0;
                                $addInstaUser->avatar = $data['profilePic'];
                                $addInstaUser->socialMediaId = 3; // Instagram
                                $addInstaUser->imageServer = '//gate.undelete.news'; // Instagram Image Server
                                $addInstaUser->save();
                            } else {
                                print_r($response);
                            }
                        } else {

                            $lastDeletedSocialPosts = SocialPost::where('userId', $socialAccount->id)->whereDate('delete_date', '>', Carbon::today()->subDays(7))->first();
                            $userStories = SocialStories::where('social_user_id', $socialAccount->id)->whereDate('takenAt', '>', Carbon::today()->subDays(7))->first();
                            if ($lastDeletedSocialPosts || $userStories) {
                                $popularUserCount[$item['region_id']]++;
                                SocialUserRegion::create([
                                    'local_region_id' => $region_id,
                                    'user_id' => $socialAccount->id,
                                    'rank' => $popularUserCount[$item['region_id']]
                                ]);

                                if (isset($response['data'])) {
                                    $data = $response['data'];
                                    $socialAccount->followers = $data['followersCount'];
                                    $socialAccount->isVerified = $data['isVerified'] ? 1 : 0;
                                    //$socialAccount->avatar = $data['profilePic'];
                                    $socialAccount->socialMediaId = 3; // Instagram
                                    $socialAccount->save();
                                }
                            }
                        }
                    }
//                    if ($popularUserCount[$item['region_id']] < 50) {
//                        $socialAccounts = SocialUser::where('local_region_id', $region_id)->doesntHave('topregion')
//                            ->orderBy('popularity', 'DESC')
//                            ->limit(1000)
//                            ->get();
//                        foreach ($socialAccounts as $itemIs) {
//                            $lastDeletedSocialPosts = SocialPost::where('userId', $itemIs->id)->whereDate('delete_date', '>', Carbon::today()->subDays(7))->first();
//                            $userStories = SocialStories::where('social_user_id', $itemIs->id)->whereDate('takenAt', '>', Carbon::today()->subDays(7))->first();
//                            if ($lastDeletedSocialPosts || $userStories) {
//                                $popularUserCount++;
//                                SocialUserRegion::create([
//                                    'local_region_id' => $region_id,
//                                    'user_id' => $itemIs->id,
//                                    'rank' => $popularUserCount[$item['region_id']]
//                                ]);
//                            }
////                            if ($popularUserCount == 200)
////                                break;
//                        }
//                    }
                }
            }
        }
    }
}
