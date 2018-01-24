<?php

namespace DigipolisGent\Robo\Generic;

use DigipolisGent\Robo\Helpers\AbstractRoboFile;
use DigipolisGent\Robo\Task\Deploy\Ssh\Auth\AbstractAuth;

class RoboFileBase extends AbstractRoboFile
{

    protected $siteInstalled = null;

    protected function installTask($worker, AbstractAuth $auth, $remote, $extra = array(), $force = false) {
       return $this->taskExec('echo "No automatic install script will be ran."');
    }

    public function setSiteInstalled($installed)
    {
        $this->siteInstalled = $installed;
    }

    protected function isSiteInstalled($worker, AbstractAuth $auth, $remote)
    {
        if (!is_null($this->siteInstalled)) {
            return $this->siteInstalled;
        }
        $currentWebRoot = $remote['currentdir'];
        $result = $this->taskSsh($worker, $auth)
            ->remoteDirectory($currentWebRoot, true)
            ->exec('ls -al | grep index.php')
            ->run();
        $this->setSiteInstalled($result->wasSuccessful());
        return $this->siteInstalled;
    }

    protected function updateTask($worker, AbstractAuth $auth, $remote) {
        return $this->taskExec('echo "No automatic update script will be ran."');
    }

    public function digipolisValidateCode() {
        return $this->taskExec('echo "Not validating code for generic php projects."');
    }

    /**
     * Build a Generic site and push it to the servers.
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
        return $this->deployTask($arguments, $opts);
    }

    /**
     * Build a Generic site and package it.
     *
     * @param string $archivename
     *   Name of the archive to create.
     *
     * @usage test.tar.gz
     */
    public function digipolisBuildGeneric($archivename = null)
    {
        return $this->buildTask($archivename);
    }

    /**
     * Install or update a Generic remote site.
     *
     * @param string $server
     *   The server to install the site on.
     * @param string $user
     *   The ssh user to use to connect to the server.
     * @param string $privateKeyFile
     *   The path to the private key file to use to connect to the server.
     * @param array $opts
     *    The options for this command.
     *
     * @option app The name of the app we're deploying. Used to determine the
     *   directory in which the drupal site can be found.
     *
     * @usage --app=myapp 10.25.2.178 sshuser /home/myuser/.ssh/id_rsa
     */
    public function digipolisInitGenericRemote(
        $server,
        $user,
        $privateKeyFile,
        $opts = [
            'app' => 'default',
            'force-install' => false
        ]
    ) {
        $remote = $this->getRemoteSettings($server, $user, $privateKeyFile, $opts['app']);
        $auth = new KeyFile($user, $privateKeyFile);
        return $this->initRemoteTask($privateKeyFile, $auth, $remote, $opts, $opts['force-install']);
    }

    /**
     * Sync the database and files between two Generic sites.
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
        return $this->syncTask(
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

    /**
     * Create a backup of files (storage folder) and database.
     *
     * @param string $host
     *   The server of the website.
     * @param string $user
     *   The ssh user to use to connect to the server.
     * @param string $keyFile
     *   The path to the private key file to use to connect to the server.
     * @param array $opts
     *    The options for this command.
     *
     * @option app The name of the app we're creating the backup for.
     */
    public function digipolisBackupGeneric(
        $host,
        $user,
        $keyFile,
        $opts = ['app' => 'default', 'files' => false, 'data' => false]
    ) {
        if (!$opts['files'] && !$opts['data']) {
            $opts['files'] = true;
            $opts['data'] = true;
        }
        $remote = $this->getRemoteSettings($host, $user, $keyFile, $opts['app']);
        $auth = new KeyFile($user, $keyFile);
        return $this->backupTask($host, $auth, $remote, $opts);
    }

    /**
     * Restore a backup of files (storage folder) and database.
     *
     * @param string $host
     *   The server of the website.
     * @param string $user
     *   The ssh user to use to connect to the server.
     * @param string $keyFile
     *   The path to the private key file to use to connect to the server.
     * @param array $opts
     *    The options for this command.
     *
     * @option app The name of the app we're restoring the backup for.
     * @option timestamp The timestamp when the backup was created. Defaults to
     *   the current time, which is only useful when syncing between servers.
     *
     * @see digipolisBackupGeneric
     */
    public function digipolisRestoreBackupGeneric(
        $host,
        $user,
        $keyFile,
        $opts = [
            'app' => 'default',
            'timestamp' => null,
            'files' => false,
            'data' => false,
        ]
    ) {
        if (!$opts['files'] && !$opts['data']) {
            $opts['files'] = true;
            $opts['data'] = true;
        }
        $remote = $this->getRemoteSettings($host, $user, $keyFile, $opts['app'], $opts['timestamp']);
        $auth = new KeyFile($user, $keyFile);
        return $this->restoreBackupTask($host, $auth, $remote, $opts);
    }

    /**
     * Download a backup of files (storage folder) and database.
     *
     * @param string $host
     *   The server of the website.
     * @param string $user
     *   The ssh user to use to connect to the server.
     * @param string $keyFile
     *   The path to the private key file to use to connect to the server.
     * @param array $opts
     *    The options for this command.
     *
     * @option app The name of the app we're downloading the backup for.
     * @option timestamp The timestamp when the backup was created. Defaults to
     *   the current time, which is only useful when syncing between servers.
     *
     * @see digipolisBackupGeneric
     */
    public function digipolisDownloadBackupGeneric(
        $host,
        $user,
        $keyFile,
        $opts = [
            'app' => 'default',
            'timestamp' => null,
            'files' => false,
            'data' => false,
        ]
    ) {
        if (!$opts['files'] && !$opts['data']) {
            $opts['files'] = true;
            $opts['data'] = true;
        }
        $remote = $this->getRemoteSettings($host, $user, $keyFile, $opts['app'], $opts['timestamp']);
        $auth = new KeyFile($user, $keyFile);
        return $this->downloadBackupTask($host, $auth, $remote, $opts);
    }

    /**
     * Upload a backup of files (storage folder) and database to a server.
     *
     * @param string $host
     *   The server of the website.
     * @param string $user
     *   The ssh user to use to connect to the server.
     * @param string $keyFile
     *   The path to the private key file to use to connect to the server.
     * @param array $opts
     *    The options for this command.
     *
     * @option app The name of the app we're uploading the backup for.
     * @option timestamp The timestamp when the backup was created. Defaults to
     *   the current time, which is only useful when syncing between servers.
     *
     * @see digipolisBackupGeneric
     */
    public function digipolisUploadBackupGeneric(
        $host,
        $user,
        $keyFile,
        $opts = [
            'app' => 'default',
            'timestamp' => null,
            'files' => false,
            'data' => false,
        ]
    ) {
        if (!$opts['files'] && !$opts['data']) {
            $opts['files'] = true;
            $opts['data'] = true;
        }
        $remote = $this->getRemoteSettings($host, $user, $keyFile, $opts['app'], $opts['timestamp']);
        $auth = new KeyFile($user, $keyFile);
        return $this->uploadBackupTask($host, $auth, $remote, $opts);
    }

}
