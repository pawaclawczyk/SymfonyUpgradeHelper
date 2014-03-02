<?php

namespace SymfonyUpgradeHelper\Fixer;

use SymfonyUpgradeHelper\Fixer;
use SymfonyUpgradeHelper\UpdateInfo;
use SymfonyUpgradeHelper\UpdateInfoCollector;
use SebastianBergmann\Diff\Differ;

class UserEquatableInterfaceFixer implements Fixer
{
    /**
     * @var Differ
     */
    private $differ;

    /**
     * @var UpdateInfoCollector
     */
    private $collector;

    public function __construct()
    {
        $this->differ = new Differ();
    }

    /**
     * @param  \SplFileInfo $file
     * @return bool
     */
    public function support(\SplFileInfo $file)
    {
        if ('php' === $file->getExtension()) {
            return true;
        }

        return false;
    }

    /**
     * @param  \SplFileInfo $file
     * @param $content
     * @return string
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $fixedContent = $content;

        if ($this->implementsUserInterface($fixedContent)) {
            $fixedContent = $this->importEquatableInterface($fixedContent);
            $fixedContent = $this->implementsEquatableInterface($fixedContent);
            $fixedContent = $this->fixMethodDefinition($fixedContent);

            $diff = $this->differ->diff($content, $fixedContent);
            $this->collector->add(new UpdateInfo($this, $file, UpdateInfo::LEVEL_FIXED, $diff));
        }

        if (false !== $matches = $this->hasEqualsDefinition($fixedContent)) {
            foreach ($matches as $match) {
                $this->collector->add(new UpdateInfo($this, $file, UpdateInfo::LEVEL_TO_MANUAL_VERIFICATION, $match));
            }
        }

        if (false !== $matches = $this->hasEqualsCall($fixedContent)) {
            foreach ($matches as $match) {
                $this->collector->add(new UpdateInfo($this, $file, UpdateInfo::LEVEL_TO_MANUAL_VERIFICATION, $match));
            }
        }

        return $fixedContent;
    }

    /**
     * @param UpdateInfoCollector $collector
     */
    public function setCollector(UpdateInfoCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_equatable_interface_fixer';
    }

    private function implementsUserInterface($content)
    {
        return (
            preg_match("/use\s+Symfony\\\Component\\\Security\\\Core\\\User\\\UserInterface;/", $content) &&
            preg_match("/class[\s\w]+implements[\s\w,]+UserInterface/", $content)
        );
    }

    private function importEquatableInterface($content)
    {
        return preg_replace("/(use\s+Symfony\\\Component\\\Security\\\Core\\\User\\\UserInterface;)/", "$1\nuse Symfony\Component\Security\Core\User\EquatableInterface;", $content);
    }

    private function implementsEquatableInterface($content)
    {
        return preg_replace("/(class[\s\w]+implements[\s\w,]+)(UserInterface)/", "$1$2, EquatableInterface", $content);
    }

    private function fixMethodDefinition($content)
    {
        return preg_replace("/(public\s+function\s+)(equals\s*)\((UserInterface)\s+(\\$[\w]+)\s*\)/", "$1isEqualTo($3 $4)", $content);
    }

    private function hasEqualsDefinition($content)
    {
        $matches = [];

        preg_match_all('/public\s+function\s+equals\s*\(/', $content, $matches);

        if (!empty($matches)) {
            return $matches[0];
        }

        return false;
    }

    private function hasEqualsCall($content)
    {
        $matches = [];

        preg_match_all('/->equals\(/', $content, $matches);

        if (!empty($matches)) {
            return $matches[0];
        }

        return false;
    }
}
