<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle\Tests;

use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use TheDomeFfm\MonologDiscordHandlerBundle\Message\DiscordMessage;
use TheDomeFfm\MonologDiscordHandlerBundle\Message\DiscordMessageFactory;

class DiscordMessageFactoryTest extends TestCase
{
    public function testRenderDiscordMessageFromLog(): void
    {
        $dateTime = new DateTimeImmutable('2022-01-01 12:00:00');
        $factory = new DiscordMessageFactory(
            200,
            'Y-m-d',
            false,
        );

        $message = $factory->createFromLogRecord(new LogRecord(
            $dateTime,
            'NOT USED',
            Level::Emergency,
            'Hello World',
            [/* not used */],
            [/* not used */],
            json_encode(['message' => 'Hello World'], JSON_THROW_ON_ERROR),
        ));

        $expected = new DiscordMessage();
        $expected
            ->append('# [Emergency] Hello World\n')
            ->append('`Server DateTime: 2022-01-01`\n')
            ->append(<<<MARKDOWN
            ```json
            {"message":"Hello World"}
            ```
            MARKDOWN. '\n',
            );
        $this->assertEquals($expected, $message);
    }

    public function testTruncatesTheMessageWhenToLong(): void
    {
        $dateTime = new DateTimeImmutable('2022-01-01 12:00:00');
        $factory = new DiscordMessageFactory(
            200,
            'Y-m-d',
            false,
        );

        $message = $factory->createFromLogRecord(new LogRecord(
            $dateTime,
            'NOT USED',
            Level::Emergency,
            'Hello World',
            [/* not used */],
            [/* not used */],
            json_encode([
                'message' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor '
                    . 'invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et '
                    . 'justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem '
                    . 'ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy '
                    . 'eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.',
                'context' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor '
                    . 'invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et '
                    . 'justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem '
                    . 'ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy '
                    . 'eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.',
            ], JSON_THROW_ON_ERROR),
        ));

        $expected = new DiscordMessage();
        $expected
            ->append('# [Emergency] Hello World\n')
            ->append('`Server DateTime: 2022-01-01`\n')
            ->append(<<<MARKDOWN
            ```json
            {"Warning":"The error log is too long to be displayed in Discord."}
            ```
            MARKDOWN. '\n',
            );
        $this->assertEquals($expected, $message);
    }
}