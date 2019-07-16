<?php

namespace DigipolisGent\Robo\Generic;

use DigipolisGent\Robo\Helpers\AbstractRoboFile;
use DigipolisGent\Robo\Task\Deploy\Ssh\Auth\AbstractAuth;

class RoboFileBase extends AbstractRoboFile
{
    use \DigipolisGent\Robo\Task\CodeValidation\loadTasks;
    use \DigipolisGent\Robo\Helpers\Traits\AbstractCommandTrait;
    use \DigipolisGent\Robo\Task\Deploy\Commands\loadCommands;
    use Traits\BuildGenericTrait;
    use Traits\DeployGenericTrait;
    use Traits\UpdateGenericTrait;
    use Traits\InstallGenericTrait;
    use Traits\GenericUtilsTrait;
    use Traits\SyncGenericTrait;

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

    public function digipolisValidateCode()
    {
        return $this->taskExec('echo "Not validating code for generic php projects."');
    }
}
