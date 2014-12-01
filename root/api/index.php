<?php
require_once(__DIR__ . '/../../include/zaphpa/zaphpa.lib.php');
require_once(__DIR__ . '/../../config/global_config.php');

$router = new Zaphpa_Router();

$router->addRoute(array(
    'path'     => '/api/email',
    'post'      => array('EmailServiceController', 'sendEmail'),
    'file'     => PROJECT_ROOT . '/handlers/email_service_controller.php',
));

try {
    $router->route();
} catch (Zaphpa_InvalidPathException $ex) {
    header("Content-Type: application/json;", TRUE, 404);
    $out = array("error" => "not found");
    die(json_encode($out));
}
