<?php

namespace SymfonyUpdater\Checker;

use SymfonyUpdater\Checker;
use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateLog;
use SymfonyUpdater\UpdateLogger;

class SessionLocalePhpChecker implements Checker, Fixer
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
     * @return bool
     */
    public function support(\SplFileInfo $file)
    {
        if ($file->getExtension() === 'php') {
            return true;
        }

        return false;
    }

    /**
     * @param  \SplFileInfo     $file
     * @param $content
     * @return array|mixed|null
     */
    public function check(\SplFileInfo $file, $content)
    {
        $pattern = '/\$[\w\s>:-]+->getLocale\(\)/';
        $matches = [];

        preg_match_all($pattern, $content, $matches);

        foreach ($matches[0] as $match) {
            $this->logger->log(new UpdateLog($this, $file, UpdateLog::LEVEL_TO_MANUAL_VERIFICATION, $match));
        }

       return preg_replace($pattern, '', $content);
    }

    public function fix(\SplFileInfo $file, $content)
    {
    }

    public function getName()
    {
        return 'session_locale_php_fixer';
    }
}
