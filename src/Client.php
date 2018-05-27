<?php

namespace Gql;

use Psr\Http\Message\ResponseInterface;

class Client
{
    private $httpClient;

    public function __construct(string $endpoint='/graphql', array $guzzleOptions = []) {

        $guzzleOptions = array_merge(['base_uri' => $endpoint], $guzzleOptions);

        $this->httpClient = new \GuzzleHttp\Client($guzzleOptions);
    }

    function query(string $name, $opts, string $endpoint=null){
        return $this->exec($name, $opts, 'query', $endpoint);
    }

    function mutate(string $name, $opts, string $endpoint=null){
        return $this->exec($name, $opts, 'mutation', $endpoint);
    }

    public function exec(string $name, $opts, string $type='query', string $endpoint=null){
        $builder = new Builder();

        $query = $builder->build_query($type, $name, $opts);

        $params = isset($opts['params']) ? $opts['params'] : [];

        $response = $this->request($query, $params, $endpoint);
        return $response;
    }

    public function request(string $query, array $params, string $endpoint=null)
    {
        $options = [
            'json' => [
                'query' => $query
            ],
            'query' => $params,
        ];

        try {
            $response = $this->httpClient->request('POST', '', $options);
        } catch (TransferException $e) {
            throw new \RuntimeException('Network Error.'.$e->getMessage(), 0, $e);
        }

        $json = $this->toJson($response);
        return $json;
    }

    private function toJson(ResponseInterface $httpResponse)
    {
        $body = $httpResponse->getBody();
        
        $json = $this->strToJson($body);

        return $json;
    }

    private function strToJson(string $body)
    {
        $json_decode = json_decode($body, true);

        $error = json_last_error();
        if (JSON_ERROR_NONE !== $error) {
            throw new \UnexpectedValueException('Invalid JSON response.');
        }
        return $json_decode;
    }
}