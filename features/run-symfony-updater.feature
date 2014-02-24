Feature: Developer runs symfony updater
  As a Developer
  I want to run the symfony updater
  In order to update my application

  Scenario: Running symfony updater
    When I run symfony-updater
    Then I should see "Symfony Updater"

  Scenario: Running update command
    When I run symfony-updater "update" command
    Then I should see "Update"
