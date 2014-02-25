<?php

namespace spec\SymfonyUpdater\Fixer;

use PhpSpec\ObjectBehavior;

class DoctrineBundleNamespaceFixerSpec extends ObjectBehavior
{
    public function it_is_fixer()
    {
        $this->shouldHaveType('SymfonyUpdater\Fixer');
    }

    public function it_replaces_namespace_in_content(\SplFileInfo $file)
    {
        $input =<<<TEXT
new Symfony\Bundle\DoctrineBundle\DoctrineBundle();
TEXT;
        $outpu =<<<TEXT
new Doctrine\Bundle\DoctrineBundle\DoctrineBundle();
TEXT;

        $this->fix($file, 'Symfony\Bundle\DoctrineBundle\DoctrineBundle')->shouldReturn('Doctrine\Bundle\DoctrineBundle\DoctrineBundle');
    }
}
