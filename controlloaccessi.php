<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // ultima operazione effettuata più di 30 minuti fa
    session_unset(); // unset la variabile $_SESSION
    session_destroy(); // Distrugge i dati della sessione
}

if (isset($_POST["username"]) || isset($_SESSION["username"])) {
    $username = isset($_POST["username"]) ? $_POST['username'] : $_SESSION['username'];
    $password = isset($_POST['password']) ? $_POST['password'] : $_SESSION['password'];
}
if (isset($_POST['id']) && $_POST['id'] == "") //Se non sto su mostrannuncio.php il campo id è inutile
    unset($_POST['id']);

if (!isset($username)) {

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>LogIn - TimeBank</title>
        <link href="TimeBank.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="jquery-1.9.1.js"></script>
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
            <form id="login" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" onsubmit="return loginUtente()">
                <label for="username">Username:</label>
                <input id="username" name="username" type="text"/>
                <span class="errori">
                <?php // $_GET['errori'] è settato solo se ho già provato a fare il login e ci sono stati problemi
                if (isset ($_GET['errore'])) {
                    switch ($_GET['errore']) {
                        case -2:
                            echo "Utente non trovato!";
                            break;
                        case -1:
                            echo "Password Errata";
                            break;
                        case -3:
                            echo "Errore Generico";
                            break;
                    }
                }
                ?>
                </span>
                <label for="password">Password:</label>
                <input id="password" name="password" type="password"/>
                <span class="errori"></span>
                <input type="hidden" name="id"
                       value="<?php //campo per inviare in successive richieste POST l'id dell'annuncio richiesto su mostraannuncio
                       if (isset($_GET['id']))
                           echo $_GET['id'];
                       else if (isset($_POST['id']))
                           echo $_POST['id']
                       ?>"/>
                <input id="invialogin" type="submit" name="Invia"/>
                <span id="nonregistrato">Se non sei registrato fallo <a href="registrazione.php">qui</a></span>

            </form>

        </div>
    </div>
    </body>
    </html>
    <?php
    exit;


} else { //se ho fatto submit sul form la pagine si è riaricata e ora $username è impostato, provo a fare il login
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;

    try {
        $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
    } catch (Exception $e) {
        echo "<h2>Exception Error!</h2>";
        echo $e->getMessage();
    }
    $result = $server->loginUtente(array('username' => $username, 'password' => $password));
    /*
   $port = '';
   if (isset($_SERVER['SERVER_PORT'])) {
       $port = ":" . $_SERVER['SERVER_PORT'];
   } */
    switch ($result->return) {
        case -3:
            //Se il login non va a buon fine elimino tutti i riferimenti alla sessione e ricarica la pagina con un codice di errore nell'URL
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
            if (!isset($_POST['id'])) {
                header("location:?errore=-3");
            } else {
                header("location:?errore=-3&id=" . $_POST['id']);
            }
            exit;
            break;
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
            if (!isset($_POST['id'])) {
                header("location:?errore=-2");
            } else {
                header("location:?errore=-2&id=" . $_POST['id']);
            }
            exit;
            break;
        case -1:
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
            if (!isset($_POST['id'])) {
                header("location:?errore=-1");
            } else {
                header("location:?errore=-1&id=" . $_POST['id']);
            }
            exit;
            break;


    }
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    if (isset($_POST['id'])) { //Se il login è andato a buon fine e sono su mostrannuncio.php creo una variabile globale e ci metto l'id dell'annuncio da caricare
        $id_annuncio = $_POST['id'];
        global $id_annuncio;
    }

} ?>
