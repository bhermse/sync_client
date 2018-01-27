<?php
require_once __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;

define('ZOOM_API_KEY', getenv('ZOOM_API_KEY'));
define('ZOOM_API_SECRET', getenv('ZOOM_API_SECRET'));
define('SANDBOX_TOKEN_URL', 'https://test.salesforce.com/services/oauth2/token');

function getToken() {
  $expiration_seconds = 86500; // one day
  $key = ZOOM_API_KEY;

  $token = array(
    "iss" => ZOOM_API_KEY,
    "exp" => $expiration_seconds,
  ); 
  return JWT::encode($token, ZOOM_API_SECRET);
}

?>
