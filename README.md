# MonologDiscordHandlerBundle

Adds a handler to monolog which can post your logs directly via discord webhook to your discord server channel.

## Installation

_not published yet_

## Configuration

I've not added a recipe (or what ever I need to create :shrug:), so you need to create the config by yourself.

```yaml
# thedomeffm_monolog_discord_handler.yaml

thedomeffm_monolog_discord_handler:
    discord:
        webhook_url: "%env(DISCORD_WEBHOOK_URL)%"
```

Add the env variable
```.dotenv
DISCORD_WEBHOOK_URL="<your webhook url>"
```

Edit your monolog configuration
```yaml
# monolog.yaml

monolog:
    handlers:
        # your other handler...
        discord:
            type: service
            id: thedomeffm_discord_monolog_handler
```

Here is an example how a production config could look like:

```yaml
# monolog.yaml

when@prod:
    monolog:
        handlers:
            main:
                type: fingers_crossed
                action_level: error
                handler: main_group
                excluded_http_codes: [404, 405]
                buffer_size: 50

            main_group:
                type: group
                members: ['error_stream', 'discord']

            error_stream:
                type: stream
                path: php://stderr
                level: debug
                formatter: monolog.formatter.json

            discord:
                type: service
                id: thedomeffm_discord_monolog_handler

            # your other handler...
```
