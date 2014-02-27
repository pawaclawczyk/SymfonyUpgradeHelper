<?php

namespace spec\SymfonyUpdater;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Tests\Iterator\Iterator;
use SymfonyUpdater\Fixer;
use SymfonyUpdater\Util\Filesystem;

class UpdaterSpec extends ObjectBehavior
{
    public function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    public function it_updates_file_when_fixers_change_its_content(Filesystem $filesystem, Finder $finder, Fixer $fixerFoo, Fixer $fixerBar)
    {
        $iterator = new Iterator(['/path/to/file']);
        $finder->getIterator()->willReturn($iterator);
        $filesystem->read(Argument::cetera())->willReturn('File content');

        $this->addFixer($fixerFoo);
        $this->addFixer($fixerBar);

        $fixerFoo->fix(Argument::type('\SplFileInfo'), 'File content')->shouldBeCalled()->willReturn('File content after fixing');
        $fixerBar->fix(Argument::type('\SplFileInfo'), 'File content after fixing')->shouldBeCalled()->willReturn('File content after second fixing');

        $filesystem->write(Argument::cetera(), 'File content after second fixing')->shouldBeCalled();

        $this->update($finder);
    }

    public function it_does_not_update_filew_hen_fixers_do_not_change_its_content(Filesystem $filesystem, Finder $finder, Fixer $fixerFoo, Fixer $fixerBar)
    {
        $iterator = new Iterator(['/path/to/file']);
        $finder->getIterator()->willReturn($iterator);
        $filesystem->read(Argument::cetera())->willReturn('File content');

        $this->addFixer($fixerFoo);
        $this->addFixer($fixerBar);

        $fixerFoo->fix(Argument::type('\SplFileInfo'), 'File content')->shouldBeCalled()->willReturn('File content');
        $fixerBar->fix(Argument::type('\SplFileInfo'), 'File content')->shouldBeCalled()->willReturn('File content');

        $filesystem->write(Argument::cetera())->shouldNotBeCalled();

        $this->update($finder);
    }
}
