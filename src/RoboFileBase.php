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

}
