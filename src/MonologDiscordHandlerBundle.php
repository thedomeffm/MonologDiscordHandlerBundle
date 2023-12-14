<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class MonologDiscordHandlerBundle extends AbstractBundle
{
    protected string $extensionAlias = 'thedomeffm_monolog_discord_handler';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('discord')
                    ->children()
                        ->scalarNode('webhook_url')->end()
                    ->end()
                ->end() // discord
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('Resources/config/services.xml');

        if (empty($config)) {
            return;
        }

        $container->services()
            ->get('thedomeffm_monolog_discord_handler')
            ->arg(0, $config['discord']['webhook_url'])
        ;
    }
}
