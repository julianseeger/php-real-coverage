[![Build Status](https://travis-ci.org/julianseeger/php-real-coverage.png?branch=master)](https://travis-ci.org/julianseeger/php-real-coverage)
[![Code Coverage](https://scrutinizer-ci.com/g/julianseeger/php-real-coverage/badges/coverage.png?s=1e024112911df161826d6270626cf409f00f8455)](https://scrutinizer-ci.com/g/julianseeger/php-real-coverage/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/julianseeger/php-real-coverage/badges/quality-score.png?s=c0d591e596fc48b728b46654969d00cdcee9b3d8)](https://scrutinizer-ci.com/g/julianseeger/php-real-coverage/)
[![License](https://poser.pugx.org/julianseeger/php-real-coverage/license.png)](https://packagist.org/packages/julianseeger/php-real-coverage)
[![Latest Stable Version](https://poser.pugx.org/julianseeger/php-real-coverage/v/stable.png)](https://packagist.org/packages/julianseeger/php-real-coverage)

What is "real" coverage?
========================

Given you have a simple class
```php
class SomeClass {
    public function someFunction()
    {
        $instance = "important message";
        $a = "This code";
        $b = "is completely";
        $c = "usesless";
        $instance .= "!!!";
        if (true) {
            $f = "and has";
            $g = "100% coverage";
        }
        $c .= "!";
        return $instance;
    }
}
```

And a pretty useless test for this class, that leaves most of the behavior untested

```php
class SomeClassTest extends \PHPUnit_Framework_TestCase
{
    public function testThisTestIsStupid()
    {
        $sut = new SomeClass();
        $instance = $sut->someFunction();

        $this->assertEquals("important message!!!", $instance);
    }
}
```

But nevertheless, the test produces 100% coverage for this class

![](https://raw.github.com/julianseeger/php-real-coverage/master/readme-resources/unreal-coverage.png)

When you run **php-real-coverage** on this project

Then you will know, what lines are actually tested

![](https://raw.github.com/julianseeger/php-real-coverage/master/readme-resources/real-coverage.png)

In this example, only line 8, 12 and 18 are neccessary to make the test pass.

QuickStart
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

Roadmap to Version 0.1-Beta
===========================
* rewrite the prototype of RealCoverageRun (the "main" method)
* pass appropriate arguments to phpunit

Roadmap to Version 1.0
======================
* integrate symfony/console
* run only those tests that cover the modified lines (huge speedup)
* add hooks into main algorithm to allow listeners/printers/etc
* support all default phpunit coverage writers (html, text, clover, php)
* review and restructure the architecture

Limitations
===========
* only works with phpunit
> looking forward to extend it for other frameworks, given there is an audience for it

* only works with namespaced classes
> just use namespaces then... easy to fix, but seriously: use namespaces ;)

* no support for phpunit 4
> as soon as phpunit 4 is stable, I will branch the support for 3.x and modify the trunk for phpunit 4

* maybe you will run into problems when you abuse reflections or dynamic loading in your project, so php-real-coverage probably won't work for doctrine, etc. But it is basically meant for straight-forward test-driven projects

* no support for hhvm
> I'm currently lacking any experience with hhvm, but as it seems to support phpunit I'm looking forward to change this.

Contribute
==========

Execute the tests
```
composer install --prefer-dist --dev
./vendor/bin/phpunit
```

Make your changes tested and in PSR-2

Execute the the tests again

And make your Pull Request

~~PS: The build will fail if the testcoverage falls below 100%~~