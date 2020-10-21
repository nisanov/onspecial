<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Application;
use PHPUnit\Framework\TestCase;

abstract class FeatureTestCase extends TestCase
{
    /**
     * The application instance.
     */
    protected static ?Application $application = null;

    /**
     * Setup before every feature test class.
     */
    public static function setUpBeforeClass(): void
    {
        self::$application = new Application;
    }

    /**
     * Tear down before every feature test class.
     */
    public static function tearDownAfterClass(): void
    {
        self::$application = null;
    }
}
