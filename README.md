[![Build Status](https://travis-ci.org/julianseeger/php-real-coverage.png?branch=master)](https://travis-ci.org/julianseeger/php-real-coverage)
[![Code Coverage](https://scrutinizer-ci.com/g/julianseeger/php-real-coverage/badges/coverage.png?s=1e024112911df161826d6270626cf409f00f8455)](https://scrutinizer-ci.com/g/julianseeger/php-real-coverage/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/julianseeger/php-real-coverage/badges/quality-score.png?s=c0d591e596fc48b728b46654969d00cdcee9b3d8)](https://scrutinizer-ci.com/g/julianseeger/php-real-coverage/)

WIP (the appropriate runner is still missing)

php-real-coverage
=================

Calculates the real coverage from an existing PHPUnit/CodeCoverage report

Given you have a Class with 100% coverage

![](https://raw.github.com/julianseeger/php-real-coverage/master/readme-resources/unreal-coverage.png)

But the appropriate test doesn't really test very much of it's behavior

![](https://raw.github.com/julianseeger/php-real-coverage/master/readme-resources/test.png)

When you run php-real-coverage on this project

Then you will know, what lines are actually tested

![](https://raw.github.com/julianseeger/php-real-coverage/master/readme-resources/real-coverage.png)

In this example, only line 8, 12 and 18 are neccessary to make the test pass.

quickstart
==========

Add it to your composer.json
```
"require-dev": {
    "julianseeger/php-real-coverage": "*"
}
```
Generate a coverage-report with phpunit
```
./vendor/bin/phpunit --coverage-php coverage.php
```
And let php-real-coverage test the quality of your coverage
```
./vendor/bin/php-real-coverage coverage.php
```

contribute
==========

Execute the tests
```
composer install --prefer-dist --dev
./vendor/bin/phpunit
```

Make your changes tested and in PSR-2

Execute the the tests again

And make your Pull Request

PS: The build will fail if the testcoverage falls below 100%