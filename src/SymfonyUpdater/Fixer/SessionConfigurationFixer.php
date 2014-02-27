<?php

namespace SymfonyUpdater\Fixer;

use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateLog;
use SymfonyUpdater\UpdateLogger;

class SessionConfigurationFixer implements Fixer
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
        if ($file->getExtension() === 'yml') {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $matches = [];

        $pattern = '/\s*default_locale\s*:\s*(?<locale>\w+)/';

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
        return 'session_configuration_fixer';
    }
}
