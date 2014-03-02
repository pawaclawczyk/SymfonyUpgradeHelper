SymfonyUpgradeHelper
====================

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/pawaclawczyk/SymfonyUpgradeHelper/badges/quality-score.png?s=ba6cfcd01e6a04d17e14cf00b53d98c41e623cdc)](https://scrutinizer-ci.com/g/pawaclawczyk/SymfonyUpgradeHelper/)

[![Build Status](https://travis-ci.org/pawaclawczyk/SymfonyUpgradeHelper.png?branch=master)](https://travis-ci.org/pawaclawczyk/SymfonyUpgradeHelper)

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/pawaclawczyk/symfonyupgradehelper/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

The main goal of this tool is to help you upgrade your Symfony2 projects and bundles to higher Symfony2 versions.

`SymfonyUpgradeHelper` is a set of Fixers that will fix your code or at least notify you about places where you have to
introduce some changes to reach compatibility with Symfony2. Fixers are created according to hints in Symfony2 `UPGRADE-X.Y` files.

**Project is currently in early development phase, so please be careful while using it.**

**If you have an idea to make it better-shaped, please create an issue or contact me.**

### Installing dependencies

```
$> wget -nc http://getcomposer.org/composer.phar
$> php composer.phar install
```

### Running

```
$> bin/symfony-upgrade-helper update path/to/your/project
```

### Running tests

```
$> php composer.phar --dev install
$> vendor/bin/phpspec run
$> vendor/bin/behat
```
