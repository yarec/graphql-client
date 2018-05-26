<?php

namespace Gql;

class Builder
{
    /**

       $query_name,
       $root_type,
       $opts : [
        'args' => [],
        'vars' => [],
        'resp' => [],
        'paginate' => [
            'page'=>1,
            'per_page' => 10,
        ],
       ]
     */
    public function build_query($type, $name, $opts){
        $params = "";
        $args = isset($opts['args']) ? $opts['args'] : '';
        $args_str = '';
        if($args){
            foreach($args as $key=>$arg){
                if($args_str){
                    $args_str .= ',';
                }
                if(is_string($arg)){
                    $arg = "\\\"$arg\\\"";
                }
                $args_str .= $key.': ' . $arg;
            }
            $args = '('.$args_str.')';
        }

        $resp = '';
        if(isset($opts['resp'])){
            if(is_array($opts['resp'])){
                $resp = "{\n        ". join($opts['resp'], ' ') . "\n    }";
            }
        }else{
            $resp = '{id}';
        }

        $paginate = isset($opts['paginate']) ? $opts['paginate'] : [];
        $page = 1;
        if($paginate){
            $paginate_resp = "    pagination {total last_page from to per_page current_page}\n";
            $resp = "{\n $paginate_resp     data".$resp."\n}";
            if(isset($paginate['page'])){
            }
        }

        return <<<QUERY
            $type $name $params {
                $name $args
                $resp
            }
QUERY;
    }
}