<?php

namespace SymfonyUpdater\Fixer;

use SebastianBergmann\Diff\Differ;
use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateLog;
use SymfonyUpdater\UpdateLogger;

class DoctrineBundleNamespaceFixer implements Fixer
{
    private $logger;

    private $differ;

    public function __construct(UpdateLogger $logger)
    {
        $this->logger = $logger;
        $this->differ = new Differ();
    }

    /**
     * {@inheritdoc}
     */
    public function support(\SplFileInfo $file)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $fixedContent = preg_replace('/(Symfony\\\Bundle\\\DoctrineBundle)/', 'Doctrine\Bundle\DoctrineBundle', $content);

        if (md5($content) !== md5($fixedContent)) {
            $diff = $this->differ->diff($content, $content);
            $this->logger->log(new UpdateLog($this, $file, UpdateLog::LEVEL_FIXED, $diff));
        }

        return $fixedContent;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'doctrine_bundle_namespace';
    }
}
