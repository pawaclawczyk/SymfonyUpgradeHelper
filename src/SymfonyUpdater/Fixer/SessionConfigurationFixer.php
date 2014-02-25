<?php

namespace SymfonyUpdater\Fixer;

use SymfonyUpdater\Fixer;
use SymfonyUpdater\RequireManualFix;

class SessionConfigurationFixer implements  Fixer
{
    public function fix(\SplFileInfo $file, $content)
    {
        if ('yml' !== $file->getExtension()) {
            return $content;
        }

//        var_dump($content);
        if (0 === preg_match('/default_locale:\s*\w*/m', $content)) {
            return $content;
        }

        throw new RequireManualFix();
    }

    public function getName()
    {
        return 'session_configuration_fixer';
    }
}
