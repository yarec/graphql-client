<?php

namespace Gql;

use PHPUnit\Framework\Assert as PHPUnit;

class TestClient extends Client
{

    public function exec(string $name, $opts, string $type='query', string $endpoint=null){
        $this->response = $this->call_request($name, $opts, $type, $endpoint);
        return $this;
    }

    public function assertSuccessful()
    {
        $json = $this->json();

        PHPUnit::assertTrue(
            $this->isSuccessful(),
            'Response code ['.$json['code'].'] is not a successful code.'
        );

        return $this;
    }

    public function isSuccessful(){
        $json = $this->json();
        return 0 ==  $json['code'];
    }

    public function assertJson(array $data, $strict = false)
    {
        PHPUnit::assertArraySubset(
            $data, $this->json(), $strict, $this->assertJsonMessage($data)
        );

        return $this;
    }

    protected function assertJsonMessage(array $data)
    {
        $expected = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $actual = json_encode($this->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return 'Unable to find JSON: '.PHP_EOL.PHP_EOL.
                                      "[{$expected}]".PHP_EOL.PHP_EOL.
                                      'within response JSON:'.PHP_EOL.PHP_EOL.
                                      "[{$actual}].".PHP_EOL.PHP_EOL;
    }

}
