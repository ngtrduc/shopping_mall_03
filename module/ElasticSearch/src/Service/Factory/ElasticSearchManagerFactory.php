<?php

namespace Elasticsearch\Service\Factory;

use ElasticSearch\Service\ElasticSearchManager;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Elasticsearch;

define('PATH_APPLICATION', realpath(dirname(_DIR_)));
define('PATH_CONFIG', PATH_APPLICATION . '/config');

class ElasticSearchManagerFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = new \Zend\Config\Config(include PATH_CONFIG.'/autoload/local.php');
        $hosts = [
            'localhost', // Domain
        ];

        $clientBuilder = Elasticsearch\ClientBuilder::create();

        return new ElasticSearchManager($clientBuilder, $hosts);
    }
}
