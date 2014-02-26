<?php

namespace spec\SymfonyUpdater\Checker;

use PhpSpec\ObjectBehavior;
use SymfonyUpdater\Checker;

class SessionConfigurationCheckerSpec extends ObjectBehavior
{
    public function it_is_a_checker()
    {
        $this->shouldHaveType('SymfonyUpdater\Checker');
    }

    public function it_supports_yml_file(\SplFileInfo $file)
    {
        $file->getExtension()->willReturn('yml');

        $this->support($file)->shouldReturn(true);
    }

    public function it_does_not_support_php_file(\SplFileInfo $file)
    {
        $file->getExtension()->willReturn('php');

        $this->support($file)->shouldReturn(false);
    }

    public function it_returns_array_with_info_and_certainty_on_successful_check(\SplFileInfo $file)
    {
        $content =<<<YML
framework:
    session:
        default_locale: fr
YML;
        $this->check($file, $content)->shouldReturn(
            [
                'Found occurrence of "default_locale: fr"',
                Checker::PROBABLY,
            ]
        );
    }

    public function it_returns_null_on_failure_check(\SplFileInfo $file)
    {
        $content =<<<YML
key:
    subKey: value
YML;

        $this->check($file, $content)->shouldReturn(null);
    }
}
