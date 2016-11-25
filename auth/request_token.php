<?php
use Dotenv\Dotenv;

include "./../vendor/autoload.php";

session_start();

(new Dotenv('../'))->load();

$oauth = new \PhpQuickbooks\Auth\QuickbooksAuth(
    getenv('QUICKBOOKS_CONSUMER_KEY'),
    getenv('QUICKBOOKS_CONSUMER_SECRET')
);

$oauth->getRequestToken();
