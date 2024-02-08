<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */

session_start();

require "config/autoload.php";

$tokenCSRF = new CSRFTokenManager();
$token = $tokenCSRF->generateCSRFToken();
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = $token;
}
$router = new Router();

$router->handleRequest($_GET);

print_r($_SESSION);