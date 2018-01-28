<?php
// php -a -d auto_prep}_file=./lib/WebToken.php
// $res = $client->request('GET', 'https://api.zoom.us/v2/report/daily', ['query' => ['access_token' => $zoom->token]]
//

include_once('lib/WebToken.php');
require_once __DIR__ . '/vendor/autoload.php';
define('ZOOM_API_URL', 'https://api.zoom.us/v2/');

//echo 'Token: ' . $token;
// for dates: $date->format('Y-m-d')
class ZoomSync {
  //private $token;
  public $token;
  public $client;

  public function __construct() {
    $this->token = getToken();
    $this->client = new GuzzleHttp\Client();
  }


  function call($end_point, $end_point_params = []) {
    $base_uri = ZOOM_API_URL . $end_point;
    $params = array_merge($end_point_params, ['access_token' => $this->token]);
    $resp = $this->client->request('GET', $base_uri, ['query' => $params]);
    return json_decode($resp->getBody()->getContents());
  }

  function meeting_report_for($from = null, $to = null) {
    $from = $from ?? $this->twoMonthsAgo();
    $to = $to ?? $this->oneMonthAgo();
    return $this->call('metrics/meetings', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]);
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

  function oneMonthAgo() {
    $today = new DateTime();
    return $today->sub(new DateInterval('P1M'));
  }

  function twoMonthsAgo() {
    $today = new DateTime();
    return $today->sub(new DateInterval('P2M'));
  }
}
?>
