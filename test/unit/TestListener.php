<?php

declare(strict_types=1);

namespace Rector\BetterReflectionTest;

use ClassWithExplicitGlobalNamespace;
use ClassWithNoNamespace;
use PHPUnit\Framework\BaseTestListener;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestSuite;
use Rector\BetterReflectionTest\Fixture\ExampleClass;
use Rector\BetterReflectionTest\Fixture\Methods;
use Rector\BetterReflectionTest\FixtureOther\AnotherClass;

class TestListener extends BaseTestListener
{
    /**
     * @var \PHPUnit\Framework\TestSuite|null
     */
    private $currentSuite;

    /**
     * Determine the "full" test name (including the suite name if it is set)
     */
    private function getCurrentTestName(TestCase $test) : string
    {
        if ($this->currentSuite === null) {
            return $test->getName(true);
        }
        return $this->currentSuite->getName() . '::' . $test->getName(true);
    }

    /**
     * Create an additional assertion to ensure the specified class is not
     * loaded when executing a test
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    private function assertClassNotLoaded(string $className, TestCase $test) : void
    {
        TestCase::assertFalse(
            \class_exists($className, false),
            'Class ' . $className . ' was loaded during test ' . $this->getCurrentTestName($test)
        );
    }

    /**
     * Ensure the fixture classes have not actually been loaded (where applicable)
     *
     * @param float $time
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function endTest(Test $test, $time) : void
    {
        // Only test PHPUnit tests (i.e. no .phpt tests or anything else unexpected)
        if (! ($test instanceof TestCase)) {
            return;
        }

        $this->assertClassNotLoaded(ExampleClass::class, $test);
        $this->assertClassNotLoaded(AnotherClass::class, $test);
        $this->assertClassNotLoaded(ClassWithExplicitGlobalNamespace::class, $test);
        $this->assertClassNotLoaded(ClassWithNoNamespace::class, $test);
        $this->assertClassNotLoaded(Methods::class, $test);
    }

    /**
     * Simply record the "current" test suite being run
     * Used by getCurrentTestName().
     */
    public function startTestSuite(TestSuite $suite) : void
    {
        $this->currentSuite = $suite;
    }

    /**
     * Unset the "current" test suite being run at the end.
     * Used by getCurrentTestName().
     */
    public function endTestSuite(TestSuite $suite) : void
    {
        $this->currentSuite = null;
    }
}
