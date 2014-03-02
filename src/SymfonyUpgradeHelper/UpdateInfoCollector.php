<?php

namespace SymfonyUpgradeHelper;

class UpdateInfoCollector
{
    /**
     * @var UpdateInfo[]
     */
    private $logs = [];

    /**
     * @param UpdateInfo $log
     */
    public function add(UpdateInfo $log)
    {
        $this->logs[] = $log;
    }

    /**
     * @return UpdateInfo[]
     */
    public function getAll()
    {
        return $this->logs;
    }
}
