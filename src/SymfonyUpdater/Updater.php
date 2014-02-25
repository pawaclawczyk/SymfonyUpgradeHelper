<?php

namespace SymfonyUpdater;

use Symfony\Component\Finder\Finder;
use SymfonyUpdater\Util\Filesystem;

class Updater
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var []
     */
    private $stats = [];

    /**
     * @var Fixer[]
     */
    private $fixers = [];

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function update(Finder $finder)
    {
        foreach ($finder as $fileInfo) {
            if (! $fileInfo->isFile() || ! $fileInfo->isReadable()) {
                continue;
            };

            $originalContent = $content = $this->filesystem->read($fileInfo->getRealPath());

            foreach ($this->fixers as $fixer) {
                $oldContent = $content;

                try {
                    $content = $fixer->fix($fileInfo, $content);
                } catch (RequireManualFix $e) {
                    $this->stats[$fileInfo->getRealPath()]['manual'][] = $fixer->getName();
                }

                if ($oldContent !== $content) {
                    $this->stats[$fileInfo->getRealPath()]['applied'][] = $fixer->getName();
                }
            }

            if ($originalContent !== $content) {
                $this->filesystem->write($fileInfo->getRealPath(), $content);
            }
        }
    }

    public function getStats()
    {
        return $this->stats;
    }

    public function addFixer(Fixer $fixer)
    {
        $this->fixers[] = $fixer;
    }
}
