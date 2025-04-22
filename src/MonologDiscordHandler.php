<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use TheDomeFfm\MonologDiscordHandlerBundle\Message\DiscordMessageFactoryInterface;

class MonologDiscordHandler extends AbstractProcessingHandler
{
    public function __construct(
        #[\SensitiveParameter] private readonly string $webhookUrl,
        private readonly DiscordMessageFactoryInterface $discordMessageFactory,
        protected Level $level = Level::Error,
        protected bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        if (!str_starts_with($this->webhookUrl, 'https://')) {
            return;
        }

        $discordMessage = $this->discordMessageFactory->createFromLogRecord($record);

        $body = json_encode(['content' => stripcslashes($discordMessage->getContent())], JSON_THROW_ON_ERROR, JSON_UNESCAPED_SLASHES);

        $ch = curl_init();
        $options = [
            CURLOPT_URL => $this->webhookUrl,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-type: application/json'],
            CURLOPT_POSTFIELDS => $body,
        ];
        if (defined('CURLOPT_SAFE_UPLOAD')) {
            $options[CURLOPT_SAFE_UPLOAD] = true;
        }

        curl_setopt_array($ch, $options);

        curl_exec($ch);

        curl_close($ch);
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new DiscordFormatter();
    }
}
