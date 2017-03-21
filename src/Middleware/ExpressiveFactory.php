<?php

namespace ErrorHeroModule\Middleware;

use Doctrine\ORM\EntityManager;
use ErrorHeroModule\Handler\Logging;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExpressiveFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface
     *
     * @return Expressive
     */
    public function __invoke($container)
    {
        $config = $container->get('config');

        if ($container->has(EntityManager::class) && ! isset($configuration['db'])) {
            $entityManager          = $container->get(EntityManager::class);
            $doctrineDBALConnection = $entityManager->getConnection();

            $params        = $doctrineDBALConnection->getParams();
            $driverOptions = (isset($params['driverOptions'])) ? $params['driverOptions'] : [];

            $dbConfiguration = [
                'username'       => $doctrineDBALConnection->getUsername(),
                'password'       => $doctrineDBALConnection->getPassword(),
                'driver'         => $doctrineDBALConnection->getDriver()->getName(),
                'database'       => $doctrineDBALConnection->getDatabase(),
                'host'           => $doctrineDBALConnection->getHost(),
                'port'           => $doctrineDBALConnection->getPort(),
                'driver_options' => $driverOptions,
            ];

            $allowOverride = $container->getAllowOverride();
            $container->setAllowOverride(true);
            $container->setService('Zend\Db\Adapter\Adapter', new Adapter($dbConfiguration));
            $container->setAllowOverride($allowOverride);
        }

        return new Expressive(
            $config['error-hero-module'],
            $container->get(Logging::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}