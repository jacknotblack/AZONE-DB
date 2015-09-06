<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start(); 

$fb = new Facebook\Facebook([
  'app_id' => '1723502797877606',
  'app_secret' => '935c38564eb97e677c732d5b67dc83f9',
  'default_graph_version' => 'v2.2',
  ]);
//var_dump($fb);

$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($accessToken)) {
  // Logged in!
  $_SESSION['facebook_access_token'] = (string) $accessToken;

  // Now you can redirect to another page and use the
  // access token from $_SESSION['facebook_access_token']
}
echo "good";
?>
