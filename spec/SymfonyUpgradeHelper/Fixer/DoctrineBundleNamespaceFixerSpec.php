<?php

namespace spec\SymfonyUpgradeHelper\Fixer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpgradeHelper\UpdateInfoCollector;

class DoctrineBundleNamespaceFixerSpec extends ObjectBehavior
{
    public function let(UpdateInfoCollector $collector)
    {
        $this->setCollector($collector);
    }

    public function it_is_a_fixer()
    {
        $this->shouldHaveType('SymfonyUpgradeHelper\Fixer');
    }

    public function it_supports_php_file(\SplFileInfo $fileInfo)
    {
        $fileInfo->getExtension()->willReturn('php');

        $this->support($fileInfo)->shouldReturn(true);
    }

    public function it_adds_info_to_collector(UpdateInfoCollector $collector, \SplFileInfo $fileInfo)
    {
        $collector->add(Argument::type('SymfonyUpgradeHelper\UpdateInfo'))->shouldBeCalled();

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
