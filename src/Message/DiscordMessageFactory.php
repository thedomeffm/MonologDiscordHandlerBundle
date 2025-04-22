<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle\Message;

use Monolog\LogRecord;

class DiscordMessageFactory implements DiscordMessageFactoryInterface
{
    public function __construct(
        protected readonly int $charactersLimit,
        protected readonly string $dateFormat,
        protected readonly bool $jsonPrettyPrint,
    ) {}

    public function createFromLogRecord(LogRecord $record): DiscordMessage
    {
        if ($this->charactersLimit <= 0) {
            throw new \InvalidArgumentException('The characters limit must be greater than 0.');
        }

        // Example: [ERROR] The error message
        $title = Text::create(Syntax::H1, sprintf('[%s] %s', $record->level->name, $record->message));
        // Example: Server DateTime: 2022-01-01 12:00:00
        $timestamp = Text::create(Syntax::Code, sprintf('Server DateTime: %s', $record->datetime->format($this->dateFormat)));
        // Example: {"message": "The error message", ...}
        $formatted = Text::create(Syntax::JsonCodeBlock, $this->formatJson($record->formatted));

        $formattedTooLong = Text::create(Syntax::JsonCodeBlock, json_encode(['Warning' => 'The error log is too long to be displayed in Discord.'], JSON_THROW_ON_ERROR));

        $discordMessage = new DiscordMessage();
        if ($title->getLength() + $discordMessage->getLength() <= $this->charactersLimit) {
            $discordMessage->append($title->render());
        }

        if ($timestamp->getLength() + $discordMessage->getLength() <= $this->charactersLimit) {
            $discordMessage->append($timestamp->render());
        }

        if ($formatted->getLength() + $discordMessage->getLength() <= $this->charactersLimit) {
            $discordMessage->append($formatted->render());
        } else if ($formattedTooLong->getLength() + $discordMessage->getLength() <= $this->charactersLimit) {
            $discordMessage->append($formattedTooLong->render());
        }

        return $discordMessage;
    }

    private function formatJson(mixed $formatted): string
    {
        if (!is_string($formatted)) {
            return json_encode(['Warning' => 'The error log is not a valid json.'], JSON_THROW_ON_ERROR);
        }

        if ($this->jsonPrettyPrint) {
            return json_encode(json_decode($formatted, false, 512, JSON_THROW_ON_ERROR), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        }

        return $formatted;
    }
}
