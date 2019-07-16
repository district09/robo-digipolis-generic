<?php

namespace DigipolisGent\Robo\Generic\Traits;

use DigipolisGent\Robo\Helpers\Traits\AbstractDeployCommandTrait;

trait BuildGenericTrait
{
    /**
     * @see \DigipolisGent\Robo\Helpers\Traits\TraitDependencyCheckerTrait
     */
    protected function getBuildGenericTraitDependencies()
    {
        return [AbstractDeployCommandTrait::class];
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
}
