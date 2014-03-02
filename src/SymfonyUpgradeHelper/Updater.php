<?php

namespace SymfonyUpgradeHelper;

use Symfony\Component\Finder\Finder;
use SymfonyUpgradeHelper\Util\Filesystem;

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
        $collector = new UpdateInfoCollector();

        foreach ($this->fixers as $fixer) {
            $fixer->setCollector($collector);
        }

        foreach ($finder as $fileInfo) {
            $fixedContent = $content = $this->filesystem->read($fileInfo->getRealPath());

            foreach ($this->fixers as $fixer) {
                $fixedContent = $fixer->fix($fileInfo, $fixedContent);
            }

            if (md5($content) !== md5($fixedContent)) {
                $this->filesystem->write($fileInfo->getRealPath(), $fixedContent);
            }
        }

        return $collector;
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
