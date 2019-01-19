<?php

namespace Gql;

trait GraphqlCall
{
    protected $gqlEndpoint = '/graphql';

    public function query($name, $opts, array $headers = []) {
        return $this->do_query('query', $name, $opts, $headers);
    }

    public function mutate($name, $opts, array $headers = []) {
        return $this->do_query('mutation', $name, $opts, $headers);
    }

    public function do_query($type, $name, $opts, array $headers = [])
    {
        $endpoint = $this->gqlEndpoint;

        if(isset($opts['params'])){
            $qs = '?';
            foreach($opts['params'] as $k=>$v){
                if($qs!='?'){
                    $qs.='&';
                }
                $qs.= $k.'='.$v;
            }
            $endpoint .= $qs;
        }

        $builder = new Builder();
        $query = $builder->build_query($type, $name, $opts);
        $query = str_replace("\n", "", $query);
        $query = str_replace("\\", "", $query);

        $query = ['query'=> $query];

        $response = $this->post($endpoint, $query);

        return $response;
    }
}
