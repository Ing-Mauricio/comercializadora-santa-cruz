<?php
/**
 * Front Controller - Sistema Comercializadora
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// ==== Configuración base ====
define('APP_PATH', __DIR__ . '/app');
define('BASE_URL', (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

// ==== Autoload de núcleo y configuración ====
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/App.php';

// ==== Iniciar router ====
$app = new App();
