<?php
try {
    $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));

} catch (Exception $e) {
    echo "<h2>Exception Error!</h2>";
    echo $e->getMessage();
}


switch ($_POST['ACTION']) {
    
    case 1:
        $result = $server->loginUtente(array('username' => $_POST['USERNAME'], 'password' => $_POST['PASSWORD']));
        print_r($result);
        break;
    
    case 2:
        $result = $server->inserisciUtente(array('username' => $_POST['USERNAME'], 'password' => $_POST['PASSWORD'], 'email' => $_POST['EMAIL'], 'indirizzo' => $_POST['INDIRIZZO'], 'cap' => $_POST['CAP'], 'citta' => $_POST['COMUNE'], 'provincia' => $_POST['PROVINCIA']));
        print_r($result);
        break;
    
    case 3: //Cerca i comuni facendo parte di una provincia
        //$parameters=array('value'=>35);
        $result = $server->getComuniPerProvincia(array('provincia' => $_POST['PROVINCIA']));
        $comuni = json_decode($result->return, true);
        foreach ($comuni as $comune)
            echo "<option value=\"" . $comune["codice_istat"] . "\">" . $comune["nome"] . "</option>";
        break;
        
        case 4: //creaziono nuovo annuncio
        $result = $server->inserisciAnnuncio(array('descrizione' => $_POST['DESCRIZIONE'], 'creatore' => $_POST['CREATORE'], 'categoria' => $_POST['CATEGORIA']));
        print_r($result);
        break;


}





?>