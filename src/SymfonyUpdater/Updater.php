<?php

namespace SymfonyUpdater;

use Symfony\Component\Finder\Finder;

class Updater
{
    private $updatedFiles = [];

    private $fixers = [];

    public function update(Finder $finder)
    {
        foreach ($finder as $file) {
            if ($file instanceof \SplFileInfo) {
                $this->updatedFiles[] = $file->getRealPath();
            }
        }
    }

    public function getUpdatedFiles()
    {
        return $this->updatedFiles;
    }

    public function addFixer(Fixer $fixer)
    {
        $this->fixers[] = $fixer;
    }

    public function getFixers()
    {
        return $this->fixers;
    }
}
