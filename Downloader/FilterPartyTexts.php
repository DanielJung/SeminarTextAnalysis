<?php

//$DBConnection = new mysqli("192.168.178.63", "politics", "politics");
$DBConnection = new mysqli("http://jung-simulation.de", "politics", "politics");

if(!mysqli_select_db($DBConnection, "politics")) {
    die("Error Select DB");
}
if(!$SQLStatement = $DBConnection->prepare("SELECT id, partyid, text FROM parteiprogramm")) {
    die("Error Prepare: " . $DBConnection->error);
}


$SQLStatement->execute();

$Result = $SQLStatement->get_result();

while($Row = $Result->fetch_object()) {
    
    echo "ID: " . $Row->id . "\n";
    echo "PartyID: " . $Row->partyid . "\n";
    $Text = utf8_decode($Row->text);
    echo "Text: " . $Text . "\n";
    
    $Filter1 = TextFilter1($DBConnection, $Text);
    
    echo "Filter1: " . $Filter1 . "\n";
    
    $Filter2 = TextFilter2($DBConnection, $Filter1);
    
    echo "Filter2: " . $Filter2 . "\n";
    
    
    break;
}

function TextFilter1($Connection, $BaseText) {
    $TermArray = explode(" ", $BaseText);
    
    if(!$Statement = $Connection->prepare("SELECT * FROM `baseterm` WHERE `term` LIKE ?")) {
        die("Error Prepare: " . $Connection->error);
    }
    $Statement->bind_param('s', $SQLTerm);
    $NewText = array();

    foreach($TermArray as $Term) {
        $SQLTerm = utf8_encode($Term);
        $Statement->execute();

        $Result = $Statement->get_result();
        if($Result->num_rows==0) {
            //echo "No Entry for: " . $Term . "\n";
            $NewText[] = $Term;
        } else if($Result->num_rows==1) {
            //echo "Entry for: " . $Term . ": ";
            $Row = $Result->fetch_object();
            $Base = $Row->base;
            $NewText[] = utf8_decode($Base);
        } else {
            echo "Unknown number of rows: " . $Term . "\n";
        }
    }
    
    return implode(' ', $NewText);
}

function TextFilter2($Connection, $BaseText) {
    $TermArray = explode(" ", $BaseText);
    
    if(!$Statement = $Connection->prepare("SELECT * FROM wortarten WHERE `term` LIKE ?")) {
        die("Error Prepare: " . $Connection->error);
    }
    
    $Statement->bind_param('s', $SQLTerm);
    $NewText = array();

    foreach($TermArray as $Term) {
        $SQLTerm = utf8_encode($Term);
        $Statement->execute();
        
        $Result = $Statement->get_result();
        if($Result->num_rows==0) {
            $NewText[] = $Term;
        }
    }
    return implode(' ', $NewText);
}