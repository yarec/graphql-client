<?php

namespace Gql;

trait GraphqlCall
{
    /*
    public function query($name, $opts, array $headers = []) {
        return $this->do_query('query', $name, $opts, $headers);
    }

    public function mutate($name, $opts, array $headers = []) {
        return $this->do_query('mutation', $name, $opts, $headers);
    }

    public function do_query($type, $name, $opts, array $headers = [])
    {
        $endpoint = '/graphql';
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

        $builder = new Builder($endpoint);

        $query = '{"query": "' . $builder->build_query($type, $name, $opts) . '"}';

        $query = str_replace("\n", "", $query);
        $query = str_replace("\\", "", $query);

        $headers = array_merge([
            'CONTENT_LENGTH' => mb_strlen($query, '8bit'),
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
            'access_token' => '111',
        ], $headers);

        $response = $this->call('POST', $endpoint, [], [], [], $this->transformHeadersToServerVars($headers), $query);

        return $response;
    }
    */
}
