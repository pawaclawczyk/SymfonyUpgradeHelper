<?php

namespace spec\SymfonyUpgradeHelper\Fixer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SymfonyUpgradeHelper\UpdateInfoCollector;

class SessionLocaleTwigFixerSpec extends ObjectBehavior
{
    public function let(UpdateInfoCollector $collector)
    {
        $this->setCollector($collector);
    }

    public function it_is_a_fixer()
    {
        $this->shouldHaveType('SymfonyUpgradeHelper\Fixer');
    }

    public function it_supports_twig_file(\SplFileInfo $file)
    {
        $file->getExtension()->willReturn('twig');

        $this->support($file)->shouldreturn(true);
    }

    public function it_returns_fixed_content(\SplFileInfo $file)
    {
        $content =<<<TWIG
<div>{% if app.request.session.locale == 'pl' %}</div>
<div>{{ app.request.session.locale|filter }}</div>
<div>{% if app.session.locale == 'pl' %}</div>
<div>{{ app.session.locale|filter }}</div>
<div>{% if profile.locale == 'pl' %}</div>
<div>{{ profile.locale|filter }}</div>
TWIG;
        $expected =<<<PHP
<div>{% if app.request.locale == 'pl' %}</div>
<div>{{ app.request.locale|filter }}</div>
<div>{% if app.request.locale == 'pl' %}</div>
<div>{{ app.request.locale|filter }}</div>
<div>{% if profile.locale == 'pl' %}</div>
<div>{{ profile.locale|filter }}</div>
PHP;

        $this->fix($file, $content)->shouldReturn($expected);
    }

    public function it_adds_info_to_collector(UpdateInfoCollector $collector, \SplFileInfo $fileInfo)
    {
        $collector->add(Argument::type('SymfonyUpgradeHelper\UpdateInfo'))->shouldBeCalledTimes(3);

        $content =<<<TWIG
<div>{% if app.request.session.locale == 'pl' %}</div>
<div>{{ app.request.session.locale|filter }}</div>
<div>{% if app.session.locale == 'pl' %}</div>
<div>{{ app.session.locale|filter }}</div>
<div>{% if profile.locale == 'pl' %}</div>
<div>{{ profile.locale|filter }}</div>
TWIG;

        $this->fix($fileInfo, $content);
    }
}
