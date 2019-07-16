<?php

namespace DigipolisGent\Robo\Generic\Traits;

use DigipolisGent\Robo\Helpers\Traits\AbstractDeployCommandTrait;
use DigipolisGent\Robo\Task\Deploy\Ssh\Auth\AbstractAuth;

trait InstallGenericTrait
{
    /**
     * @see \DigipolisGent\Robo\Helpers\Traits\TraitDependencyCheckerTrait
     */
    protected function getInstallGenericTraitDependencies()
    {
        return [AbstractDeployCommandTrait::class, GenericUtilsTrait::class];
    }

    protected function installTask($worker, AbstractAuth $auth, $remote, $extra = array(), $force = false) {
       return $this->taskExec('echo "No automatic install script will be ran."');
    }
}
