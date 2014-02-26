Feature: Developer upgrade Symfony from 2.0 to 2.1
  As a developer
  I want to fix files in my project
  In order to upgrade Symfony version to 2.1

  Scenario: Fix DoctrineBundle namespace
    Given the class file "app/AppKernel.php" contains:
      """
      <?php

      use Symfony\Component\HttpKernel\Kernel;

      class AppKernel extends Kernel
      {
          public function registerBundles()
          {
              $bundles = array(
                  new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
              );
          }
      }
      """
    When I run symfony-updater "update" command for dir "."
    Then the class file "app/AppKernel.php" should contain:
      """
      <?php

      use Symfony\Component\HttpKernel\Kernel;

      class AppKernel extends Kernel
      {
          public function registerBundles()
          {
              $bundles = array(
                  new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
              );
          }
      }
      """

    Scenario: Notify about session configuration
      Given the file "app/config/config.yml" contains:
        """
        framework:
            session:
                default_locale: fr
        """
      When I run symfony-updater "update" command for dir "."
      Then I should see "Require manual fix"
      And I should see "config.yml"

    Scenario: Fix session access in twig
      Given the file "test.html.twig" contains:
        """
        {{ app.request.session.locale }}
        {{ app.session.locale }}
        """
      When I run symfony-updater "update" command for dir "."
      Then the file "test.html.twig" should contain:
        """
        {{ app.request.locale }}
        {{ app.request.locale }}
        """

  Scenario: Notify about getLocale usage in code
    Given the class file "Test.php" contains:
      """
      <?php

      use Symfony\Component\HttpFoundation\Session\Session;

      class Test
      {
          public function test(Session $session)
          {
              $session->getLocale();
          }
      }
      """
    When I run symfony-updater "update" command for dir "."
    Then I should see "Require manual fix"
    And I should see "Test.php"