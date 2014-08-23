<?php
include_once "SOAP.php";
global $server;
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    header("location:index.php ");
    session_destroy();
}

if (isset($username)) {
    $result = $server->isAdmin(array('username' => $username));
    $port = '';
    if (isset($_SERVER['SERVER_PORT'])) {
        $port = ":" . $_SERVER['SERVER_PORT'];
    }
    echo "<div class=\"logout\">Sei loggato come " . $username . ". (<a href=\"?logout=true\">Logout</a>)";
    $isAdmin = $result->return;
    global $isAdmin;
    if ($isAdmin)
        echo "(<a href=\"admin.php\">Pannello Amministratore</a>)";
    echo "</div>";
}
?>
