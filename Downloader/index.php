<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Textinfo.php';

echo "Hello World\n";

$Result = Textinfo::getFromCSV("C:\Users\danie\Documents\politiker.csv");

foreach($Result as $Politician) {
    if(!empty($Politician->FBID)) {
        echo "Fetching Facebook Data from " . $Politician->Name . " " . $Politician->Firstname . " | ID: " . $Politician->FBID . "\n";
        try {
            $fbtext = utf8_decode(getFacebookTexts($Politician->FBID));
        }
        catch(Facebook\Exceptions\FacebookServerException $e) {
            echo 'Server returned an error: ' . $e->getMessage();
        }
        
        echo "Writing Data to File: " . $Politician->RawText . "\n";
        file_put_contents($Politician->RawText, $fbtext);
        
        echo "Writing filtered Data to File: " . $Politician->FilteredText . "\n";
        file_put_contents($Politician->FilteredText, preg_replace('/\s+/', ' ', preg_replace("/[^a-z]/", " ", strtolower($fbtext))));
    }
}

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


