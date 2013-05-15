<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Registrazione - TimeBank</title>
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
    <form id="registrazione"  onsubmit="inserisciUtente()">

        <label for="username">Username:   </label>
            <input id="username" type="text" maxlength="50" />


        <label for="password">Password:</label>
            <input id="password" type="password" maxlength="50" />


        <label for="password2">Ridigita Password:</label>
            <input id="password2" type="password" maxlength="50" onchange="checkPassword()"/>


        <label for="email">Email:</label>
            <input id="email" type="email" maxlength="50" />

        <label for="indirizzo">Indirizzo:</label>
            <input id="indirizzo" type="text" maxlength="100" />

        <label for="cap">CAP:</label>
            <input id="cap" type="text" maxlength="5" />

        <label for="provincia">Provincia (sigla):</label>

        <select id="provincia" onchange="sceltaProvincia()">
            <?php
            try {
                $server= new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl');
                //$parameters=array('value'=>35);
                $result = $server->getProvince();
                foreach ($result->return as $temp)  {
                    $temp = "<option>".$temp."</option>";
                    echo $temp;
                }
            } catch (Exception $e) {
                echo "<h2>Exception Error!</h2>";
                echo $e->getMessage();
            }
            ?>
        </select>

        <label for="comune">Comune:</label>
            <select id="comune">
                <option>Seleziona una provincia</option>
            </select>
        <input id="invia" type="submit" name="Invia"/>










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