<?php

namespace SymfonyUpdater\Fixer;

use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateLog;
use SymfonyUpdater\UpdateLogger;

class SessionLocalePhpFixer implements Fixer
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
    public function support(\SplFileInfo $file)
    {
        if ($file->getExtension() === 'php') {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $pattern = '/\$[\w\s>:-]+->getLocale\(\)/';
        $matches = [];

        preg_match_all($pattern, $content, $matches);

        foreach ($matches[0] as $match) {
            $this->logger->log(new UpdateLog($this, $file, UpdateLog::LEVEL_TO_MANUAL_VERIFICATION, $match));
        }

       return preg_replace($pattern, '', $content);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'session_locale_php_fixer';
    }
}
