<?php

namespace SymfonyUpdater;

class UpdateLogger
{
    /**
     * @var UpdateLog[]
     */
    private $logs = [];

    /**
     * @param UpdateLog $log
     */
    public function log(UpdateLog $log)
    {
        $this->logs[] = $log;
    }

    /**
     * @return UpdateLog[]
     */
    public function getAll()
    {
        return $this->logs;
    }
}
