<?php

namespace SymfonyUpdater\Fixer;

use SebastianBergmann\Diff\Differ;
use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateInfo;
use SymfonyUpdater\UpdateInfoCollector;

class SessionLocaleTwigFixer implements Fixer
{
    private $differ;
    /**
     * @var UpdateInfoCollector
     */
    private $collector;

    /**
     * @param UpdateInfoCollector $collector
     */
    public function __construct(UpdateInfoCollector $collector)
    {
        $this->collector = $collector;
        $this->differ = new Differ();
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $fixPattern = '/({(%|({))[^%]+app\.)(request\.)?session(\.locale[^%]+(?(3)}|%)})/';
        $replacement = '\\1request\\5';

        $fixedContent = preg_replace($fixPattern, $replacement, $content);

        if (md5($content) !== md5($fixedContent)) {
            $diff = $this->differ->diff($content, $fixedContent);
            $this->collector->add(new UpdateInfo($this, $file, UpdateInfo::LEVEL_FIXED, $diff));
        }

        $matches = [];

        $matchPattern = '/{(%|({))[^%}]+(?<!app\.request)\.locale()[^%}]+((?(2)}|%))}/';

        preg_match_all($matchPattern, $fixedContent, $matches);

        foreach ($matches[0] as $match) {
            $this->collector->add(new UpdateInfo($this, $file, UpdateInfo::LEVEL_TO_MANUAL_VERIFICATION, $match));
        }

        return $fixedContent;
    }

    /**
     * {@inheritdoc}
     */
    public function support(\SplFileInfo $file)
    {
        if ($file->getExtension() === 'twig') {
            return true;
        }

        return false;
    }

    public function getName()
    {
        return 'session_locale_twig_fixer';
    }
}
