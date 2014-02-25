<?php

namespace spec\SymfonyUpdater;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Finder\Finder;
use SymfonyUpdater\Fixer;
use SymfonyUpdater\RequireManualFix;
use SymfonyUpdater\Util\Filesystem;

class UpdaterSpec extends ObjectBehavior
{
    public function let(Filesystem $filesystem, Finder $finder, \Iterator $iterator, \SplFileInfo $fileFoo, \SplFileInfo $fileBar)
    {
        $this->beConstructedWith($filesystem);

        $finder->getIterator()->willReturn($iterator);

        $iterator->rewind()->willReturn();
        $iterator->valid()->willReturn(true, true, false);
        $iterator->current()->willReturn($fileFoo, $fileBar);
        $iterator->next()->willReturn();

        $fileFoo->getRealPath()->willReturn('/tmp/Foo.php');
        $fileBar->getRealPath()->willReturn('/tmp/Bar.php');

        $fileFoo->isFile()->willReturn(true);
        $fileBar->isFile()->willReturn(true);

        $fileFoo->isReadable()->willReturn(true);
        $fileBar->isReadable()->willReturn(true);
    }

    public function it_reads_file_content(Filesystem $filesystem, Finder $finder, \SplFileInfo $fileFoo, \SplFileInfo $fileBar)
    {
        $fileFoo->getRealPath()->willReturn('/tmp/Foo.php');
        $fileBar->getRealPath()->willReturn('/tmp/Bar.php');

        $filesystem->read('/tmp/Foo.php')->shouldBeCalled();
        $filesystem->read('/tmp/Bar.php')->shouldBeCalled();

        $this->update($finder);
    }

    public function it_reads_only_regular_file(Filesystem $filesystem, Finder $finder, \SplFileInfo $fileFoo, \SplFileInfo $fileBar)
    {
        $fileFoo->isFile()->willReturn(false);
        $fileBar->isFile()->willReturn(true);

        $filesystem->read('/tmp/Foo.php')->shouldNotBeCalled();
        $filesystem->read('/tmp/Bar.php')->shouldBeCalled();

        $this->update($finder);
    }

    public function it_reads_only_readable_file(Filesystem $filesystem, Finder $finder, \SplFileInfo $fileFoo, \SplFileInfo $fileBar)
    {
        $fileFoo->isReadable()->willReturn(true);
        $fileBar->isReadable()->willReturn(false);

        $filesystem->read('/tmp/Foo.php')->shouldBeCalled();
        $filesystem->read('/tmp/Bar.php')->shouldNotBeCalled();

        $this->update($finder);
    }

    public function it_pass_file_info_and_file_content_to_fixer(Filesystem $filesystem, Finder $finder, \SplFileInfo $fileFoo, \SplFileInfo $fileBar, Fixer $fixer)
    {
        $filesystem->read(Argument::cetera())->willReturn('File Foo content', 'File Bar content');

        $fixer->getName()->willReturn('fixer');
        $this->addFixer($fixer);

        $fixer->fix($fileFoo, 'File Foo content')->shouldBeCalled()->willReturn('File Foo content');
        $fixer->fix($fileBar, 'File Bar content')->shouldBeCalled()->willReturn('File Bar content');

        $this->update($finder);
    }

    public function it_collects_files_fixing_statistics(Filesystem $filesystem, Finder $finder, \SplFileInfo $fileFoo, \SplFileInfo $fileBar, Fixer $fixerFoo, Fixer $fixerBar)
    {
        $filesystem->read(Argument::cetera())->willReturn('File Foo content', 'File Bar content');
        $filesystem->write(Argument::cetera())->willReturn();

        $fixerFoo->getName()->willReturn('fixer_foo');
        $fixerBar->getName()->willReturn('fixer_bar');

        $this->addFixer($fixerFoo);
        $this->addFixer($fixerBar);

        $fixerFoo->fix($fileFoo, 'File Foo content')->willReturn('File FooFoo content');
        $fixerFoo->fix($fileBar, 'File Bar content')->willReturn('File Bar content');

        $fixerBar->fix($fileFoo, 'File FooFoo content')->willReturn('File FooFoo content.');
        $fixerBar->fix($fileBar, 'File Bar content')->willReturn('File Bar content.');

        $this->update($finder);

        $this->getStats()->shouldReturn(
            [
                '/tmp/Foo.php' => [
                    'applied' =>[
                        'fixer_foo',
                        'fixer_bar',
                    ],
                ],
                '/tmp/Bar.php' => [
                    'applied' => [
                        'fixer_bar'
                    ],
                ],
            ]
        );
    }

    public function it_collects_files_statistics_for_manual_fix(Filesystem $filesystem, Finder $finder, \SplFileInfo $fileFoo, \SplFileInfo $fileBar, Fixer $fixerFoo, Fixer $fixerBar)
    {
        $filesystem->read(Argument::cetera())->willReturn('File Foo content', 'File Bar content');
        $filesystem->write(Argument::cetera())->willReturn();

        $fixerFoo->getName()->willReturn('fixer_foo');
        $fixerBar->getName()->willReturn('fixer_bar');

        $this->addFixer($fixerFoo);
        $this->addFixer($fixerBar);

        $fixerFoo->fix($fileFoo, 'File Foo content')->willThrow(new RequireManualFix());
        $fixerBar->fix($fileFoo, 'File Foo content')->willReturn('File Foo content.');

        $fixerFoo->fix($fileBar, 'File Bar content')->willReturn('File Bar content');
        $fixerBar->fix($fileBar, 'File Bar content')->willReturn('File Bar content.');

        $this->update($finder);

        $this->getStats()->shouldReturn(
            [
                '/tmp/Foo.php' => [
                    'manual' => [
                        'fixer_foo',
                    ],
                    'applied' => [
                        'fixer_bar',
                    ],
                ],
                '/tmp/Bar.php' => [
                    'applied' => [
                        'fixer_bar',
                    ],
                ],
            ]
        );
    }

    public function it_saves_fixed_content_to_file(Filesystem $filesystem, Finder $finder, \SplFileInfo $fileFoo, \SplFileInfo $fileBar, Fixer $fixerFoo, \SplFileObject $fileObject)
    {
        $filesystem->read(Argument::cetera())->willReturn('File Foo content', 'File Bar content');

        $fixerFoo->getName()->willReturn('fixer_foo');
        $this->addFixer($fixerFoo);

        $fixerFoo->fix($fileFoo, 'File Foo content')->willReturn('File FooFoo content');
        $fixerFoo->fix($fileBar, 'File Bar content')->willReturn('File Bar content');

        $filesystem->write('/tmp/Foo.php', 'File FooFoo content')->shouldBeCalled();

        $this->update($finder);
    }
}
