<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle\Message;

class DiscordMessage
{
    private string $content = '';

    public function clear(): void
    {
        $this->content = '';
    }

    public function append(string $text): self
    {
        $this->content .= $text;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getLength(): int
    {
        return strlen($this->content);
    }
}