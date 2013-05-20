<?php
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    header("location:index.php ");
    session_destroy();
}

if (isset($username)) {
    $port = '';
    if (isset($_SERVER['SERVER_PORT'])) {
        $port = ":" . $_SERVER['SERVER_PORT'];
    }
    echo "<div class=\"logout\">Sei loggato come " . $username . ". (<a href=\"?logout=true\">Logout</a>)</div>";
}
?>
