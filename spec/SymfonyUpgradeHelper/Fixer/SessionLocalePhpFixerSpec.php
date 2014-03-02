<?php

namespace spec\SymfonyUpgradeHelper\Fixer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpgradeHelper\UpdateInfoCollector;

class SessionLocalePhpFixerSpec extends ObjectBehavior
{
    public function let(UpdateInfoCollector $collector)
    {
        $this->setCollector($collector);
    }

    public function it_is_a_fixer()
    {
        $this->shouldHaveType('SymfonyUpgradeHelper\Fixer');
    }

    public function it_supports_php_file(\SplFileInfo $file)
    {
        $file->getExtension()->willreturn('php');

        $this->support($file)->shouldReturn(true);
    }

    public function it_adds_info_to_collector(UpdateInfoCollector $collector, \SplFileInfo $fileInfo)
    {
        $collector->add(Argument::type('SymfonyUpgradeHelper\UpdateInfo'))->shouldBeCalledTimes(2);

        $content =<<<YML
\$session->getLocale();
\$request->getLocale();
YML;

        $this->fix($fileInfo, $content);
    }

    public function it_returns_unmodified_content(\SplFileInfo $file)
    {
        $content =<<<PHP
\$session->getLocale();
\$request->getLocale();
PHP;
        $expected =<<<PHP
\$session->getLocale();
\$request->getLocale();
PHP;

        $this->fix($file, $content)->shouldReturn($expected);
    }
}
