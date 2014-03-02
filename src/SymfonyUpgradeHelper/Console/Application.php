<?php

namespace SymfonyUpgradeHelper\Console;

use Symfony\Component\Console\Application as BaseApplication;
use SymfonyUpgradeHelper\Console\Command\UpdateCommand;

class Application extends BaseApplication
{
    const NAME = 'Symfony Updater';

    const VERSION = '0.1.0';

    public function __construct()
    {
        parent::__construct(self::NAME, self::VERSION);

        $this->add(new UpdateCommand());
    }
}
