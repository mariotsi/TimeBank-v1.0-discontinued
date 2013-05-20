<?php
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_destroy();
    header("location: " . $_SERVER['HTTP_HOST']);

}
if (isset($username)) {
    $port = '';

    if (isset($_SERVER['SERVER_PORT'])) {
        $port = ":" . $_SERVER['SERVER_PORT'];
    }
    // echo "<div class=\"logout\">Sei loggato come " . $_SESSION['username'] . ". <a href=\"http://" . $_SERVER["SERVER_NAME"].$port . $_SERVER['PHP_SELF'] . "?logout=true\">(Logout)</a></div>";
    echo "<div class=\"logout\">Sei loggato come " . $username . ". <a href=\"?logout=true\">(Logout)</a></div>";

}
?>