<?php

require_once './Textinfo.php';

$conn = new mysqli("192.168.178.63", "politics", "politics");
if(!$statement = $conn->prepare("INSERT INTO `politics`.`politicians` (`id`, `lastname`, `firstname`, `partyid`, `role`, `fblink`, `fbid`) VALUES (NULL, ?, ?, ?, ?, ?, ?)")) {
    die("Error Prepare: " . $conn->error);
}
$statement->bind_param('ssissi', $Lastname, $Firstname, $PartyID, $Role, $FBLink, $FBID);

$Result = Textinfo::getFromCSV("C:\Users\danie\Documents\politiker.csv");

foreach($Result as $Politician) {
    $Lastname = $Politician->Name;
    $Firstname = $Politician->Firstname;
    switch($Politician->Party) {
        case "CDU":         $PartyID = 1; break;
        case "SPD":         $PartyID = 2; break;
        case "B90/Grüne":   $PartyID = 3; break;
        case "CSU":         $PartyID = 4; break;
        case "Linke":       $PartyID = 5; break;
        case "FDP":         $PartyID = 5; break;
        case "AfD":         $PartyID = 6; break;
        default: {
            echo "Unknown Party: " . $Politician->Party . "\n";
            break;
        }
    }
    $Role = $Politician->Role;
    $FBLink = $Politician->FBUrl;
    $FBID = $Politician->FBID;
    $statement->execute();
}

$statement->close();
$conn->close();