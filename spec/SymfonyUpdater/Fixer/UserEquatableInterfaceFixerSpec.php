<?php

namespace spec\SymfonyUpdater\Fixer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpdater\UpdateInfoCollector;

class UserEquatableInterfaceFixerSpec extends ObjectBehavior
{
    public function let(UpdateInfoCollector $collector)
    {
        $this->setCollector($collector);
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

    public function it_does_not_support_other_than_php_file(\SplFileInfo $fileInfo)
    {
        $this->support($fileInfo)->shouldReturn(false);
    }

    public function it_fixes_file_content(\SplFileInfo $fileInfo)
    {
        $content =<<<PHP
<?php

use Symfony\Component\Security\Core\User\UserInterface;

class TestUser implements UserInterface
{
    public function equals(UserInterface \$user)
    {
        // some code
    }
}
PHP;

        $expected =<<<PHP
<?php

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class TestUser implements UserInterface, EquatableInterface
{
    public function isEqualTo(UserInterface \$user)
    {
        // some code
    }
}
PHP;

        $this->fix($fileInfo, $content)->shouldReturn($expected);
    }

    public function it_adds_info_to_collector(\SplFileInfo $fileInfo, UpdateInfoCollector $collector)
    {
        $content =<<<PHP
<?php

use Symfony\Component\Security\Core\User\UserInterface;

class TestUser implements UserInterface
{
    public function equals(UserInterface \$user)
    {
        // some code
    }
}
PHP;

        $collector->add(Argument::type('SymfonyUpdater\UpdateInfo'))->shouldBeCalled();

        $this->fix($fileInfo, $content);
    }

    public function it_adds_info_to_collector_about_potential_fix_requirement(\SplFileInfo $fileInfo, UpdateInfoCollector $collector)
    {
        $content =<<<PHP
<?php

class Test
{
    public function equals(UserInterface \$user)
    {
        // some code
    }

    public function test()
    {
        \$this->equals();
    }
}
PHP;

        $collector->add(Argument::type('SymfonyUpdater\UpdateInfo'))->shouldBeCalledTimes(2);

        $this->fix($fileInfo, $content)->shouldReturn($content);
    }
}
