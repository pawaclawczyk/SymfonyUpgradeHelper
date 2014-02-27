<?php

namespace SymfonyUpdater\Fixer;

use SebastianBergmann\Diff\Differ;
use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateInfo;
use SymfonyUpdater\UpdateInfoCollector;

class DoctrineBundleNamespaceFixer implements Fixer
{
    private $collector;

    private $differ;

    public function __construct(UpdateInfoCollector $collector)
    {
        $this->collector = $collector;
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
            $this->collector->add(new UpdateInfo($this, $file, UpdateInfo::LEVEL_FIXED, $diff));
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
