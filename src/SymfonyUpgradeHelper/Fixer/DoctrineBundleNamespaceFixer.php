<?php

namespace SymfonyUpgradeHelper\Fixer;

use SebastianBergmann\Diff\Differ;
use SymfonyUpgradeHelper\Fixer;
use SymfonyUpgradeHelper\UpdateInfo;
use SymfonyUpgradeHelper\UpdateInfoCollector;

class DoctrineBundleNamespaceFixer implements Fixer
{
    /**
     * @var UpdateInfoCollector
     */
    private $collector;

    /**
     * @var Differ
     */
    private $differ;

    public function __construct()
    {
        $this->differ = new Differ();
    }

    /**
     * @param UpdateInfoCollector $collector
     */
    public function setCollector(UpdateInfoCollector $collector)
    {
        $this->collector = $collector;
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
