<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle\Message;

enum Syntax: string
{
    case H1 = '# %s';
    case H2 = '## %s';
    case Subtext = '-# %s';
    case Code = '`%s`';
    case JsonCodeBlock = <<<MARKDOWN
    ```json
    %s
    ```
    MARKDOWN;
}