<?php declare(strict_types=1);
require_once 'autoloader.php';
use Api\AutoParts;
use Api\Auto;

try {
    $requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $api = null;
    $apiSet = ['auto', 'parts'];
    $api = array_shift($requestUri);
    $apiName = array_shift($requestUri);
    $getParams = array_shift($requestUri);
    if ($api !== '') {
        if ($api !== 'api' || !in_array($apiName, $apiSet)) {
            throw new RuntimeException('API Not Found', 404);
        }
        switch ($apiName) {
            case 'auto':
                $api = new Auto($getParams);
                break;
            case 'parts':
                $api = new AutoParts($getParams);
                break;
        }
        echo $api->run();
    }
} catch (Exception $e) {
    header("HTTP/1.1 {$e->getCode()} Not Found");
    echo json_encode(['error' => $e->getMessage()]);
}
