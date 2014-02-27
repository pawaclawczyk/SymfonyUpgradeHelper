Feature: Developer runs symfony updater
  As a Developer
  I want to run the symfony updater
  In order to update my application

  Scenario: Running symfony updater
    When I run symfony-updater
    Then I should see "Symfony Updater"

  Scenario: Running update command
    Given the class file "A.php" contains:
    """
    <?php

    class A
    {

    }
    """
    When I run symfony-updater "update" command for dir "."
    Then I should see "Update stared."
    And I should see "Update finished."
