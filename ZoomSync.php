<?php
// php -a -d auto_prep}_file=./lib/WebToken.php
// $res = $client->request('GET', 'https://api.zoom.us/v2/report/daily', ['query' => ['access_token' => $zoom->token]]);

include_once('lib/WebToken.php');
require_once __DIR__ . '/vendor/autoload.php';
define('ZOOM_API_URL', getenv('https://api.zoom.us/v2/'));

//echo 'Token: ' . $token;

class ZoomSync {
  //private $token;
  public $token;
  public $client;

  public function __construct() {
    $this->token = getToken();
    $this->client = new GuzzleHttp\Client();
  }

  function call($point, $params) {
    //base_uri = URI.join(ZOOM_API_URL, }point).to_s
    //params = params.merge({access_token: @zoom_web_token})
    //response = RestClient.get(base_uri, {params: params})
    //JSON.parse(response)
  }

  function meeting_report_for() {
    //call(point: 'metrics/meetings', params: {from: from.to_s, to: to.to_s })
  }

  function meeting_instance($meeting_id) {
    //call(point: "metrics/meetings/#{meeting_id}")
  }

  function dashboard_participants_for_meeting($meeting_id) {
    //call(point: "metrics/meetings/#{meeting_id}/participants")
  }

  function daily_report($date) {
    //call(point: 'report/daily/', params: {year: date.year, month: date.month})
  }

  function users_report($from_date, $to_date) {
    //call(point: 'report/users/', params: {from: from.to_s, to: to.to_s})
  }

  function meeting_participants_report($meeting_id) {
    //call(point: "report/meetings/#{meeting_id}/participants")
  }
}
?>
