<?php

namespace Gql;

function _build_space($count){
    $space = '';
    foreach (range(1, $count) as $item) {
        $space .= '    ';
    }
    return $space;
}

function _build_resp($resps, $layer, $max_depth) {
    if(is_string($resps)){
        return $resps;
    }

    if($layer > $max_depth) return 'exit';

    $data = '';
    foreach ($resps as $k => $resp) {
        #$data .= ' ';
        if(is_array($resp)){
            $data .=  "$k" . _build_resp($resp, $layer++, $max_depth);
        }else{
            $data .= _build_space($layer+1) . $resp;
        }
    }

    return "{\n" . _build_space($layer+1) . $data . "\n". _build_space($layer-2) . "}";
}

class Builder
{
    /**
     *  $root_type,
     *  $query_name,
     *  $opts : [
     *   'args' => [],
     *   'resp' => [],
     *  ]
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

        if(isset($opts['resp'])){
            $resp = $this->build_resp($opts['resp'], isset($opts['paginate']) ? $opts['paginate'] : null);
        }else{
            $resp = '';
        }

        return <<<QUERY
    $type $name $params {
        $name $args
        $resp
    }
QUERY;
    }

    public function build_resp($_resp, $paginate=null){
        $resp = _build_resp($_resp, 1, 3);

        $page = 1;
        if($paginate){
            $paginate_resp = "    pagination {total last_page from to per_page current_page}\n";
            $resp = "{\n $paginate_resp     data".$resp."}";
            if(isset($paginate['page'])){
            }
        }

        return $resp;
    }



}
