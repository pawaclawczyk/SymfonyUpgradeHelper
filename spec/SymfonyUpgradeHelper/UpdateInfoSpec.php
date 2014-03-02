<?php

namespace spec\SymfonyUpgradeHelper;

use PhpSpec\ObjectBehavior;
use SymfonyUpgradeHelper\Fixer;

class UpdateInfoSpec extends ObjectBehavior
{
    public function let(Fixer $fixer, \SplFileInfo $fileInfo)
    {
        $this->beConstructedWith($fixer, $fileInfo, $this->LEVEL_FIXED, 'A fixer message.');
    }

    public function it_has_fixer_name(Fixer $fixer)
    {
        $fixer->getName()->willReturn('foo_fixer');

        $this->fixer()->shouldReturn('foo_fixer');
    }

    public function it_has_file_path(\SplFileInfo $fileInfo)
    {
        $fileInfo->getRealPath()->willReturn('/path/to/file');

        $this->file()->shouldReturn('/path/to/file');
    }

    public function it_has_level_fixed()
    {
        $this->level()->shouldReturn($this->LEVEL_FIXED);
    }

    public function it_has_level_to_manual_fix(Fixer $fixer, \SplFileInfo $fileInfo)
    {
        $this->beConstructedWith($fixer, $fileInfo, $this->LEVEL_TO_MANUAL_FIX, 'A fixer message.');

        $this->level()->shouldReturn($this->LEVEL_TO_MANUAL_FIX);
    }

    public function it_has_level_to_manual_verification(Fixer $fixer, \SplFileInfo $fileInfo)
    {
        $this->beConstructedWith($fixer, $fileInfo, $this->LEVEL_TO_MANUAL_VERIFICATION, 'A fixer message.');

        $this->level()->shouldReturn($this->LEVEL_TO_MANUAL_VERIFICATION);
    }

    public function it_has_message()
    {
        $this->message()->shouldReturn('A fixer message.');
    }
}
