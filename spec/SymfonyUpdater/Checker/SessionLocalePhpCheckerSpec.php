<?php

namespace spec\SymfonyUpdater\Checker;

use PhpSpec\ObjectBehavior;
use SymfonyUpdater\Checker;

class SessionLocalePhpCheckerSpec extends ObjectBehavior
{
    public function it_is_checker()
    {
        $this->shouldHaveType('SymfonyUpdater\Checker');
    }

    public function it_supports_php_file(\SplFileInfo $file)
    {
        $file->getExtension()->willreturn('php');

        $this->support($file)->shouldReturn(true);
    }

    public function it_returns_message_and_certainty_level(\SplFileInfo $file)
    {
        $content =<<<PHP
\$session->getLocale();
\$request->getLocale();
PHP;

        $this->check($file, $content)->shouldReturn(
            [
                ['$session->getLocale()', Checker::PROBABLY],
                ['$request->getLocale()', Checker::PROBABLY],
            ]
        );
    }
}
