<?php

namespace TheDomeFfm\MonologDiscordHandlerBundle;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;

class DiscordFormatter extends JsonFormatter implements FormatterInterface
{
}
