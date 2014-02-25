<?php

namespace SymfonyUpdater;

interface Fixer
{
    public function fix(\SplFileInfo $file, $content);

    public function getName();
}
