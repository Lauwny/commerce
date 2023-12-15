<?php

namespace Helpers\RestResponse;

class RestResponse
{
    public $datas;
    private $response; // Change from typed property

    public function __construct($datas = null)
    {
        $this->datas = $datas;
        $this->response = null; // Initialize $response here
    }

    public function build()
    {
        if ($this->datas == null) {
            $this->response = json_encode(["response" => ""]);
        } else {
            $this->response = json_encode(["response" => $this->datas]);
        }
        return $this->response;
    }
}