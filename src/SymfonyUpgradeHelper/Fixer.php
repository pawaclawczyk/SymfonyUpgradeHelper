<?php

namespace SymfonyUpgradeHelper;

interface Fixer
{
    /**
     * @param UpdateInfoCollector $collector
     */
    public function setCollector(UpdateInfoCollector $collector);

    /**
     * @param  \SplFileInfo $file
     * @return bool
     */
    public function support(\SplFileInfo $file);

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
