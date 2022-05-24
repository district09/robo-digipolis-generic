<?php

namespace DigipolisGent\Robo\Generic\Util\TaskFactory;

use Consolidation\AnnotatedCommand\Output\OutputAwareInterface;
use Consolidation\Config\ConfigInterface;
use DigipolisGent\CommandBuilder\CommandBuilder;
use DigipolisGent\Robo\Drupal8\Traits\Drupal8UtilsTrait;
use DigipolisGent\Robo\Helpers\DependencyInjection\PropertiesHelperAwareInterface;
use DigipolisGent\Robo\Helpers\DependencyInjection\RemoteHelperAwareInterface;
use DigipolisGent\Robo\Helpers\DependencyInjection\Traits\AppTaskFactoryAware;
use DigipolisGent\Robo\Helpers\DependencyInjection\Traits\PropertiesHelperAware;
use DigipolisGent\Robo\Helpers\DependencyInjection\Traits\RemoteHelperAware;
use DigipolisGent\Robo\Helpers\Util\PropertiesHelper;
use DigipolisGent\Robo\Helpers\Util\RemoteHelper;
use DigipolisGent\Robo\Helpers\Util\TaskFactory\AbstractApp;
use DigipolisGent\Robo\Task\Deploy\Ssh\Auth\AbstractAuth;
use League\Container\DefinitionContainerInterface;
use Robo\Collection\CollectionBuilder;
use Symfony\Component\Console\Input\InputAwareInterface;

class Generic extends AbstractApp implements PropertiesHelperAwareInterface, RemoteHelperAwareInterface, InputAwareInterface, OutputAwareInterface
{

    use RemoteHelperAware;
    use \DigipolisGent\Robo\Task\Deploy\Tasks;
    use AppTaskFactoryAware;
    use Drupal8UtilsTrait;
    use PropertiesHelperAware;
    use \Boedah\Robo\Task\Drush\loadTasks;
    use \Robo\Common\IO;

    protected $siteInstalled = null;

    public function __construct(ConfigInterface $config, PropertiesHelper $propertiesHelper, RemoteHelper $remoteHelper) {
        parent::__construct($config);
        $this->setPropertiesHelper($propertiesHelper);
        $this->setRemoteHelper($remoteHelper);
    }

    public static function create(DefinitionContainerInterface $container) {
      $object = new static(
        $container->get('config'),
        $container->get(PropertiesHelper::class),
        $container->get(RemoteHelper::class)
      );
      $object->setBuilder(CollectionBuilder::create($container, $object));

      return $object;
    }

    /**
     * Install the site in the current folder.
     *
     * @param string $worker
     *   The server to install the site on.
     * @param AbstractAuth $auth
     *   The ssh authentication to connect to the server.
     * @param array $remote
     *   The remote settings for this server.
     * @param bool $force
     *   Whether or not to force the install even when the site is present.
     *
     * @return \Robo\Contract\TaskInterface
     *   The install task.
     */
    public function installTask($worker, AbstractAuth $auth, $remote, $extra = [], $force = false)
    {
        return $this->taskExec('echo "No automatic install script will be ran."');
    }

    /**
     * Executes database updates of the site in the current folder.
     *
     * Executes database updates of the site in the current folder. Sets
     * the site in maintenance mode before the update and takes in out of
     * maintenance mode after.
     *
     * @param string $worker
     *   The server to install the site on.
     * @param AbstractAuth $auth
     *   The ssh authentication to connect to the server.
     * @param array $remote
     *   The remote settings for this server.
     *
     * @return \Robo\Contract\TaskInterface
     *   The update task.
     */
    public function updateTask($worker, AbstractAuth $auth, $remote, $extra = [])
    {
        return $this->taskExec('echo "No automatic update script will be ran."');
    }

    /**
     * Check if a site is already installed
     *
     * @param string $worker
     *   The server to install the site on.
     * @param AbstractAuth $auth
     *   The ssh authentication to connect to the server.
     * @param array $remote
     *   The remote settings for this server.
     *
     * @return bool
     *   Whether or not the site is installed.
     */
    public function isSiteInstalled($worker, AbstractAuth $auth, $remote)
    {
        if (!is_null($this->siteInstalled)) {
            return $this->siteInstalled;
        }
        $currentWebRoot = $remote['currentdir'];
        $result = $this->taskSsh($worker, $auth)
            ->remoteDirectory($currentWebRoot, true)
            ->exec('ls -al | grep index.php')
            ->run();
        $this->siteInstalled = $result->wasSuccessful();
        return $this->siteInstalled;
    }
}
