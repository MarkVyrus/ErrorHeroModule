<?php

declare(strict_types=1);

namespace ErrorHeroModule\Handler;

use Psr\Container\ContainerInterface;
use RuntimeException;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

class LoggingFactory
{
    /**
     * @throws RuntimeException when mail config is enabled
     * but mail-message and/or mail-transport config is not a service instance of Message
     */
    public function __invoke(ContainerInterface $container) : Logging
    {
        $config                = $container->get('config');
        $errorHeroModuleLogger = $container->get('ErrorHeroModuleLogger');

        $errorHeroModuleLocalConfig = $config['error-hero-module'];
        $logWritersConfig           = $config['log']['ErrorHeroModuleLogger']['writers'];

        $mailConfig           = $errorHeroModuleLocalConfig['email-notification-settings'];
        $mailMessageService   = null;
        $mailMessageTransport = null;

        if ($mailConfig['enable'] === true) {
            $mailMessageService   = $container->get($mailConfig['mail-message']);
            if (! $mailMessageService instanceof Message) {
                throw new RuntimeException(sprintf(
                    'You are enabling email log writer, your "mail-message" config must be instanceof %s',
                    Message::class
                ));
            }

            $mailMessageTransport = $container->get($mailConfig['mail-transport']);
            if (! $mailMessageTransport instanceof TransportInterface) {
                throw new RuntimeException(sprintf(
                    'You are enabling email log writer, your "mail-transport" config must implements %s',
                    TransportInterface::class
                ));
            }
        }

        return new Logging(
            $errorHeroModuleLogger,
            $errorHeroModuleLocalConfig,
            $logWritersConfig,
            $mailMessageService,
            $mailMessageTransport
        );
    }
}
