<?php

namespace SymfonyUpdater\Util;

class Filesystem
{
    public function write($filename, $content)
    {
        file_put_contents($filename, $content);
    }

    public function read($filename)
    {
        return file_get_contents($filename);
    }
}
