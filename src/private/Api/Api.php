<?php

namespace Api;

abstract class Api
{
    protected $method = '';

    public $GetParams = [];
    public $bodyPost = [];

    protected $action = '';


    public function __construct($getParams)
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->getParams = $getParams;

        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method === 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            $this->bodyPost = \json_decode(\file_get_contents('php://input'), true);

            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } elseif ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new \Exception("Unexpected Header");
            }
        }
    }

    public function run()
    {
        $this->action = $this->getAction();
        if (method_exists($this, $this->action)) {
            return $this->{$this->action}();
        } else {
            throw new \RuntimeException('Invalid Method', 405);
        }
    }

    protected function response($data, int $status = 500, bool $isError = false)
    {
        header("HTTP/1.1 {$status} {$this->requestStatus($status)}");
        if ($isError) {
            $data = ['error' => $data];
        }
        return json_encode($data);
    }

    private function requestStatus($code)
    {
        $status = [
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ];
        return ($status[$code])?$status[$code]:$status[500];
    }

    protected function getAction()
    {
        $method = $this->method;
        switch ($method) {
            case 'GET':
                if ($this->getParams) {
                    return 'viewAction';
                } else {
                    return 'indexAction';
                }
                break;
            case 'POST':
                return 'createAction';
                break;
            case 'PUT':
                return 'updateAction';
                break;
            case 'DELETE':
                return 'deleteAction';
                break;
            default:
                return null;
        }
    }

    abstract protected function indexAction();
    abstract protected function viewAction();
    abstract protected function createAction();
    abstract protected function updateAction();
    abstract protected function deleteAction();
}
