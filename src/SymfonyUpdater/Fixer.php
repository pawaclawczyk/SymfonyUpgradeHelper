<?php

namespace SymfonyUpdater;

interface Fixer
{
    /**
     * @param  \SplFileInfo $file
     * @param $content
     * @return string
     */
    public function fix(\SplFileInfo $file, $content);

    /**
     * @return string
     */
    public function getName();
}
