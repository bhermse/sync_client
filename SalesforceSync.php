<?php
include_once('lib/OauthToken.php');
require_once __DIR__ . '/vendor/autoload.php';
define('SANDBOX_HOST ', 'test.salesforce.com');
define('SALESFORCE_SERVICE_URL', '/services/data/v38.0/');

// for dates: $date->format('Y-m-d')
class SalesforceSync {
  public $token;
  public $client;
  public $instanceUrl;
  public $queryUrl;
  public $contactUrl;
  public $paramUrl;
  public $commonHeaders;

  public function __construct() {
    $salesforceToken = getSalesforceToken();
    $this->token = $salesforceToken->access_token;
    $this->client = new GuzzleHttp\Client();
    $instanceUrl = $salesforceToken->instance_url;
    $this->instanceUrl = $instanceUrl;
    $this->queryUrl = $instanceUrl . SALESFORCE_SERVICE_URL . 'query?q=';
    $this->contactUrl = $instanceUrl . SALESFORCE_SERVICE_URL . 'sobjects/Contact/';
    $this->paramUrl = $instanceUrl . SALESFORCE_SERVICE_URL . 'parameterizedSearch/';
    $this->commonHeaders = ['Authorization' => "OAuth $this->token", 'Content-type' => 'application/json'];
  }

  function query($query) {
    return $this->call($this->queryUrl . urlencode($query));
  }

  function paramSearch($params) {
    $allParams = array_merge(['q' => 'Contact', 'sobject' => 'Contact'], $params);
    print_r($allParams);
    return $this->call($this->paramUrl, $allParams);
  }

  /*
  function fetchContact($id) {
    return $this->paramSearch([
      'fields' => "Id, Name, Birthdate, Email, Intro_Call_RSVP_Date__c"
    ]);
  }
  */

  function call($url, $queryParams = []) {
    $args = ['headers' => $this->commonHeaders];
    if (!empty($queryParams)) { $args['query'] = $queryParams; }
    $resp = $this->client->request('GET', $url, $args);
    return json_decode($resp->getBody()->getContents());
  }

  function update($url, $params) {
    $args = ['headers' => $this->commonHeaders];
    if (!empty($params)) { $args['body'] = json_encode($params); }
    $resp = $this->client->request('PATCH', $url, $args);
    return json_decode($resp->getBody()->getContents());
  }

  function updateContact($id, $params) {
    return $this->update($this->contactUrl . $id, $params);
  }

  function allContacts() {
    return $this->query("SELECT Id, FirstName, LastName, Birthdate, Email, Intro_Call_RSVP_Date__c FROM Contact");
  }

  function showContact($id) {
    return $this->query("SELECT Id, FirstName, LastName, Birthdate, Email, Intro_Call_RSVP_Date__c FROM Contact WHERE Id = '$id'");
  }
}
?>
