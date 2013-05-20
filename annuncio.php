<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php /* include_once "controlloaccessi.php" */ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Inserimento nuovo annuncio</title>
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
            <br/>
            <h4>Inserisci Nuovo Annuncio</h4>
        </div>
    </div>
</div>
<div id="corpo">
    <form id="nuovoAnnuncio" onsubmit="return inserisciAnnuncio()">

        <label for="testoAnnuncio">testo dell'annuncio:</label>
        <textarea id="testoAnnuncio" type="text" maxlength="1000"></textarea>

        <label for="categoria">categoria:</label>
        <select id="categoria">
            <option></option>
            <?php
            try {
                $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
                $result = $server->getCategorie();
                $categorie = json_decode($result->return, true);
                foreach ($categorie as $temp) {
                    echo "<option value=\"" . $temp["id_categoria"] . "\">" . $temp["nome_cat"] . "</option>";
                }
            } catch (Exception $e) {
                echo "<h2>Exception Error! " . $e->getMessage() . "</h2>";
            }
            ?>
        </select>
        <input id="inviaAnnuncio" type="submit" name="InviaAnnuncio" value="Crea Annuncio"/>
    </form>
</div>
</body>
</html>

