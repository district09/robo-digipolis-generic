<?php

namespace DigipolisGent\Robo\Generic\Traits;

trait GenericUtilsTrait
{
    protected $siteInstalled = null;

    public function setSiteInstalled($installed)
    {
        $this->siteInstalled = $installed;
    }
}
