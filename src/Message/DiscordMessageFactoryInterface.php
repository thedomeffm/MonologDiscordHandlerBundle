<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle\Message;

use Monolog\LogRecord;

interface DiscordMessageFactoryInterface
{
    public function createFromLogRecord(LogRecord $record): DiscordMessage;
}
