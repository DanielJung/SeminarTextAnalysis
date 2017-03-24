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
class Textinfo {
    public $Name;
    public $Firstname;
    public $Party;
    public $Role;
    public $FBUrl;
    public $FBID;
    
    public $RawText;
    public $FilteredText;
    
    public static function getFromCSV($sFile) {
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
                $var->RawText = $data[6];
                $var->FilteredText = $data[7];
                
                $Result[] = $var;
            }
            fclose($handle);
        }
        return $Result;
    }
}
