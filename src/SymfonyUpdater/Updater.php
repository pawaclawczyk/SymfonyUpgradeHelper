<?php

namespace SymfonyUpdater;

use Symfony\Component\Finder\Finder;

class Updater
{
    private $updatedFiles = [];

    public function update(Finder $finder)
    {
        foreach ($finder as $file) {
            $this->updatedFiles[] = $file->getFilename();
        }
    }

    public function getUpdatedFiles()
    {
        return $this->updatedFiles;
    }
}
