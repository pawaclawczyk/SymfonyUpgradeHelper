<?php

namespace SymfonyUpdater\Fixer;

use SymfonyUpdater\Fixer;

class DoctrineBundleNamespaceFixer implements Fixer
{
    public function fix(\SplFileInfo $file, $content)
    {
        return preg_replace('/(Symfony\\\Bundle\\\DoctrineBundle\\\DoctrineBundle)/', 'Doctrine\Bundle\DoctrineBundle\DoctrineBundle', $content);
    }
}
