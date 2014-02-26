<?php

namespace SymfonyUpdater\Checker;

use SymfonyUpdater\Checker;

class SessionLocalePhpChecker implements Checker
{
    /**
     * @param  \SplFileInfo $file
     * @return boolean
     */
    public function support(\SplFileInfo $file)
    {
        if ($file->getExtension() === 'php') {
            return true;
        }

        return false;
    }

    /**
     * @param  \SplFileInfo $file
     * @param $content
     * @return array|void
     */
    public function check(\SplFileInfo $file, $content)
    {
        $pattern = '/\$[\w\s>:-]+->getLocale\(\)/';
        $matches = [];

        if (!preg_match_all($pattern, $content, $matches)) {
            return;
        }

        $info = [];

        foreach ($matches[0] as $match) {
            $info[] = [
                $match,
                self::PROBABLY,
            ];
        }

        return $info;
    }
}
