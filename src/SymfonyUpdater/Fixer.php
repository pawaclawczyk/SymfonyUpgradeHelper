<?php

namespace SymfonyUpdater;

interface Fixer
{
    const CERTAINLY = 0;

    const PROBABLY = 1;

    public function fix(\SplFileInfo $file, $content);

    public function getName();
}
