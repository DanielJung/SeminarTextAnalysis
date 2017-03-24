<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Textinfo
 *
 * @author danie
 */
class Politician {
    public $ID;
    public $Name;
    public $Firstname;
    public $PartyID;
    public $Role;
    public $FBUrl;
    public $FBID;
    
    public static function getAllFromDatabase($Connection) {
        $Statement = $Connection->prepare("SELECT id, lastname, firstname, partyid, role, fblink, fbid FROM politicians");

        $Statement->execute();

        $QueryResult = $Statement->get_result();

        $Result = array();

        while($Row = $QueryResult->fetch_object()) {
            $p = new Politician();
            $p->ID = $Row->id;
            $p->Name = $Row->lastname;
            $p->Firstname = $Row->firstname;
            $p->PartyID = $Row->partyid;
            $p->Role = $Row->role;
            $p->FBUrl = $Row->fblink;
            $p->FBID = $Row->fbid;
            
            $Result[] = $p;
        }
        
        $Statement->close();
        
        return $Result;
    }
    
    /*public static function getFromCSV($sFile) {
        $Result = array();
        if ($handle = fopen($sFile, "r")) {
            fgetcsv($handle, 1000, ";");  // skip headline
            while ($data = fgetcsv($handle, 1000, ";")) {
                $num = count($data);
                if($num!=8) {
                    throw new Exception("Error reading csv: Wrong number of Columns");
                }
                $var = new Textinfo();
                $var->Name = $data[0];
                $var->Firstname = $data[1];
                $var->Party = $data[2];
                $var->Role = $data[3];
                $var->FBUrl = $data[4];
                $var->FBID = $data[5];
                
                $Result[] = $var;
            }
            fclose($handle);
        }
        return $Result;
    }*/
}
