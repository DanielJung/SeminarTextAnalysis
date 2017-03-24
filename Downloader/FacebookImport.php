<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Politician.php';

$DBConnection = new mysqli("192.168.178.63", "politics", "politics");

if(!mysqli_select_db($DBConnection, "politics")) {
    die("Error Select DB");
}
$Result = Politician::getAllFromDatabase($DBConnection);

if(!$InsertStatement = $DBConnection->prepare("INSERT INTO politician_text (id, timestamp, politicianid, source, text) VALUES (NULL, CURRENT_TIMESTAMP, ?, ?, ?)")) {
    die("Error Creating Statement");
}
$InsertStatement->bind_param("iss", $SQL_ID, $SQL_SOURCE, $SQL_TEXT);

foreach($Result as $Politician) {
    if(!empty($Politician->FBID)) {
        echo "Fetching Facebook Data from " . $Politician->Name . " " . $Politician->Firstname . " | ID: " . $Politician->FBID . "\n";
        try {
            $fbtext = utf8_decode(getFacebookTexts($Politician->FBID));
        }
        catch(Facebook\Exceptions\FacebookServerException $e) {
            echo 'Server returned an error: ' . $e->getMessage() . "\n";
            continue;
        }
        catch(Exception $e) {
            echo 'Exception occurred: ' . $e->getMessage() . "\n";
            continue;
        }
        
        $arr1 = array("ä", "ö", "ü", "ß");
        $arr2 = array("ae", "oe", "ue", "ss");

        $Text = preg_replace('/\s+/', ' ', preg_replace("/[^a-z]/", " ", str_replace($arr1, $arr2, strtolower($fbtext))));
        
        $SQL_ID = $Politician->ID;
        $SQL_SOURCE = "fb";
        $SQL_TEXT = utf8_encode($Text);
        $InsertStatement->execute();
    }
}

$InsertStatement->close();
$DBConnection->close();

function getFacebookTexts($ID) {
    $fb = new Facebook\Facebook([
    'app_id' => '{1433500746877888}',
    'app_secret' => '{fa94565810fcea19bb8f255fa6b7689e}',
    'default_graph_version' => 'v2.2',
    ]);

  try {    
    $response = $fb->get('/' . $ID . '/posts', "1433500746877888|_sDgUMcjDBuXeP4z8b5P9Yzikzk");
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
  // Page 1
  $feedEdge = $response->getGraphEdge();
  $Result = "";
do {
  foreach ($feedEdge as $status) {
      //echo $status->getField("message") . ' \n';

      $post = $status->getField("message");
      if(!empty($post)) {
          $Result .= "\n" . $post;
      }
  }
      $feedEdge = $fb->next($feedEdge);

} while($feedEdge);
  
  return $Result;
}


