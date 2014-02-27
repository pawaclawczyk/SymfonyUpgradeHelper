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
