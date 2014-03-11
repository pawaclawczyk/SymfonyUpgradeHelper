<?php

namespace spec\SymfonyUpgradeHelper\Fixer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpgradeHelper\UpdateInfoCollector;

class MutableAclFixerSpec extends ObjectBehavior
{
    public function let(UpdateInfoCollector $collector)
    {
        $this->setCollector($collector);
    }

    public function it_is_a_fixer()
    {
        $this->shouldHaveType('SymfonyUpgradeHelper\Fixer');
    }

    public function it_supports_php_files(\SplFileInfo $fileInfo)
    {
        $fileInfo->getExtension()->willReturn('php');

        $this->support($fileInfo)->shouldReturn(true);
    }

    public function it_does_not_support_other_files(\SplFileInfo $fileInfo)
    {
        $fileInfo->getExtension()->willReturn('twig');

        $this->support($fileInfo)->shouldReturn(false);
    }

    public function it_fixes_set_parent_method_definition(\SplFileInfo $fileInfo)
    {
        $content =<<<PHP
class Acl
{
    public function setParentAcl(AclInterface \$parentAcl)
    {
        // some code
    }
}
PHP;

        $expected =<<<PHP
class Acl
{
    public function setParentAcl(AclInterface \$parentAcl = null)
    {
        // some code
    }
}
PHP;

        $this->fix($fileInfo, $content)->shouldReturn($expected);
    }

    public function it_adds_info_to_collector(UpdateInfoCollector $collector, \SplFileInfo $fileInfo)
    {
        $content =<<<PHP
class Acl
{
    public function setParentAcl(AclInterface \$parentAcl)
    {
        // some code
    }
}
PHP;
        $collector->add(Argument::type('SymfonyUpgradeHelper\UpdateInfo'))->shouldBeCalled();

        $this->fix($fileInfo, $content);
    }
}
