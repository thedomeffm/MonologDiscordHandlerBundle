<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class MonologDiscordHandlerBundle extends AbstractBundle
{
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
            throw new \RuntimeException('Please add a configuration for \'monolog_discord_handler\'.');
        }

        $container->services()
            ->get('thedomeffm_discord_monolog_handler')
            ->arg(0, $config['discord']['webhook_url'])
        ;
    }
}
