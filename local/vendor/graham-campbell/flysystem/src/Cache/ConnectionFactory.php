<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem\Cache;

use GrahamCampbell\Flysystem\FlysystemManager;
use Illuminate\Cache\CacheManager;
use InvalidArgumentException;

/**
 * This is the cache connection factory class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ConnectionFactory
{
    /**
     * The cache manager instance.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Create a new connection factory instance.
     *
     * @param \Illuminate\Cache\CacheManager $cache
     *
     * @return void
     */
    public function __construct(CacheManager $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Establish a cache connection.
     *
     * @param array                                      $config
     * @param \GrahamCampbell\Flysystem\FlysystemManager $manager
     *
     * @return \League\Flysystem\Cached\CacheInterface
     */
    public function make(array $config, FlysystemManager $manager)
    {
        return $this->createConnector($config, $manager)->connect($config);
    }

    /**
     * Create a connector instance based on the configuration.
     *
     * @param array                                      $config
     * @param \GrahamCampbell\Flysystem\FlysystemManager $manager
     *
     * @throws \InvalidArgumentException
     *
     * @return \GrahamCampbell\Manager\ConnectorInterface
     */
    public function createConnector(array $config, FlysystemManager $manager)
    {
        if (!isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        switch ($config['driver']) {
            case 'illuminate':
                return new IlluminateConnector($this->cache);
            case 'adapter':
                return new AdapterConnector($manager);
        }

        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}]");
    }

    /**
     * Get the cache manager instance.
     *
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Cache\CacheManager
     */
    public function getCache()
    {
        return $this->cache;
    }
}
