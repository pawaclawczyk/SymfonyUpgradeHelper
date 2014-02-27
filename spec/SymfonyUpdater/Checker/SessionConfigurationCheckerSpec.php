<?php

namespace spec\SymfonyUpdater\Checker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpdater\Checker;
use SymfonyUpdater\UpdateLogger;

class SessionConfigurationCheckerSpec extends ObjectBehavior
{
    public function let(UpdateLogger $logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_is_a_checker()
    {
        $this->shouldHaveType('SymfonyUpdater\Checker');
    }

    public function it_supports_yml_file(\SplFileInfo $fileInfo)
    {
        $fileInfo->getExtension()->willReturn('yml');

        $this->support($fileInfo)->shouldReturn(true);
    }

    public function it_does_not_support_php_file(\SplFileInfo $fileInfo)
    {
        $fileInfo->getExtension()->willReturn('php');

        $this->support($fileInfo)->shouldReturn(false);
    }

    public function it_logs_fixing(UpdateLogger $logger, \SplFileInfo $fileInfo)
    {
        $logger->log(Argument::type('SymfonyUpdater\UpdateLog'))->shouldBeCalled();

        $content =<<<YML
framework:
    session:
        default_locale: fr
YML;

        $this->check($fileInfo, $content);
    }

    public function it_returns_content_with_removed_match(\SplFileInfo $fileInfo)
    {
        $content =<<<YML
framework:
    session:
        default_locale: fr
YML;
        $expected =<<<YML
framework:
    session:
YML;

        $this->check($fileInfo, $content)->shouldReturn($expected);
    }

    public function it_does_not_log_fixing(UpdateLogger $logger, \SplFileInfo $fileInfo)
    {
        $logger->log(Argument::type('SymfonyUpdater\UpdateLog'))->shouldNotBeCalled();

        $content =<<<YML
key:
    subKey: value
YML;

        $this->check($fileInfo, $content);
    }

    public function it_returns_unmodified_content(\SplFileInfo $fileInfo)
    {
        $content =<<<YML
key:
    subKey: value
YML;

        $this->check($fileInfo, $content)->shouldReturn($content);
    }
}
