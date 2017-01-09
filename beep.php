<?php

$idtime_secret = 'CHANGEME'; // [./0-9A-Za-z] Mehr als 21 Zeichen führen dazu, dass das Benutzer-Passwort nicht mehr in die ID-Generierung einfliesst.

// -----------------------------------------------------------------------------
// Benutzerdaten in Array ( ID => Pfad ) lesen:
$_stempelid = array();
$fp = @fopen('./Data/users.txt', 'r');
@fgets($fp); // erste Zeile überspringen
while (($logindata = fgetcsv($fp, 0, ';')) != false) {
    if(isset($_GET['rfid'])) {
        $tempid=trim(@$logindata[3]);
        if($tempid==@$_GET['rfid']){
            $user = $logindata[0];
        }
    }
}
fclose($fp);
// -----------------------------------------------------------------------------
// übergebene ID Benutzer zuordnen und Stempelzeit eintragen:
if(isset($_GET['rfid'])){
    if(isset($user)){
        $_timestamp = time();
        $_zeilenvorschub= "\r\n";
        $_file = './Data/' . $user . '/Timetable/' . date('Y') . '.' . date('n');
        $fp = fopen($_file, 'a+b') or die("FEHLER - Konnte Stempeldatei nicht oeffnen!");
        fputs($fp, time().$_zeilenvorschub);
        fclose($fp);

        echo json_encode(array(true, "Stempelzeit fuer $user eingetragen."));
    } else {
        echo json_encode(array(false, 'Fehler, unbekannte ID!'));
    }
}else{
    echo json_encode(array(false, 'Fehler, keine ID uebermittelt!'));
}
