<?php

$Text1 = utf8_decode(file_get_contents("text.txt"));

$arr1 = array("ä", "ö", "ü", "ß");
$arr2 = array("ae", "oe", "ue", "ss");

$Text2 = preg_replace('/\s+/', ' ', preg_replace("/[^a-z]/", " ", str_replace($arr1, $arr2, strtolower($Text1))));

$TermArray = explode(" ", $Text2);

$conn = new mysqli("192.168.178.63", "politics", "politics");
if ($conn->connect_errno) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}
if(!mysqli_select_db($conn, "politics")) {
    die("Error Select DB");
}
if(!$statement = $conn->prepare("SELECT * FROM `baseterm` WHERE `term` LIKE ?")) {
    die("Error Prepare: " . $conn->error);
}
$statement->bind_param('s', $SQLTerm);
$NewText = array();

foreach($TermArray as $Term) {
    $SQLTerm = utf8_encode($Term);
    $statement->execute();
    
    $Result = $statement->get_result();
    if($Result->num_rows==0) {
        //echo "No Entry for: " . $Term . "\n";
        $NewText[] = $Term;
    } else if($Result->num_rows==1) {
        echo "Entry for: " . $Term . ": ";
        $Row = $Result->fetch_object();
        $Base = $Row->base;
        $NewText[] = utf8_decode($Base);
    } else {
        echo "Unknown number of rows: " . $Term . "\n";
    }
}

$Text3 = implode(' ', $NewText);

echo $Text1 . "\n\n\n";
echo $Text2 . "\n\n\n";
echo $Text3 . "\n\n\n";