<?php

$DBConnection = new mysqli("192.168.178.63", "politics", "politics");

if(!mysqli_select_db($DBConnection, "politics")) {
    die("Error Select DB");
}
if(!$SQLStatement = $DBConnection->prepare("INSERT INTO parteiprogramm (id, partyid, text) VALUES (NULL, ?, ?);")) {
    die("Error Prepare: " . $DBConnection->error);
}
$SQLStatement->bind_param('is', $PartyID, $PartyText);

$PartyIDs = array(1, 2, 3, 4, 5, 6, 7);
$PartyTextFiles = array("Z:\Uni\Seminar_Gutenbrunner\Grundsatzprogramme\CDU.txt",
    "Z:\Uni\Seminar_Gutenbrunner\Grundsatzprogramme\SPD.txt",
    "Z:\Uni\Seminar_Gutenbrunner\Grundsatzprogramme\Gruene.txt",
    "Z:\Uni\Seminar_Gutenbrunner\Grundsatzprogramme\CSU.txt",
    "Z:\Uni\Seminar_Gutenbrunner\Grundsatzprogramme\Linke.txt",
    "Z:\Uni\Seminar_Gutenbrunner\Grundsatzprogramme\FDP.txt",
    "Z:\Uni\Seminar_Gutenbrunner\Grundsatzprogramme\AfD.txt");

$arr1 = array("ä", "ö", "ü", "ß");
$arr2 = array("ae", "oe", "ue", "ss");

for($i=0; $i<7; $i++) {
    $Text = utf8_decode(file_get_contents($PartyTextFiles[$i]));
    $Text = preg_replace('/\s+/', ' ', preg_replace("/[^a-z]/", " ", str_replace($arr1, $arr2, strtolower($Text))));
    $PartyText = utf8_encode($Text);
    $PartyID = $PartyIDs[$i];
    
    $SQLStatement->execute();
}

$SQLStatement->close();
$DBConnection->close();

