# Robo Digipolis Generic

[![Latest Stable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-generic/v/stable)](https://packagist.org/packages/digipolisgent/robo-digipolis-generic)
[![Latest Unstable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-generic/v/unstable)](https://packagist.org/packages/digipolisgent/robo-digipolis-generic)
[![Total Downloads](https://poser.pugx.org/digipolisgent/robo-digipolis-generic/downloads)](https://packagist.org/packages/digipolisgent/robo-digipolis-generic)
[![License](https://poser.pugx.org/digipolisgent/robo-digipolis-generic/license)](https://packagist.org/packages/digipolisgent/robo-digipolis-generic)

[![Build Status](https://travis-ci.org/digipolisgent/robo-digipolis-generic.svg?branch=develop)](https://travis-ci.org/digipolisgent/robo-digipolis-generic)
[![Maintainability](https://api.codeclimate.com/v1/badges/f3b213f3d449af134290/maintainability)](https://codeclimate.com/github/digipolisgent/robo-digipolis-generic/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/f3b213f3d449af134290/test_coverage)](https://codeclimate.com/github/digipolisgent/robo-digipolis-generic/test_coverage)
[![PHP 7 ready](https://php7ready.timesplinter.ch/digipolisgent/robo-digipolis-generic/develop/badge.svg)](https://travis-ci.org/digipolisgent/robo-digipolis-generic)

Used by digipolis, serving as an example.

This package contains a RoboFileBase class that can be used in your own
RoboFile. All commands can be overwritten by overwriting the parent method.

## Example

```php
<?php

use DigipolisGent\Robo\Generic\RoboFileBase;

class RoboFile extends RoboFileBase
{
    use \Robo\Task\Base\loadTasks;

    /**
     * @inheritdoc
     */
    public function digipolisDeployGeneric(
        array $arguments,
        $opts = [
            'app' => 'default',
            'worker' => null,
        ]
    ) {
        $collection = parent::digipolisDeployGeneric($arguments, $opts);
        $collection->taskExec('/usr/bin/custom-post-release-script.sh');
        return $collection;
    }
}

```

## Available commands

Following the example above, these commands will be available:

```bash
digipolis:backup-generic           Create a backup of files
(sites/default/files) and database.
digipolis:build-generic            Build a generic site and package it.
digipolis:clean-dir                Partially clean directories.
digipolis:clear-op-cache           Command digipolis:database-backup.
digipolis:database-backup          Command digipolis:database-backup.
digipolis:database-restore         Command digipolis:database-restore.
digipolis:deploy-generic           Build a generic site and push it to the
servers.
digipolis:download-backup-generic  Download a backup of files and database.
digipolis:init-generic-remote      Install or update a remote site.
digipolis:install-generic          Install the site in the current folder.
digipolis:package-project          Package a directory into an archive.
digipolis:push-package             Command digipolis:push-package.
digipolis:restore-backup-generic   Restore a backup of files and database.
digipolis:sync-generic             Sync the database and files between two
generic sites.
digipolis:update-generic           Executes database updates of the site
in the current folder.
digipolis:upload-backup-generic    Upload a files and database backup to a server.
```
