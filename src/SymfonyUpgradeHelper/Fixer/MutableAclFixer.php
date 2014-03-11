<?php

namespace SymfonyUpgradeHelper\Fixer;

use SebastianBergmann\Diff\Differ;
use SymfonyUpgradeHelper\Fixer;
use SymfonyUpgradeHelper\UpdateInfo;
use SymfonyUpgradeHelper\UpdateInfoCollector;

class MutableAclFixer implements Fixer
{
    const PATTERN = '/public[\s]+function[\s]+setParentAcl\(AclInterface[\s]+(\$[\w]+)\)/';

    /**
     * @var UpdateInfoCollector
     */
    private $collector;

    private $differ;

    public function __construct()
    {
        $this->differ = new Differ();
    }

    /**
     * {@inheritdoc}
     */
    public function setCollector(UpdateInfoCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * {@inheritdoc}
     */
    public function support(\SplFileInfo $file)
    {
        if ('php' === $file->getExtension()) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $fixedContent = $content;
        if ($this->hasSetParentMethodDefinition($fixedContent)) {
            $fixedContent = $this->fixSetParentMethodDefinition($fixedContent);

            $diff = $this->differ->diff($content, $fixedContent);
            $info = new UpdateInfo($this, $file, UpdateInfo::LEVEL_FIXED, $diff);
            $this->collector->add($info);
        }

        return $fixedContent;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    }

    private function hasSetParentMethodDefinition($content)
    {
        return (bool) preg_match(self::PATTERN, $content);
    }

    public function fixSetParentMethodDefinition($content)
    {
        return preg_replace(self::PATTERN, 'public function setParentAcl(AclInterface \1 = null)', $content);
    }
}
