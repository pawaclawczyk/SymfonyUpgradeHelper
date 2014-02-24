<?php

use Behat\Behat\Context\TurnipAcceptingContext;
use SymfonyUpdater\Console\Application;

/**
 * Behat context class.
 */
class FeatureContext implements TurnipAcceptingContext
{
    /**
     * @var ApplicationTester
     */
    private $applicationTester;

    /**
     * Initializes context. Every scenario gets it's own context object.
     *
     * @param array $parameters Suite parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
    }

    /**
     * @When I run symfony-updater
     */
    public function iRunSymfonyUpdater()
    {
        $application = new Application();
        $application->setAutoExit(false);

        $this->applicationTester = new ApplicationTester($application);
        $this->applicationTester->run();
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
}
