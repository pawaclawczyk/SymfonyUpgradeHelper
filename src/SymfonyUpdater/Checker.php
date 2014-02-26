<?php

namespace SymfonyUpdater;

interface Checker
{
    const CERTAINLY = 0;

    const PROBABLY = 1;

    /**
     * @param  \SplFileInfo $file
     * @param $content
     * @return []
     */
    public function check(\SplFileInfo $file, $content);

    /**
     * @param  \SplFileInfo $file
     * @return boolean
     */
    public function support(\SplFileInfo $file);
}
