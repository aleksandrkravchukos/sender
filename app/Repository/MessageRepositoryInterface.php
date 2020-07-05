<?php declare(strict_types=1);


namespace App\Repository;


interface MessageRepositoryInterface
{
    const BASE_TIME_ZONE = 2;

    public function getMessagesForSendByTime(string $time): array;
}
