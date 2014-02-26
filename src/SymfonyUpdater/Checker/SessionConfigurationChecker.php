<?php

namespace SymfonyUpdater\Checker;

use SymfonyUpdater\Checker;

class SessionConfigurationChecker implements Checker
{
    /**
     * @param  \SplFileInfo $file
     * @param $content
     * @return array
     */
    public function check(\SplFileInfo $file, $content)
    {
        $matches = [];

        $pattern = '/default_locale\s*:\s*(?<locale>\w+)/';

        if (preg_match($pattern,$content, $matches)) {
            return ['Found occurrence of "default_locale: '.$matches['locale'].'"', self::PROBABLY];
        }
    }

    public function support(\SplFileInfo $file)
    {
        if ($file->getExtension() === 'yml') {
            return true;
        }

        return false;
    }
}
