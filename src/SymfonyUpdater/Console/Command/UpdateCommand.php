<?php

namespace SymfonyUpdater\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use SymfonyUpdater\Updater;
use SymfonyUpdater\Util\Filesystem;

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
        $output->writeln('Updating...');

        $finder = Finder::create();
        $finder->files()->in($input->getArgument('dir'));

        $this->updater->update($finder);

        foreach ($this->updater->getStats() as $file => $stats) {
            $output->writeln('File \''.$file.'\'');

            if (isset($stats['applied'])) {
                $output->writeln('Fixers applied:');
                foreach ($stats['applied'] as $fixer) {
                    $output->writeln('  - '.$fixer);
                }
            }

            if (isset($stats['manual'])) {
                $output->writeln('Require manual fix:');
                foreach ($stats['manual'] as $fixer) {
                    $output->writeln('  - '.$fixer);
                }
            }
        }
    }

    private function registerBuiltInFixers(Updater $updater)
    {
        foreach (Finder::create()->files()->in(__DIR__.'/../../Fixer') as $file) {
            $class = 'SymfonyUpdater\\Fixer\\'.basename($file, '.php');
            $updater->addFixer(new $class());
        }
    }
}
