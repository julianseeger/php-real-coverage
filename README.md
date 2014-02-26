[![Build Status](https://travis-ci.org/julianseeger/php-real-coverage.png?branch=master)](https://travis-ci.org/julianseeger/php-real-coverage)

WIP!!!

php-real-coverage
=================

Calculates the real coverage from an existing PHPUnit/CodeCoverage report

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
And let PHPRealCoverage test the quality of your coverage
```
./vendor/bin/realcoverage coverage.php
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