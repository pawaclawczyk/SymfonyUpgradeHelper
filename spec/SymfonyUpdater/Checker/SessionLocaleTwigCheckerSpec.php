<?php

namespace spec\SymfonyUpdater\Checker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpdater\Checker;
use SymfonyUpdater\UpdateLogger;

class SessionLocaleTwigCheckerSpec extends ObjectBehavior
{
    public function let(UpdateLogger $logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_is_checker()
    {
        $this->shouldHaveType('SymfonyUpdater\Checker');
    }

    public function it_supports_twig_file(\SplFileInfo $file)
    {
        $file->getExtension()->willReturn('twig');

        $this->support($file)->shouldreturn(true);
    }

    public function it_logs_fixing(UpdateLogger $logger, \SplFileInfo $fileInfo)
    {
        $logger->log(Argument::type('SymfonyUpdater\UpdateLog'))->shouldBeCalledTimes(4);

        $content =<<<TWIG
<div>{% if app.request.session.locale == 'pl' %}</div>
<div>{{ app.request.session.locale|trans }}</div>
<div>{% if app.session.locale == 'pl' %}</div>
<div>{{ app.session.locale|trans }}</div>
TWIG;

        $this->check($fileInfo, $content);
    }

    public function it_returns_content_with_removed_match(\SplFileInfo $file)
    {
        $content =<<<TWIG
<div>{% if app.request.session.locale == 'pl' %}</div>
<div>{{ app.request.session.locale|trans }}</div>
<div>{% if app.session.locale == 'pl' %}</div>
<div>{{ app.session.locale|trans }}</div>
TWIG;
        $expected =<<<PHP
<div></div>
<div></div>
<div></div>
<div></div>
PHP;

        $this->check($file, $content)->shouldReturn($expected);
    }
}
