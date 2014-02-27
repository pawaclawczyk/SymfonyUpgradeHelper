<?php

namespace SymfonyUpdater\Checker;

use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateLog;
use SymfonyUpdater\UpdateLogger;

class SessionLocaleTwigFixer implements Fixer
{
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
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $pattern = '/{(%|({))[^%]+app\.(request\.)?session\.locale[^%]+(?(2)}|%)}/';
        $matches = [];

        preg_match_all($pattern, $content, $matches);

        foreach ($matches[0] as $match) {
            $this->logger->log(new UpdateLog($this, $file, UpdateLog::LEVEL_TO_MANUAL_FIX, $match));
        }

        return preg_replace($pattern, '', $content);
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
