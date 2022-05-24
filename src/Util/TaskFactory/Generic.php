<?php

namespace DigipolisGent\Robo\Generic\Util\TaskFactory;

use DigipolisGent\Robo\Helpers\Util\TaskFactory\AbstractApp;
use DigipolisGent\Robo\Task\Deploy\Ssh\Auth\AbstractAuth;

class Generic extends AbstractApp
{
    use \DigipolisGent\Robo\Task\Deploy\Tasks;

    protected $siteInstalled = null;

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

    public function clearCacheTask($worker, $auth, $remote)
    {
        return false;
    }

}
