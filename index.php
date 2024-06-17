<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */

session_start();

require "config/autoload.php";
if(!isset($_SESSION['csrf_token'])) {
    $tokenCSRF = new CSRFTokenManager();
    $token = $tokenCSRF->generateCSRFToken();
    $_SESSION['csrf_token'] = $token;
}

$router = new Router();

$router->handleRequest($_GET);