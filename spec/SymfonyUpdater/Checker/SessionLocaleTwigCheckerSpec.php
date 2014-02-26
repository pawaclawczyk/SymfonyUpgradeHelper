<?php

namespace spec\SymfonyUpdater\Checker;

use PhpSpec\ObjectBehavior;
use SymfonyUpdater\Checker;

class SessionLocaleTwigCheckerSpec extends ObjectBehavior
{
    public function it_is_checker()
    {
        $this->shouldHaveType('SymfonyUpdater\Checker');
    }

    public function it_supports_twig_file(\SplFileInfo $file)
    {
        $file->getExtension()->willReturn('twig');

        $this->support($file)->shouldreturn(true);
    }

    public function it_return_info_and_certainty_level_on_successful_check(\SplFileInfo $file)
    {
        $content =<<<TWIG
<div>{% if app.request.session.locale == 'pl' %}</div>
<div>{{ app.request.session.locale|trans }}</div>
<div>{% if app.session.locale == 'pl' %}</div>
<div>{{ app.session.locale|trans }}</div>
TWIG;

        $this->check($file, $content)->shouldReturn(
            [
                ["{% if app.request.session.locale == 'pl' %}", Checker::CERTAINLY],
                ["{{ app.request.session.locale|trans }}", Checker::CERTAINLY],
                ["{% if app.session.locale == 'pl' %}", Checker::CERTAINLY],
                ["{{ app.session.locale|trans }}", Checker::CERTAINLY],
            ]
        );
    }
}
