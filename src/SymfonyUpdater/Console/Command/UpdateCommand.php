<?php

namespace SymfonyUpdater\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use SymfonyUpdater\Updater;

class UpdateCommand extends Command
{
    private $updater;

    public function __construct()
    {
        parent::__construct();

        $this->updater = new Updater();
    }

    protected function configure()
    {
        $this->setName('update')
             ->addArgument('dir', InputArgument::REQUIRED, 'The path');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = Finder::create();
        $finder->files()->in($input->getArgument('dir'));

        $this->updater->update($finder);

        foreach ($this->updater->getUpdatedFiles() as $filename) {
            $output->writeln('Updated '.$filename);
        }
    }
}
