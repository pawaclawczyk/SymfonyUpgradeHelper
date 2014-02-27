<?php

namespace spec\SymfonyUpdater\Checker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpdater\Checker;
use SymfonyUpdater\UpdateLogger;

class SessionLocalePhpCheckerSpec extends ObjectBehavior
{
    public function let(UpdateLogger $logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_is_checker()
    {
        $this->shouldHaveType('SymfonyUpdater\Checker');
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

        $this->check($fileInfo, $content);
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

        $this->check($file, $content)->shouldReturn($expected);
    }
}
