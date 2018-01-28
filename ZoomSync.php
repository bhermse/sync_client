<?php
// php -a -d auto_prepend_file=./ZoomSync.php
// $res = $client->request('GET', 'https://api.zoom.us/v2/report/daily', ['query' => ['access_token' => $zoom->token]]

include_once('lib/WebToken.php');
require_once __DIR__ . '/vendor/autoload.php';
define('ZOOM_API_URL', 'https://api.zoom.us/v2/');

// for dates: $date->format('Y-m-d')
class ZoomSync {
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

  function meetingReportFor($from = null, $to = null) {
    $from = $from ?? $this->twoMonthsAgo();
    $to = $to ?? $this->oneMonthAgo();
    return $this->call('metrics/meetings', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]);
  }

  function meetingInstance($meeting_id) {
    return $this->call('metrics/meetings/' . $meeting_id);
  }

  function dashboardParticipantsForMeeting($meeting_id) {
    return $this->call('metrics/meetings/' . $meeting_id . '/participants');
  }

  function dailyReport($date = null) {
    $date = $date ?? $this->oneMonthAgo();
    return $this->call('report/daily/', ['year' => $date->format('Y'), 'month' => $date->format('m')]);
  }

  function usersReport($from_date, $to_date) {
    $from = $from ?? $this->twoMonthsAgo();
    $to = $to ?? $this->oneMonthAgo();
    return $this->call('report/users/', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]);
  }

  function meetingParticipantsReport($meeting_id) {
    return $this->call('report/meetings/' . $meeting_id . '/participants');
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
