<?php declare(strict_types=1);

namespace ZeroBounce;

use ZeroBounce\HttpClient\{HttpClient, HttpResponse};
use ZeroBounce\Response\{CreditsResponse, SendfileResponse, ValidateResponse};

/**
 * Class Api
 * @package ZeroBounce
 */
class Api implements ApiInterface
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $email
     * @param string $ipAddress
     *
     * @return ValidateResponse
     * @throws Exception\ClientException
     */
    public function validate(string $email, string $ipAddress = ''): ValidateResponse
    {
        /** @var HttpResponse $response */
        $response = $this->httpClient->get('validate', [
            'query' => [
                'email'      => $email,
                'ip_address' => $ipAddress,
            ]
        ]);
        
        /** @var ValidateResponse $response */
        $response = ValidateResponse::fromResponse($response);
        
        return $response;
    }

    /**
     * @return CreditsResponse
     * @throws Exception\ClientException
     */
    public function credits(): CreditsResponse
    {
        /** @var HttpResponse $response */
        $response = $this->httpClient->get('getcredits');

        /** @var CreditsResponse $response */
        $response = CreditsResponse::fromResponse($response);
        
        return $response;
    }

    /**
     * @return SendfileResponse
     * @throws Exception\ClientException
     */
    public function sendfile(string $fileName, array $params = []): SendfileResponse
    {
        $params = array_map(function($k, $p): array {
            return [
                'name' => $k,
                'contents' => $p
            ];
        }, array_keys($params), $params);

        /** @var HttpResponse $response */
        $response = $this->httpClient->postMultipart('sendfile', [
            'multipart' => array_merge([
                [
                    'name'     => 'file',
                    'contents' => fopen($fileName, 'r')
                ]
            ], $params),
        ]);

        /** @var SendfileResponse $response */
        $response = SendfileResponse::fromResponse($response);
        
        return $response;
    }

    /**
     * @return SendfileResponse
     * @throws Exception\ClientException
     */
    public function filestatus(string $fileId): SendfileResponse
    {
        /** @var HttpResponse $response */
        $response = $this->httpClient->get('filestatus', [
            'query' => [
                'file_id' => $fileId
            ]
        ]);

        /** @var SendfileResponse $response */
        $response = SendfileResponse::fromResponse($response);
        
        return $response;
    }

    /**
     * @return SendfileResponse
     * @throws Exception\ClientException
     */
    public function getfile(string $fileId): string
    {
        /** @var HttpResponse $response */
        $response = $this->httpClient->get('getfile', [
            'query' => [
                'file_id' => $fileId
            ]
        ]);

        return $response->getBody();
    }
}
