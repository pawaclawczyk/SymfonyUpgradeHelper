<?php

namespace SymfonyUpdater\Checker;

use SymfonyUpdater\Checker;
use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateLog;
use SymfonyUpdater\UpdateLogger;

class SessionLocaleTwigChecker implements Checker, Fixer
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
     * @param  \SplFileInfo $file
     * @param $content
     * @return string
     */
    public function check(\SplFileInfo $file, $content)
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
     * @param  \SplFileInfo $file
     * @return bool
     */
    public function support(\SplFileInfo $file)
    {
        if ($file->getExtension() === 'twig') {
            return true;
        }

        return false;
    }

    public function fix(\SplFileInfo $file, $content)
    {
    }

    public function getName()
    {
        return 'session_locale_twig_fixer';
    }
}
