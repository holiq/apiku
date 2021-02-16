<?php

namespace App\Traits;

trait APIResponse
{
    private $result = [
        "status" => null,
        "message" => null,
        "data" => [],
        "error" => []
    ];
    
    public function response($msg = null, $data = [], $code = 200) {
        $this->result['status'] = in_array($code, [200, 201, 202]) ? 'success' : 'error';
        $this->result['message'] = $msg;
        if(in_array($code, [200, 201, 202])){
            $this->result['data'] = $data;
            unset($this->result['error']);
        }else{
            $this->result['error'] = $data;
            unset($this->result['data']);
        }
        return \response()->json($this->result, $code);
    }
}