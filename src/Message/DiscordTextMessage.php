<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle\Message;

class DiscordTextMessage implements DiscordMessageInterface
{
    private string $content = '';

    public function getContent(): string
    {
        return $this->content;
    }

    public function addHeadline(MdHeadline $headline, string $content): self
    {
        $this->addNewline();

        $this->content .= sprintf('%s%s', $headline->value, $content);

        return $this;
    }

    public function addText(string $content): self
    {
        $this->addNewline();

        $this->content .= $content;

        return $this;
    }

    public function addMultilineCodeBlock(string $content, string $language = ''): self
    {
        $this->addNewline();
        $this->addWhitespace();

        $this->content .= sprintf('```%s', $language);
        $this->addNewline();
        $this->content .= $content;
        $this->addNewline();
        $this->content .= '```';

        return $this;
    }

    private function addNewline(): void
    {
        if (empty($this->content)) {
            return;
        }

        $this->content .= '\n';
    }

    private function addWhitespace(): void
    {
        if (mb_substr($this->content, -1) === ' ') {
            return;
        }

        $this->content .= ' ';
    }
}
