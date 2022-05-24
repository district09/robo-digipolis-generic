<?php

namespace DigipolisGent\Robo\Generic\Robo\Plugin\Commands;

use DigipolisGent\Robo\Generic\Util\TaskFactory\Generic;
use DigipolisGent\Robo\Helpers\DependencyInjection\DeployTaskFactoryAwareInterface;
use DigipolisGent\Robo\Helpers\DependencyInjection\SyncTaskFactoryAwareInterface;
use DigipolisGent\Robo\Helpers\DependencyInjection\Traits\DeployTaskFactoryAware;
use DigipolisGent\Robo\Helpers\DependencyInjection\Traits\SyncTaskFactoryAware;
use DigipolisGent\Robo\Helpers\Robo\Plugin\Commands\DigipolisHelpersCommands;
use DigipolisGent\Robo\Helpers\Util\TaskFactory\Deploy;
use DigipolisGent\Robo\Helpers\Util\TaskFactory\Sync;
use League\Container\ContainerAwareInterface;
use League\Container\DefinitionContainerInterface;

class DigipolisGenericCommands extends DigipolisHelpersCommands implements
    DeployTaskFactoryAwareInterface,
    SyncTaskFactoryAwareInterface
{
    use DeployTaskFactoryAware;
    use SyncTaskFactoryAware;

    public function setContainer(DefinitionContainerInterface $container): ContainerAwareInterface {
        parent::setContainer($container);

        $this->setDeployTaskFactory($container->get(Deploy::class));
        $this->setSyncTaskFactory($container->get(Sync::class));

        return $this;
    }

    public function getAppTaskFactoryClass() {
        return Generic::class;
    }

    /**
     * Build a  site and push it to the servers.
     *
     * @param array $arguments
     *   Variable amount of arguments. The last argument is the path to the
     *   the private key file (ssh), the penultimate is the ssh user. All
     *   arguments before that are server IP's to deploy to.
     * @param array $opts
     *   The options for this command.
     *
     * @option app The name of the app we're deploying. Used to determine the
     *   directory to deploy to.
     * @option worker The IP of the worker server. Defaults to the first server
     *   given in the arguments.
     *
     * @usage --app=myapp 10.25.2.178 sshuser /home/myuser/.ssh/id_rsa
     */
    public function digipolisDeployGeneric(
        array $arguments,
        $opts = [
            'app' => 'default',
            'worker' => null,
        ]
    ) {
        return $this->deployTaskFactory->deployTask($arguments, $opts);
    }

    /**
     * Sync the database and files between two sites.
     *
     * @param string $sourceUser
     *   SSH user to connect to the source server.
     * @param string $sourceHost
     *   IP address of the source server.
     * @param string $sourceKeyFile
     *   Private key file to use to connect to the source server.
     * @param string $destinationUser
     *   SSH user to connect to the destination server.
     * @param string $destinationHost
     *   IP address of the destination server.
     * @param string $destinationKeyFile
     *   Private key file to use to connect to the destination server.
     * @param string $sourceApp
     *   The name of the source app we're syncing. Used to determine the
     *   directory to sync.
     * @param string $destinationApp
     *   The name of the destination app we're syncing. Used to determine the
     *   directory to sync to.
     */
    public function digipolisSyncGeneric(
        $sourceUser,
        $sourceHost,
        $sourceKeyFile,
        $destinationUser,
        $destinationHost,
        $destinationKeyFile,
        $sourceApp = 'default',
        $destinationApp = 'default',
        $opts = ['files' => false, 'data' => false]
    ) {
        if (!$opts['files'] && !$opts['data']) {
            $opts['files'] = true;
            $opts['data'] = true;
        }
        return $this->syncTaskFactory->syncTask(
            $sourceUser,
            $sourceHost,
            $sourceKeyFile,
            $destinationUser,
            $destinationHost,
            $destinationKeyFile,
            $sourceApp,
            $destinationApp,
            $opts
        );
    }

}
