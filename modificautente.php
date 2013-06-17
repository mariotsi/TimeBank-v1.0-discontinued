<?php
include_once "controlloaccessi.php";
include_once "logout.php";
?>
<?php include_once "menu.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Modifica Utente - TimeBank</title>
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
        <?php
        if (!$isAdmin) {
            echo "<div class=\"erroriCercaAnnunci\">Pagina riservata ai soli Amministratori!</div>";
            exit;
        }
        if (!isset($_GET['utente'])) {
            echo "<div class=\"erroriCercaAnnunci\">Non è stato fornito nessun utente da modificare</div>";
            exit;
        }
        $result = $server->getUtente(array('username' => $_GET['utente']));
        $result = json_decode($result->return, true);
        global $result;
        if ($result['codiceErrore'] == -2) {
            echo "<div class=\"erroriCercaAnnunci\">Non è stato trovato nessun utente da modificare</div>";
            exit;
        }
        ?>
        <form id="registrazione">

            <label for="username">Username: </label>
            <input id="username" type="text" maxlength="50" value="<?= $result['username'] ?>"/>

            <label for="cambiaPassword">Cambia Password: </label>
            <input style="float: left" type="checkbox" id="cambiaPassword" value="cambia"
                   onclick="toggleCambiaPassword()">

            <label for="password">Password:</label>
            <input id="password" type="password" maxlength="50" disabled/>


            <label for="password2">Ridigita Password:</label>
            <input id="password2" type="password" maxlength="50" onchange="checkPassword()" disabled/>


            <label for="email">Email:</label>
            <input id="email" type="email" maxlength="50" onchange="checkEmail()" value="<?= $result['email'] ?>"/>

            <label for="indirizzo">Indirizzo:</label>
            <input id="indirizzo" type="text" maxlength="100" value="<?= $result['indirizzo'] ?>"/>

            <label for="cap">CAP:</label>
            <input id="cap" type="text" maxlength="5" value="<?= $result['cap'] ?>"/>

            <label for="provincia">Provincia (sigla):</label>

            <select id="provincia" onchange="sceltaProvincia()">
                <option></option>
                <?php
                try {
                    $server2 = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl');
                    //$parameters=array('value'=>35);
                    $result2 = $server2->getProvince();
                    foreach ($result2->return as $temp) {
                        if ($temp == $result['provincia'])
                            $temp = "<option selected>" . $temp . "</option>";
                        else
                            $temp = "<option>" . $temp . "</option>";
                        echo $temp;
                    }
                } catch (Exception $e) {
                    echo "<h2>Exception Error!</h2>";
                    echo $e->getMessage();
                }
                ?>

            </select>

            <label for="comune">Comune:</label>
            <select id="comune">  <?php
                $result2 = $server->getComuniPerProvincia(array('provincia' => $result['provincia']));
                $comuni = json_decode($result2->return, true);
                foreach ($comuni as $comune)
                    if ($result['citta'] == $comune["codice_istat"])
                        echo "<option selected value=\"" . $comune["codice_istat"] . " \">" . $comune["nome"] . "</option>";
                    else
                        echo "<option value=\"" . $comune["codice_istat"] . "\">" . $comune["nome"] . "</option>";
                ?>
            </select>

            <label for="makeAdmin">Amministratore </label>
            <input style="float: left" type="checkbox" id="makeAdmin" value="admin"
                   onclick="return toggleMakeAdmin()"<?php if ($result['admin']) echo "checked" ?>/>

            <input id="invia" type="button" value="Invia" onclick=" return modificaUtente()"/>


        </form>

    </div>

</div>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: Simone
 * Date: 14/05/13
 * Time: 14.02
 * To change this template use File | Settings | File Templates.
 */
?>

</body>
</html>