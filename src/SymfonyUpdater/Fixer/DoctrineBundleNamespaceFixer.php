<?php

namespace SymfonyUpdater\Fixer;

use SymfonyUpdater\Fixer;

class DoctrineBundleNamespaceFixer implements Fixer
{
    public function fix(\SplFileInfo $file, $content)
    {
        return preg_replace('/(Symfony\\\Bundle\\\DoctrineBundle)/', 'Doctrine\Bundle\DoctrineBundle', $content);
    }

    public function getName()
    {
        return 'doctrine_bundle_namespace';
    }
}
