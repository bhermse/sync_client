<?php
include_once('lib/OauthToken.php');
require_once __DIR__ . '/vendor/autoload.php';
define('SANDBOX_HOST ', 'test.salesforce.com');
define('SALESFORCE_SERVICE_URL', '/services/data/v20.0/');

// for dates: $date->format('Y-m-d')
class SalesforceSync {
  public $token;
  public $client;
  public $instanceUrl;
  public $queryUrl;

  public function __construct() {
    $salesforceToken = getSalesforceToken();
    $this->token = $salesforceToken->access_token;
    $this->client = new GuzzleHttp\Client();
    $instanceUrl = $salesforceToken->instance_url;
    $this->instanceUrl = $instanceUrl;
    $this->queryUrl = $instanceUrl . SALESFORCE_SERVICE_URL . 'query?q=';
  }

  function query($query) {
    $headers = ['Authorization' => "OAuth $this->token", 'Content-type' => 'application/json'];
    $resp = $this->client->request('GET', $this->queryUrl . urlencode($query), [
      'headers' => $headers,
    ]);
    return json_decode($resp->getBody()->getContents());
  }

  function allContacts() {
    return $this->query("SELECT Id, FirstName, LastName, Birthdate, Email, Intro_Call_RSVP_Date__c FROM Contact");
  }

}
?>
