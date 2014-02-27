<?php

namespace spec\SymfonyUpdater\Fixer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpdater\UpdateInfoCollector;

class SessionConfigurationFixerSpec extends ObjectBehavior
{
    public function let(UpdateInfoCollector $collector)
    {
        $this->beConstructedWith($collector);
    }

    public function it_is_a_fixer()
    {
        $this->shouldHaveType('SymfonyUpdater\Fixer');
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

    public function it_returns_content_unmodified_content(\SplFileInfo $fileInfo)
    {
        $content =<<<YML
framework:
    session:
        default_locale: fr
YML;

        $expected =<<<YML
framework:
    session:
        default_locale: fr
YML;

        $this->fix($fileInfo, $content)->shouldReturn($expected);
    }

    public function it_adds_info_to_collector(UpdateInfoCollector $collector, \SplFileInfo $fileInfo)
    {
        $collector->add(Argument::type('SymfonyUpdater\UpdateInfo'))->shouldBeCalled();

        $content =<<<YML
framework:
    session:
        default_locale: fr
YML;

        $this->fix($fileInfo, $content);
    }

    public function it_does_not_add_info_to_collector(UpdateInfoCollector $collector, \SplFileInfo $fileInfo)
    {
        $collector->add(Argument::type('SymfonyUpdater\UpdateInfo'))->shouldNotBeCalled();

        $content =<<<YML
key:
    subKey: value
YML;

        $this->fix($fileInfo, $content);
    }
}
