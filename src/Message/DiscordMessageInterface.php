<?php

namespace TheDomeFfm\MonologDiscordHandlerBundle\Message;

interface DiscordMessageInterface
{
    public function getContent(): string;
}
