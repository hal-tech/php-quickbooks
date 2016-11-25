<?php
use Dotenv\Dotenv;

include "./../vendor/autoload.php";

session_start();

(new Dotenv('../'))->load();

$oauth = new \PhpQuickbooks\Auth\QuickbooksAuth(
    getenv('QUICKBOOKS_CONSUMER_KEY'),
    getenv('QUICKBOOKS_CONSUMER_SECRET')
);

$oauth_token = $_GET['oauth_token'];
$oauth_verifier = $_GET['oauth_verifier'];

if(!$oauth_token || !$oauth_verifier) {
    die('error: missing info.');
}

$oauth->getTokenCredentials($oauth_token, $oauth_verifier);
