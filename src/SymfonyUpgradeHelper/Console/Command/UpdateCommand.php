<?php

namespace SymfonyUpgradeHelper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use SymfonyUpgradeHelper\Updater;
use SymfonyUpgradeHelper\Util\Filesystem;

class UpdateCommand extends Command
{
    private $updater;

    public function __construct()
    {
        parent::__construct();

        $this->updater = new Updater(new Filesystem());
    }

    protected function configure()
    {
        $this->setName('update')
             ->addArgument('dir', InputArgument::REQUIRED, 'The path');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->registerBuiltInFixers($this->updater);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Update stared.'."\n");

        $finder = Finder::create();
        $finder->files()->in($input->getArgument('dir'));

        $collector = $this->updater->update($finder);

        foreach ($collector->getAll() as $info) {

            $output->writeln('<info>File: '.$info->file().'</info>');
            $output->writeln('Fixer: '.$info->fixer().' Level: '.$info->level()."\n");
        }

        $output->writeln('Update finished.');
    }

    private function registerBuiltInFixers(Updater $updater)
    {
        foreach (Finder::create()->files()->in(__DIR__.'/../../Fixer') as $file) {
            $class = 'SymfonyUpgradeHelper\\Fixer\\'.basename($file, '.php');
            $updater->addFixer(new $class());
        }
    }
}
