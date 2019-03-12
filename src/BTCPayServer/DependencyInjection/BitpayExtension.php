<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\DependencyInjection;

use BTCPayServer\Config\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * @package BTCPayServer
 */
class BTCPayServerExtension implements ExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config    = $processor->processConfiguration(new Configuration(), $configs);

        foreach (array_keys($config) as $key) {
            $container->setParameter('btcpayserver.'.$key, $config[$key]);
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('services.xml');

        $container->setParameter(
            'adapter.class',
            'BTCPayServer\Client\Adapter\\'.ContainerBuilder::camelize($config['adapter']).'Adapter'
        );
        $container->setParameter('key_storage.class', $config['key_storage']);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getAlias()
    {
        return 'btcpay';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getNamespace()
    {
        return 'http://example.org/schema/dic/btcpayserver';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }
}
