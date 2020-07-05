<?php


namespace App\Services;


interface SenderInterface
{
    const SAMPLE_SEND_TO_EMAIL = 'aleksandr.kravchuk@ukr.net';
    const SAMPLE_SEND_TO_NAME = 'Aleksandr';

    public function setResponse($response);

    public function addMinuteEmailsToQueue(string $time);
}