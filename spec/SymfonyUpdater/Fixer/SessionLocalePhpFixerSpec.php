<?php

namespace spec\SymfonyUpdater\Fixer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpdater\UpdateLogger;

class SessionLocalePhpFixerSpec extends ObjectBehavior
{
    public function let(UpdateLogger $logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_is_a_fixer()
    {
        $this->shouldHaveType('SymfonyUpdater\Fixer');
    }

    public function it_supports_php_file(\SplFileInfo $file)
    {
        $file->getExtension()->willreturn('php');

        $this->support($file)->shouldReturn(true);
    }

    public function it_logs_fixing(UpdateLogger $logger, \SplFileInfo $fileInfo)
    {
        $logger->log(Argument::type('SymfonyUpdater\UpdateLog'))->shouldBeCalled();

        $content =<<<YML
\$session->getLocale();
\$request->getLocale();
YML;

        $this->fix($fileInfo, $content);
    }

    public function it_returns_content_with_removed_match(\SplFileInfo $file)
    {
        $content =<<<PHP
\$session->getLocale();
\$request->getLocale();
PHP;
        $expected =<<<PHP
;
;
PHP;

        $this->fix($file, $content)->shouldReturn($expected);
    }
}