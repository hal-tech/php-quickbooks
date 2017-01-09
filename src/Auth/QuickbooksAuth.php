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

    /**
     * @var string
     */
    protected $callback;

    public function __construct(
        string $consumer_key,
        string $consumer_secret,
        $callback = 'http://localhost:8080/auth/authorize_callback.php'
    ) {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->callback = $callback;

        $this->initServer();
    }

    protected function initServer()
    {
        $this->oauth = new Intuit([
            'identifier'   => $this->consumer_key,
            'secret'       => $this->consumer_secret,
            'callback_uri' => $this->callback
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

        return $this->oauth->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
    }
}
