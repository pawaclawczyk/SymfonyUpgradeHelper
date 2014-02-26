<?php

namespace SymfonyUpdater\Checker;

use SymfonyUpdater\Checker;

class SessionLocaleTwigChecker implements Checker
{
    /**
     * @param  \SplFileInfo $file
     * @param $content
     * @return array
     */
    public function check(\SplFileInfo $file, $content)
    {
        $pattern = '/{(%|({))[^%]+app\.(request\.)?session\.locale[^%]+(?(2)}|%)}/';

        $matches = [];

        if (!preg_match_all($pattern, $content, $matches)) {
            return;
        }

        foreach ($matches[0] as $match) {
            $info[] = [
                $match,
                self::CERTAINLY,
            ];
        }

        return $info;
    }

    /**
     * @param  \SplFileInfo $file
     * @return boolean
     */
    public function support(\SplFileInfo $file)
    {
        if ($file->getExtension() === 'twig') {
            return true;
        }

        return false;
    }
}
