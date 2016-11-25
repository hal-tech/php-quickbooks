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

if (!$oauth_token || !$oauth_verifier) {
    die('error: missing info.');
}

$token = $oauth->getTokenCredentials($oauth_token, $oauth_verifier);
var_dump($token);
?>
<html>
<head>
    <title>Intuit Anywhere</title>
</head>
<body>
<table>
    <tr>
        <td style="text-align: right"><strong>Access Token:</strong></td>
        <td>
            <pre><?php echo $token->getIdentifier(); ?></pre>
        </td>
    </tr>
    <tr>
        <td style="text-align: right"><strong>Access Token Secret:</strong></td>
        <td>
            <pre><?php echo $token->getSecret(); ?></pre>
        </td>
    </tr>
</table>
</body>
</html>
