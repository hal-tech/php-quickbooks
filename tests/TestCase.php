<?php

namespace Tests;

use Dotenv\Dotenv;
use PhpQuickbooks\Quickbooks;
use PHPUnit_Framework_TestCase;

/**
 * Class TestCase
 *
 * @package Tests
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var Quickbooks
     */
    protected $quickbooks;

    /**
     * Override the setup function to load environment variables
     * and load quickbooks object with tokens and keys.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpDotEnv();
        $this->setUpQuickbooks();
    }

    /**
     * Load .env environment variables
     */
    private function setUpDotEnv()
    {
        (new Dotenv('../'))->load();
    }

    /**
     * Load Quickbooks token and keys
     */
    private function setUpQuickbooks()
    {
        $this->quickbooks = new Quickbooks(
            getenv('QUICKBOOKS_CONSUMER_KEY'),
            getenv('QUICKBOOKS_CONSUMER_SECRET'),
            getenv('QUICKBOOKS_ACCESS_TOKEN'),
            getenv('QUICKBOOKS_ACCESS_TOKEN_SECRET'),
            getenv('QUICKBOOKS_CUSTOMER_ID'),
            'https://sandbox-quickbooks.api.intuit.com'
        );
    }
}
