<?php

namespace spec\SymfonyUpdater\Fixer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpdater\UpdateLogger;

class DoctrineBundleNamespaceFixerSpec extends ObjectBehavior
{
    public function let(UpdateLogger $logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_is_a_fixer()
    {
        $this->shouldHaveType('SymfonyUpdater\Fixer');
    }

    public function it_supports_php_file(\SplFileInfo $fileInfo)
    {
        $fileInfo->getExtension()->willReturn('php');

        $this->support($fileInfo)->shouldReturn(true);
    }

    public function it_logs_fixing(UpdateLogger $logger, \SplFileInfo $fileInfo)
    {
        $logger->log(Argument::type('SymfonyUpdater\UpdateLog'))->shouldBeCalled();

        $content =<<<YML
new Symfony\Bundle\DoctrineBundle\DoctrineBundle();
YML;

        $this->fix($fileInfo, $content);
    }

    public function it_returns_content_with_removed_match(\SplFileInfo $fileInfo)
    {
        $content =<<<YML
new Symfony\Bundle\DoctrineBundle\DoctrineBundle();
YML;
        $expected =<<<YML
new Doctrine\Bundle\DoctrineBundle\DoctrineBundle();
YML;

        $this->fix($fileInfo, $content)->shouldReturn($expected);
    }
}
