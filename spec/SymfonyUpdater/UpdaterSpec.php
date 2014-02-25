<?php

namespace spec\SymfonyUpdater;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class UpdaterSpec extends ObjectBehavior
{
    public function it_iterate_over_every_file(Finder $finder, \Iterator $iterator, \SplFileInfo $fileA, \SplFileInfo $fileB)
    {
        $finder->getIterator()->willReturn($iterator);

        $iterator->rewind()->willReturn();
        $iterator->next()->willReturn();
        $iterator->valid()->willReturn(true, true, false);
        $iterator->current()->willReturn($fileA, $fileB);

        $fileA->getFilename()->shouldBeCalled();
        $fileB->getFilename()->shouldBeCalled();
        $this->update($finder);
    }

    public function it_has_updated_files_list(Finder $finder, \Iterator $iterator, \SplFileInfo $fileA, \SplFileInfo $fileB)
    {
        $finder->getIterator()->willReturn($iterator);

        $iterator->rewind()->willReturn();
        $iterator->next()->willReturn();
        $iterator->valid()->willReturn(true, true, false);
        $iterator->current()->willReturn($fileA, $fileB);

        $fileA->getFilename()->willReturn('A.php');
        $fileB->getFilename()->willReturn('B.php');

        $this->update($finder);

        $this->getUpdatedFiles()->shouldBe(['A.php', 'B.php']);
    }
}
