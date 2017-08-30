<?php

namespace Vangrg\ProfanityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class VangrgProfanityExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setAlias('vangrg_profanity.storage', $config['storage']);

        $container->setParameter('vangrg_profanity.allow_bound_by_words', $config['allow_bound_by_words']);
        $container->setParameter('vangrg_profanity.source.file_name', $config['profanities_source']['file_name']);
        $container->setParameter('vangrg_profanity.source.format', $config['profanities_source']['format']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
