<?php

namespace DigipolisGent\Robo\Generic\Traits;

use DigipolisGent\Robo\Helpers\Traits\AbstractDeployCommandTrait;
use DigipolisGent\Robo\Task\Deploy\Ssh\Auth\AbstractAuth;


trait UpdateGenericTrait
{
    /**
     * @see \DigipolisGent\Robo\Helpers\Traits\TraitDependencyCheckerTrait
     */
    protected function getUpdateGenericTraitDependencies()
    {
        return [AbstractDeployCommandTrait::class];
    }

    protected function updateTask($worker, AbstractAuth $auth, $remote) {
        return $this->taskExec('echo "No automatic update script will be ran."');
    }
}
