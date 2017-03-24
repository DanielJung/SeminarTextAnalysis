<?php

$conn = new mysqli("192.168.178.63", "politics", "politics");
if(!$statement = $conn->prepare("INSERT INTO `politics`.`baseterm` (`id`, `term`, `base`) VALUES (NULL, ?, ?);")) {
    die("Error Prepare: " . $conn->error);
}
$statement->bind_param('ss', $Term, $Base);

$arr1 = array("ä", "ö", "ü", "ß");
$arr2 = array("ae", "oe", "ue", "ss");

if ($handle = fopen("dictionary.dump", "r")) {
    while ($data = fgetcsv($handle, 1000, "\t")) {
        
        $Term = utf8_decode($data[0]);
        $Base = utf8_decode($data[1]);
        $Additional = utf8_decode($data[2]);
        if(strlen($Term)<2)     continue;
        
        $Term = str_replace($arr1, $arr2, strtolower($Term));
        $Base = str_replace($arr1, $arr2, strtolower($Base));
        
        $Term = utf8_encode($Term);
        $Base = utf8_encode($Base);
        
        $statement->execute();
    }
}
else {
    die("Could not open File");
}

$statement->close();
$conn->close();