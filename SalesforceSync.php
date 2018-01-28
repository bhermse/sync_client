<?php
include_once('lib/OauthToken.php');
require_once __DIR__ . '/vendor/autoload.php';
define('SANDBOX_HOST ', 'test.salesforce.com');
define('SALESFORCE_SERVICE_URL', '/services/data/v32.0/');

// for dates: $date->format('Y-m-d')
class SalesforceSync {
  public $token;
  public $client;
  public $instanceUrl;
  public $queryUrl;
  public $contactUrl;

  public function __construct() {
    $salesforceToken = getSalesforceToken();
    $this->token = $salesforceToken->access_token;
    $this->client = new GuzzleHttp\Client();
    $instanceUrl = $salesforceToken->instance_url;
    $this->instanceUrl = $instanceUrl;
    $this->queryUrl = $instanceUrl . SALESFORCE_SERVICE_URL . 'query?q=';
    $this->contactUrl = $instanceUrl . SALESFORCE_SERVICE_URL . 'sobjects/Contact/';
  }

  function query($query) {
    return $this->call($this->queryUrl . urlencode($query));
  }

  function call($url) {
    $headers = ['Authorization' => "OAuth $this->token", 'Content-type' => 'application/json'];
    $resp = $this->client->request('GET', $url, [
      'headers' => $headers,
    ]);
    return json_decode($resp->getBody()->getContents());
  }

  function allContacts() {
    return $this->query("SELECT Id, FirstName, LastName, Birthdate, Email, Intro_Call_RSVP_Date__c FROM Contact");
  }

  function showAccount($id) {
    return $this->call($this->contactUrl . $id);
  }
}
?>
