<?php


namespace ACTCMS\Rpclient\HttpClient;


use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

class PayResponse
{
    /**
     * @var bool
     */
    protected $error;

    /**
     * @var string|array
     */
    protected $body;

    /**
     * @var int
     */
    protected $status_code;


    public function __construct($response)
    {
        if ($response instanceof \Exception) {
            $this->error = true;
            $this->status_code = $response->getCode();
            $this->body = $response->getMessage();
        } else {

            try {
                $this->error = !$response->isOk();
                $this->status_code = $response->status();
                $this->body = $response->json();
            } catch (\Exception $exception) {
                $this->error = true;
                $this->status_code = $response->status();
                $this->body = $response->json();
            }
        }
    }

    public function __toString()
    {
        return json_encode($this->output());
    }

    public function isError(): bool
    {
        return $this->error;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function output()
    {
        return [
            'error' => $this->error,
            'status_code' => $this->status_code,
            'body' =>  $this->body,
        ];
    }
}
