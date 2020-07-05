<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class MessageTimeInTimeZone extends Model
{

    protected $connection = 'mysql';

    protected $table = 'message_time_in_time_zone';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message_time_id',
        'timezone_shift',
        'time_in_timezome',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    public function messageTime()
    {
        return $this->belongsTo('App\MessageTime');
    }
}
