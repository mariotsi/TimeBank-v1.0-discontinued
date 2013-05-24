<?php
if (!isset($_SESSION)) {
    session_start();
}
//print_r($_POST) ;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset(); // unset $_SESSION variable for the run-time
    session_destroy(); // destroy session data in storage

}

if (isset($_POST["username"]) || isset($_SESSION["username"])) {
    //echo "azz";
    $username = isset($_POST["username"]) ? $_POST['username'] : $_SESSION['username'];
    $password = isset($_POST['password']) ? $_POST['password'] : $_SESSION['password'];
}

if (!isset($username)) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>LogIn - TimeBank</title>
        <link href="TimeBank.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="TimeBank.js"></script>


    </head>
    <body>
    <div id="container">
        <div id="top">
            <div id="top_container">
                <div class="clessidra"></div>
                <h3>Time Bank</h3>

                <div class="clessidra"></div>


            </div>


        </div>
        <div id="corpo">
            <form id="login" method="POST" action="<?php echo $_SERVER['PHP_SELF'];
            if (isset($_GET['id'])) echo "?id=" . $_GET['id']; ?>" onsubmit="return loginUtente()">
                <label for="username">Username:</label>
                <input id="username" name="username" type="text"/>
            <span class="errori"><?php if (isset ($_GET['errore'])) {
                    switch ($_GET['errore']) {
                        case -2:
                            echo "Utente non trovato!";

                            break;
                        case -1:
                            echo "Password Errata";
                            break;
                    }
                } ?></span> <label for="password">Password:</label>
                <input id="password" name="password" type="password"/>
                <span class="errori"></span>

                <input id="invialogin" type="submit" name="Invia"/>
                <span id="nonregistrato">Se non sei registrato fallo <a href="registrazione.php">qui</a></span>

            </form>

        </div>
    </div>
    </body>
    </html>
    <?php
    exit;


} else {
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;

    try {
        $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));

    } catch (Exception $e) {
        echo "<h2>Exception Error!</h2>";
        echo $e->getMessage();
    }
    $result = $server->loginUtente(array('username' => $username, 'password' => $password));

    $port = '';
    if (isset($_SERVER['SERVER_PORT'])) {
        $port = ":" . $_SERVER['SERVER_PORT'];
    }
    // print_r($result->return . "127.0.0.1" . $port . $_SERVER['PHP_SELF']);
    switch ($result->return) {
        case -2:
            session_unset();
            session_destroy();
            unset ($_POST['username']);
            unset ($_POST['password']);
            unset ($username);
            unset ($password);

            $port = '';
            if (isset($_SERVER['SERVER_PORT'])) {
                $port = ":" . $_SERVER['SERVER_PORT'];
            }
            header("location:?errore=-2");
            /*
            //set POST variables
            $url = "127.0.0.1" . $port . $_SERVER['PHP_SELF'];
            $fields = array(
                'errore' => urlencode($result->return)

            );

//url-ify the data for the POST
            $fields_string = "";
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

//open connection
            $ch = curl_init();

//set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//execute post
            $result = curl_exec($ch);

//close connection
            curl_close($ch);  */
            exit;
            break;
        case -1:
            //print_r($_SESSION);
            //unset($_SESSION['username']);
            // unset($_SESSION['password']);
            session_unset();
            session_destroy();
            unset ($_POST['username']);
            unset ($_POST['password']);
            unset ($username);
            unset ($password);
            //echo "unsetted";
            //print_r($_SESSION);
            $port = '';
            if (isset($_SERVER['SERVER_PORT'])) {
                $port = ":" . $_SERVER['SERVER_PORT'];
            }
            header("location:?errore=-1");
            /*
            //set POST variables
            $url = "127.0.0.1" - $port . $_SERVER['PHP_SELF'];
            $fields = array(
                'errore' => urlencode($result->return)

            );

//url-ify the data for the POST
            $fields_string = "";
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

//open connection
            $ch = curl_init();

//set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//execute post
            $result = curl_exec($ch);

//close connection
            curl_close($ch);
            echo "connesso";*/
            exit;
            break;


    }
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


} ?>
