<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Prova GitHub</title>
</head>
<body>
<?php
foreach ($_ENV as $key => $value)
    echo $key . " " . $value;
print_r($_POST);
try {
    $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('trace' => true));

    print("<p>");
    $result = $server->getComuniPerProvincia(array('provincia' => 'TR'));

    $comuni = json_decode($result->return, true);
    //var_dump($comuni);
    echo $comuni[1]["codice_istat"];
    /* foreach ($result->return as $temp)  {
         $temp = "<p>".$temp."</p>";
         echo $temp;
     }    */
    foreach ($comuni as $comune)
        echo "<otpion value=\"" . $comune["codice_istat"] . "\">" . $comune["nome"] . "</option>";
} catch (Exception $e) {
    echo "<h2>Exception Error!</h2>";
    echo $e->getMessage();

}


phpinfo();
?>
</body>
</html>
