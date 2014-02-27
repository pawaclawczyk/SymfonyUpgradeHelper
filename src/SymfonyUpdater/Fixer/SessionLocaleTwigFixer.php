<?php

namespace SymfonyUpdater\Fixer;

use SebastianBergmann\Diff\Differ;
use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateLog;
use SymfonyUpdater\UpdateLogger;

class SessionLocaleTwigFixer implements Fixer
{
    private $differ;
    /**
     * @var UpdateLogger
     */
    private $logger;

    /**
     * @param UpdateLogger $logger
     */
    public function __construct(UpdateLogger $logger)
    {
        $this->logger = $logger;
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
            $this->logger->log(new UpdateLog($this, $file, UpdateLog::LEVEL_FIXED, $diff));
        }

        $matches = [];

        $matchPattern = '/{(%|({))[^%}]+(?<!app\.request)\.locale()[^%}]+((?(2)}|%))}/';

        preg_match_all($matchPattern, $fixedContent, $matches);

        foreach ($matches[0] as $match) {
            $this->logger->log(new UpdateLog($this, $file, UpdateLog::LEVEL_TO_MANUAL_VERIFICATION, $match));
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
