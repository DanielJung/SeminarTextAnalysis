<?php

$Artikel = "ein eines einem einen eine einer einer eine ein eines einem ein der des dem den die der der die das des dem das die der den die kein "
        . "keines keinem keinen keine keiner keiner keine kein keines keinem kein keine keiner keinen keine";

$Personalpronomen = "ich du er sie es wir ihr sie mir dir ihm ihr ihm uns euch ihnen ihnen mich dich ihn sie es uns euch sie sie";
$Possesivpronomen = "mein dein sein ihr sein unser euer ihr "
        . "meine meines meiner meinem meinen "
        . "deine deines deiner deinem deinen "
        . "seine seines seiner seinem seinen "
        . "ihre ihres ihrer ihrem ihren "
        . "unsere unseres unserer unserem unseren ";

$Demonstrativpronomen = "dieser diese dieses diesem diesen";

$PronomenSonstige = "es man";

$Praepositionen = "ab abseits abz�glich am an anfangs angesichts anhand anl�sslich ans anstatt anstelle auf aufgrund aufs aufseiten aus ausgangs ausschlie�lich au�er au�erhalb "
        . "ausweislich behufs bei beidseits beiderseits beim betreffs bez�glich binnen bis bis auf contra dank diesseits durch eingangs eingedenk einschlie�lich entgegen "
        . "entlang entsprechend exklusive fern fernab f�r f�rs gegen gegen�ber gelegentlich gem�� gen gleich halber hinsichtlich hinter hinterm hinters im in infolge inklusive "
        . "inmitten innerhalb innert ins je jenseits kontra kraft lang l�ngs l�ngsseits laut links mangels minus mit mithilfe mitsamt mittels nach nebst n�rdlich nord�stlich "
        . "nordwestlich ob oberhalb ohne �stlich per plus pro rechts samt seit seitens seitlich seitw�rts statt s�dlich s�d�stlich s�dwestlich trotz �ber �berm �bern �bers um "
        . "ums unbeschadet unerachtet unfern ungeachtet unter unterhalb unterm untern unters unweit vermittels vermittelst verm�ge via vom von vonseiten vor vorbehaltlich w�hrend "
        . "wegen wider zeit zu zufolge zugunsten zulieb zuliebe zum zur zuungunsten zuwider zuz�glich zwecks zwischen";

$Text = $Artikel . " " . $Personalpronomen . " " . $Possesivpronomen . " " . $Demonstrativpronomen . " " . $PronomenSonstige . " " . $Praepositionen;

$arr1 = array("�", "�", "�", "�");
$arr2 = array("ae", "oe", "ue", "ss");

$Text = preg_replace('/\s+/', ' ', preg_replace("/[^a-z]/", " ", str_replace($arr1, $arr2, strtolower($Text))));

$TermArray = array_unique(explode(" ", $Text));

$conn = new mysqli("192.168.178.63", "politics", "politics");
if(!$statement = $conn->prepare("INSERT INTO `politics`.`wortarten` (`id`, `term`) VALUES (NULL, ?);")) {
    die("Error Prepare: " . $conn->error);
}
$statement->bind_param('s', $TermText);

foreach($TermArray as $Term) {
    $TermText = $Term;
    echo $Term . "\n";
    $statement->execute();
}

$statement->close();
$conn->close();