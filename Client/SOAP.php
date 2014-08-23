<?php
if (!isset($_SESSION)) {
    session_start();
}
try {
    $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));

} catch (Exception $e) {
    echo "<h2>Exception Error!</h2>";
    echo $e->getMessage();
}
?>