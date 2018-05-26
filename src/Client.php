<?php

namespace Gql;

class Client
{
    private $endpoint;

    public function __construct($endpoint='/graphql') {
        $this->endpoint = $endpoint;
    }

    function query($name, $opts, $endpoint=null){
        return $this->exec($name, $opts, 'query', $endpoint);
    }

    function mutate($name, $opts, $endpoint=null){
        return $this->exec($name, $opts, 'mutation', $endpoint);
    }

    public function exec($name, $opts, $type='query', $endpoint=null){

        $endpoint = $endpoint ? $endpoint : $this->endpoint;

        $vars = isset($opts['vars']) ? $opts['vars'] : [];

        if(isset($opts['query_params'])){
            $qs = '?';
            foreach($opts['query_params'] as $k=>$v){
                if($qs!='?'){
                    $qs.='&';
                }
                $qs.= $k.'='.$v;
            }
            $endpoint .= $qs;
        }

        $client = \Softonic\GraphQL\ClientBuilder::build($endpoint);

        $builder = new Builder($endpoint);

        $query = $builder->build_query($type, $name, $opts);

        $response = $client->query($query, $vars);
        return $response->getData();
    }
}