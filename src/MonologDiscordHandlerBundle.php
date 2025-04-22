<?php

declare(strict_types=1);

namespace TheDomeFfm\MonologDiscordHandlerBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

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
                ->arrayNode('formatting')
                    ->children()
                        ->scalarNode('characters_limit')->defaultValue(2000)->end()
                        ->scalarNode('date_format')->defaultValue('Y-m-d H:i:s')->end()
                        ->scalarNode('json_pretty_print')->defaultValue(false)->end()
                    ->end()->addDefaultsIfNotSet()
                ->end() // formatting
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
            ->get('thedomeffm_monolog_discord_message_factory')
            ->arg(0, $config['formatting']['characters_limit'])
            ->arg(1, $config['formatting']['date_format'])
            ->arg(2, $config['formatting']['json_pretty_print'])
        ;

        $container->services()
            ->get('thedomeffm_monolog_discord_handler')
            ->arg(0, $config['discord']['webhook_url'])
            ->arg(1, service('thedomeffm_monolog_discord_message_factory'))
        ;
    }
}
