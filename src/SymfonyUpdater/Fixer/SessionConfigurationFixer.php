<?php

namespace SymfonyUpdater\Fixer;

use SymfonyUpdater\Fixer;
use SymfonyUpdater\UpdateInfo;
use SymfonyUpdater\UpdateInfoCollector;

class SessionConfigurationFixer implements Fixer
{
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
            $this->collector->add(new UpdateInfo($this, $file, UpdateInfo::LEVEL_TO_MANUAL_VERIFICATION, $match));
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'session_configuration_fixer';
    }
}
