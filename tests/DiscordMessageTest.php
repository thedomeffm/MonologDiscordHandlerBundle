<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle\Tests;

use PHPUnit\Framework\TestCase;
use TheDomeFfm\MonologDiscordHandlerBundle\Message\DiscordMessage;
use TheDomeFfm\MonologDiscordHandlerBundle\Message\Syntax;
use TheDomeFfm\MonologDiscordHandlerBundle\Message\Text;

final class DiscordMessageTest extends TestCase
{
    public function testRenderSimpleMessage(): void
    {
        $message = new DiscordMessage();

        $message->append(Text::create(Syntax::H1, 'Hello World')->render());
        $message->append('## Direct passing strings\n');

        $content = $message->getContent();

        $this->assertSame('# Hello World\n## Direct passing strings\n', $content);
    }

    public function testCanRenderEmpty(): void
    {
        $message = new DiscordMessage();

        $content = $message->getContent();

        $this->assertSame('', $content);
    }
}
