<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle\Message;

class Text
{
    private const NEW_LINE = '\n';

    private function __construct(
        private readonly Syntax $type,
        private readonly string $content,
    ) {}

    public static function create(Syntax $type, string $content): self
    {
        return new self($type, $content);
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function render(): string
    {
        return sprintf($this->type->value, $this->content) . self::NEW_LINE;
    }

    public function getLength(): int
    {
        return strlen($this->render());
    }
}