<?php

namespace PhpQuickbooks\Auth;

use Wheniwork\OAuth1\Client\Server\Intuit;

class QuickbooksAuth
{
    /**
     * @var \Wheniwork\OAuth1\Client\Server\Intuit
     */
    protected $oauth;

    /**
     * @var string
     */
    protected $consumer_key;

    /**
     * @var string
     */
    protected $consumer_secret;

    public function __construct(string $consumer_key, string $consumer_secret)
    {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;

        $this->initServer();
    }

    protected function initServer()
    {
        $this->oauth = new Intuit([
            'identifier'   => $this->consumer_key,
            'secret'       => $this->consumer_secret,
            'callback_uri' => 'http://localhost:8080/auth/authorize_callback.php',
        ]);
    }

    public function getRequestToken()
    {
        $temporaryCredentials = $this->oauth->getTemporaryCredentials();

        $_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
        session_write_close();

        $this->oauth->authorize($temporaryCredentials);
    }

    public function getTokenCredentials(string $oauth_token, string $oauth_verifier)
    {
        $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);

        $tokenCredentials = $this->oauth->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);

        var_dump($tokenCredentials);
    }
}
