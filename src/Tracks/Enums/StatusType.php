<?php namespace Maqe\Qwatcher\Tracks\Enums;

class StatusType
{
    const QUEUE = 'queue';
    const PROCESS = 'process';
    const SUCCEED = 'succeed';
    const FAILED = 'failed';

    const QUEUE_MESSAGE = 'queued';
    const PROCESS_MESSAGE = 'processing';
    const SUCCEED_MESSAGE = 'completed';
    const FAILED_MESSAGE = 'failed';

    public static function statsTypes()
    {
        return [self::QUEUE, self::PROCESS, self::SUCCEED, self::FAILED];
    }

    public static function statusTypeMessages()
    {
        return [
            self::QUEUE => self::QUEUE_MESSAGE,
            self::PROCESS => self::PROCESS_MESSAGE,
            self::SUCCEED => self::SUCCEED_MESSAGE,
            self::FAILED => self::FAILED_MESSAGE
        ];
    }

    public static function getMessageByStatus($status)
    {
        $status = strtolower($status);

        return self::statusTypeMessages()[$status];
    }
}
