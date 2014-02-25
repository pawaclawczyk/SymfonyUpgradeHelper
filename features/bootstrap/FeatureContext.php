<?php

use Behat\Behat\Context\TurnipAcceptingContext;
use SymfonyUpdater\Console\Application;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Behat context class.
 */
class FeatureContext implements TurnipAcceptingContext
{
    private $workDir;
    /**
     * @var ApplicationTester
     */
    private $applicationTester;

    /**
     * @BeforeScenario
     */
    public function createWorkDir()
    {
        $this->workDir = sys_get_temp_dir().'/'.uniqid('SymfonyUpdate').'/';
        mkdir($this->workDir, 0777, true);
        chdir($this->workDir);
    }

    /**
     * @AfterScenario
     */
    public function removeWorkDir()
    {
        system('rm -rf '.$this->workDir);
    }

    /**
     * @When /^I run symfony-updater( "(?P<command>[^"]*)" command for dir "(?P<dir>[^"])"){0,1}$/
     */
    public function iRunSymfonyUpdater($command = '', $dir = null)
    {
        $application = new Application();
        $application->setAutoExit(false);

        $this->applicationTester = new ApplicationTester($application);
        $this->applicationTester->run($command, $dir);
    }

    /**
     * @Then I should see :text
     */
    public function iShouldSee($text)
    {
        $match = preg_match('/'.preg_quote($text).'/sm', $this->applicationTester->getDisplay());

        if (1 !== $match) {
            throw new RuntimeException('I should see: ' . $text);
        }
    }

    /**
     * @Given /^the class file "(?P<file>[^"]+)" contains:$/
     */
    public function theClassFileContains($file, PyStringNode $string)
    {
        $dir = dirname($file);

        if (!file_exists($dir)) {
            mkdir($dir,0777, true);
        }

        file_put_contents($file, $string->getRaw());
    }
}
