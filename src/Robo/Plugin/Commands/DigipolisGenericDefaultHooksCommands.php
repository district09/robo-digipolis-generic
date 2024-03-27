<?php

namespace DigipolisGent\Robo\Generic\Robo\Plugin\Commands;

use DigipolisGent\Robo\Helpers\EventHandler\DefaultHandler\IsSiteInstalledHandler;
use Robo\Tasks;

class DigipolisGenericDefaultHooksCommands extends Tasks
{

    /**
     * Default handler for the digipolis:is-site-installed event.
     *
     * @hook on-event digipolis:is-site-installed
     */
    public function getIsSiteInstalledHandler() {
        return new IsSiteInstalledHandler();
    }
}
