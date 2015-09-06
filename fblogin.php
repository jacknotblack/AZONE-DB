<?php
require_once __DIR__ . '/vendor/autoload.php';
# login.php
session_start(); 
$fb = new Facebook\Facebook([
  'app_id' => '1723502797877606',
  'app_secret' => '935c38564eb97e677c732d5b67dc83f9',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes','publish_actions']; // optional
$loginUrl = $helper->getLoginUrl('http://1.34.137.108/AZONE-DB/fbcallback.php',$permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
?>