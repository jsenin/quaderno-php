<?php
abstract class QuadernoBase {

  const DEBUG_URL = 'http://localhost:3000/';
  const PRODUCTION_URL = "https://www.quaderno.com/";
  protected static $API_KEY = null;
  protected static $ACCOUNT_ID = null;
  protected static $URL = null;

  static function init($key, $account_id, $debug=false) {
    self::$API_KEY = $key;
    self::$ACCOUNT_ID = $account_id;
    self::$URL = $debug ? self::DEBUG_URL : self::PRODUCTION_URL;
  }

  static function responseIsValid($response) {
    return isset($response) &&
            !$response['error'] &&
            intval($response['http_code']/100) == 2;
  }

  static function findByID($model, $id) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model . "/" . $id . ".json";
    return QuadernoJSON::exec($url, "GET", self::$API_KEY, "foo", null);
  }

  static function find($model) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model . ".json";
    return QuadernoJSON::exec($url, "GET", self::$API_KEY, "foo", null);
  }

  static function saveNested($parentmodel, $parentid, $model, $data) {
    return self::save($parentmodel . "/" . $parentid . "/" . $model, $data);
  }

  static function save($model, $data, $id) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model;

    if ($id) {
      $url .= "/" . $id . ".json";      
      $return = QuadernoJSON::exec($url, "PUT", self::$API_KEY, "foo", $data);
    } else {
      $url .= ".json";
      $return = QuadernoJSON::exec($url, "POST", self::$API_KEY, "foo", $data);
    }

    return $return;
  }

  static function deleteNested($parentmodel, $parentid, $model, $id) {
    return self::delete($parentmodel . "/" . $parentid . "/" . $model, $id);
  }

  static function delete($model, $id) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model . "/" . $id . ".json";

    return QuadernoJSON::exec($url, "DELETE", self::$API_KEY, "foo", null);    
  }

  static function deliver($model, $id) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model . "/" . $id . "/deliver.json";

    return QuadernoJSON::exec($url, "GET", self::$API_KEY, "foo", null);
  }

}
?>