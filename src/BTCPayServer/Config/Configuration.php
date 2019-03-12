<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This class contains all the valid configuration settings that can be used.
 * If you update this file to add new settings, please make sure you update the
 * documentation as well.
 *
 * @see http://symfony.com/doc/current/components/config/definition.html
 *
 * @package BTCPayServer
 */
class Configuration implements ConfigurationInterface
{
    /**
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('btcpay');
        $rootNode
            ->children()
                ->scalarNode('public_key')
                    ->info('Public Key Filename')
                    ->defaultValue(getenv('HOME').'/.btcpayserver/api.pub')
                ->end()
                ->scalarNode('private_key')
                    ->info('Private Key Filename')
                    ->defaultValue(getenv('HOME').'/.btcpayserver/api.key')
                ->end()
                ->scalarNode('sin_key')
                    ->info('Private Key Filename')
                    ->defaultValue(getenv('HOME').'/.btcpayserver/api.sin')
                ->end()
                ->enumNode('adapter')
                    ->values(array('curl', 'mock'))
                    ->info('Client Adapter')
                    ->defaultValue('curl')
                ->end()
                ->append($this->addKeyStorageNode())
                ->scalarNode('key_storage_password')
                    ->info('Used to encrypt and decrypt keys when saving to filesystem')
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * Adds the key_storage node with validation rules
     *
     * key_storage MUST:
     *     * implement BTCPayServer\Storage\StorageInterface
     *     * be a class that can be loaded
     */
    protected function addKeyStorageNode()
    {
        $builder = new TreeBuilder();
        $node    = $builder->root('key_storage', 'scalar');

        $node
            ->info('Class that is used to store your keys')
            ->defaultValue('BTCPayServer\Storage\EncryptedFilesystemStorage')
            ->validate()
                ->always()
                ->then(function ($value) {
                    if (!class_exists($value)) {
                        throw new \Exception(
                            sprintf(
                                'Could not find class "%s".',
                                $value
                            )
                        );
                    }

                    // requires PHP >= 5.3.7
                    if (!is_subclass_of($value, 'BTCPayServer\Storage\StorageInterface')) {
                        throw new \Exception(
                            sprintf(
                                '"%s" does not implement "BTCPayServer\Storage\StorageInterface"',
                                $value
                            )
                        );
                    }

                    return $value;
                })
            ->end();

        return $node;
    }
}
