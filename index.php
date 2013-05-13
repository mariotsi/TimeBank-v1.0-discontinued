

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Prova GitHub</title>
</head>
<body>
<?php

try {
    $server= new SoapClient('http://151.40.127.217:8080/axis2/services/NewAxisFromJava?wsdl');
    // print_r($server->__getFunctions());
    print("<p>");
    $parameters=array('value'=>35);
    print_r($server->addOne($parameters));

    $result=$server->hello(array('name'=>'porcamadonna'));

    print_r($result->return);

} catch (Exception $e) {
    echo "<h2>Exception Error!</h2>";
    echo $e->getMessage();

}

echo '<p> running HelloWorld : <p>';


phpinfo();
?>
</body>
</html>
