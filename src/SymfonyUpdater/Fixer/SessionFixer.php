<?php

namespace SymfonyUpdater\Fixer;

use SymfonyUpdater\Fixer;
use SymfonyUpdater\RequireManualFix;

class SessionFixer implements  Fixer
{
    public function fix(\SplFileInfo $file, $content)
    {
        if ('yml' === $file->getExtension()) {
            return $this->fixYml($content);
        }

        if ('twig' === $file->getExtension()) {
            return $this->fixTwig($content);
        }

        if ('php' === $file->getExtension()) {
            return $this->fixPhp($content);
        }

        return $content;
    }

    private function fixYml($content)
    {
        if (0 < preg_match('/default_locale:\s*\w*/m', $content)) {
            throw new RequireManualFix();
        }

        return $content;
    }

    private function fixTwig($content)
    {
        return preg_replace('/(app\.request\.session\.locale|app\.session\.locale)/m', 'app.request.locale', $content);
    }

    private function fixPhp($content)
    {
        if (0 < preg_match('/->getLocale\(\)/m', $content)) {
            throw new RequireManualFix();
        }

        return $content;
    }

    public function getName()
    {
        return 'session_configuration_fixer';
    }
}
