<?php

namespace Gql;

use Psr\Http\Message\ResponseInterface;

class Client
{
    private $httpClient;
    protected $response;

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

    function schema(array $opts, $type='types'){
        return $this->query('__schema', [
            'params' => $opts['params'],
            'resp' => [
                $type => ['name']
            ]
        ]);
    }

    function type(string $name, array $opts){
        return $this->query('__type', [
            'args' => ['name' => $name],
            'params' => $opts['params'],
            'resp' => $opts['resp'],
        ]);
    }

    public function exec(string $name, $opts, string $type='query', string $endpoint=null){
        $this->response = $this->call_request($name, $opts, $type, $endpoint);
        return $this->json();
    }

    public function call_request(string $name, $opts, string $type='query', string $endpoint=null){
        $builder = new Builder();

        $query = $builder->build_query($type, $name, $opts);
        $query = str_replace("\n", "", $query);
        $query = str_replace("\\", "", $query);

        $params = isset($opts['params']) ? $opts['params'] : [];

        return $this->request($query, $params, $endpoint);
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

        return $response;
    }

    public function json(){
        return $this->toJson($this->response);
    }

    public function response(){
        return $this->response;
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


    /*
    public function assertGqlJson(array $data, $strict = false)
    {
        PHPUnit::assertArraySubset(
            $data, $this->decodeResponseJson(), $strict, $this->assertJsonMessage($data)
        );

        return $this;
    }

    protected function assertGqlJsonMessage(array $data)
    {
        $expected = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $actual = json_encode($this->decodeResponseJson(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return 'Unable to find JSON: '.PHP_EOL.PHP_EOL.
                                      "[{$expected}]".PHP_EOL.PHP_EOL.
                                      'within response JSON:'.PHP_EOL.PHP_EOL.
                                      "[{$actual}].".PHP_EOL.PHP_EOL;
    }
    */
}
