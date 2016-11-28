<?php

namespace PhpQuickbooks\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

class QuickbooksRequestException extends Exception
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * QuickbooksRequestException constructor.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->parseError($response);

        parent::__construct($this->message, $this->code);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function parseError(ResponseInterface $response)
    {
        $content = json_decode($response->getBody()->getContents(), true);

        $error = $content['Fault']['Error'][0];

        $this->message = $error['Message'] . " - " . $error['Detail'];
        $this->code = $error['code'];
    }
}
