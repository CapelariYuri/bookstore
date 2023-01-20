<?php

namespace App\Helpers\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseBuilder extends JsonResponse
{
    protected bool $sucess;
    protected mixed $responseContent;
    protected int $httpStatusCode;
    protected ?string $erroMessage;
    protected ?string $returnMessage;

    public function __construct()
    {
        $this->erroMessage = null;
        $this->returnMessage = null;
        $this->responseContent = null;
        $this->setsucess();
        $this->setHttpStatusCode(200);
    }

    public function setSucess(): self
    {
        $this->sucess = true;
        return $this;
    }

    public function setFail(): self
    {
        $this->sucess = false;
        return $this;
    }

    public function setResponseContent(mixed $responseContent): self
    {
        $this->responseContent = $responseContent;
        return $this;
    }

    public function setHttpStatusCode(int $httpStatusCode): self
    {
        $this->httpStatusCode = $httpStatusCode;
        return $this;
    }

    public function setReturnMessage(string $returnMessage): self
    {
        $this->returnMessage = $returnMessage;
        return $this;
    }

    public function setErroMessage(string $erroMessage): self
    {
        $this->erroMessage = $erroMessage;
        return $this;
    }

    public function getResponse(): JsonResponse
    {
        $response = [
            'sucess' => $this->sucess,
            'mensagem' => $this->returnMessage,
            'dados' => $this->responseContent,
            'erro' => $this->erroMessage,
        ];

        return new JsonResponse($response, $this->httpStatusCode);
    }
}
