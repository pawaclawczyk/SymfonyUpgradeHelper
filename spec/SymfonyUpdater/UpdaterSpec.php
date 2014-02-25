<?php

namespace spec\SymfonyUpdater;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Finder\Finder;
use SymfonyUpdater\Fixer;

class UpdaterSpec extends ObjectBehavior
{
    public function it_iterate_over_every_file(Finder $finder, \Iterator $iterator, \SplFileInfo $fileFoo, \SplFileInfo $fileBar)
    {
        $finder->getIterator()->willReturn($iterator);

        $iterator->rewind()->willReturn();
        $iterator->next()->willReturn();
        $iterator->valid()->willReturn(true, true, false);
        $iterator->current()->willReturn($fileFoo, $fileBar);

        $fileFoo->getRealPath()->shouldBeCalled();
        $fileBar->getRealPath()->shouldBeCalled();
        $this->update($finder);
    }

    public function it_has_updated_files_list(Finder $finder, \Iterator $iterator, \SplFileInfo $fileFoo, \SplFileInfo $fileBar)
    {
        $finder->getIterator()->willReturn($iterator);

        $iterator->rewind()->willReturn();
        $iterator->next()->willReturn();
        $iterator->valid()->willReturn(true, true, false);
        $iterator->current()->willReturn($fileFoo, $fileBar);

        $fileFoo->getRealPath()->willReturn('Foo.php');
        $fileBar->getRealPath()->willReturn('Bar.php');

        $this->update($finder);

        $this->getUpdatedFiles()->shouldBe(['Foo.php', 'Bar.php']);
    }

    public function it_has_fixers(Fixer $fixerFoo, Fixer $fixerBar)
    {
        $this->addFixer($fixerFoo);
        $this->addFixer($fixerBar);

        $this->getFixers()->shouldReturn([$fixerFoo, $fixerBar]);
    }
}
