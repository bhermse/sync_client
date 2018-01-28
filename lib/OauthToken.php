<?php
require_once __DIR__ . '/../vendor/autoload.php';

define('SALESFORCE_PWD', getenv('SALESFORCE_PWD'));
define('SALESFORCE_USER', getenv('SALESFORCE_USER'));
define('SALESFORCE_CLIENT_ID', getenv('SALESFORCE_CLIENT_ID'));
define('SALESFORCE_SECRET', getenv('SALESFORCE_SECRET'));

define('SANDBOX_TOKEN_URL', 'https://test.salesforce.com/services/oauth2/token');
define('PRODUCTION_TOKEN_URL', 'https://login.salesforce.com/services/oauth2/token');

function getSalesforceToken() {
  $client = new GuzzleHttp\Client();
  // SANDBOX for testing
  $resp = $client->request('POST', SANDBOX_TOKEN_URL, [
    'form_params' => [
      'grant_type' => 'password', 'client_id' => SALESFORCE_CLIENT_ID, 'client_secret' => SALESFORCE_SECRET,
      'username' => SALESFORCE_USER, 'password' => SALESFORCE_PWD
    ]
  ]);
  return json_decode($resp->getBody()->getContents());
}
?>
