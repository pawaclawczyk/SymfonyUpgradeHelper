<?php

namespace SymfonyUpdater;

class UpdateInfo
{
    const LEVEL_FIXED = 0;

    const LEVEL_TO_MANUAL_FIX = 1;

    const LEVEL_TO_MANUAL_VERIFICATION = 2;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $fixer;

    /**
     * @var int
     */
    private $level;

    /**
     * @var string
     */
    private $message;

    /**
     * @param Fixer        $fixer
     * @param \SplFileInfo $fileInfo
     * @param $level
     * @param $message
     */
    public function __construct(Fixer $fixer, \SplFileInfo $fileInfo, $level, $message)
    {
        $this->fixer = $fixer->getName();
        $this->file = $fileInfo->getRealPath();
        $this->level = $level;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function fixer()
    {
        return $this->fixer;
    }

    /**
     * @return string
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function level()
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
