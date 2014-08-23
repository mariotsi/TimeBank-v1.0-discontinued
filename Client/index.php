<?php include_once "menu.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>TimeBank</title>
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

            <div class="clessidra">
            </div>
        </div>
    </div>
    <div class="listaAnnuncio">

    </div>
    <br/>

    <form id="registrazione" sytle="margin-top: 50px" Style="border : 1px">
        <h4 style="top: 0px;">Filtra la ricerca</h4>
        <br/>

        <label for="creatore">Creatore: </label>
        <input id="creatore" onchange="caricaAnnunci(0)"/>

        <label for="categoria">Categoria:</label>
        <select id="categoria" onchange="caricaAnnunci(0)">
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

        <label for="provincia">Provincia (sigla):</label>

        <select id="provincia" onchange="sceltaProvincia(0)">
            <option></option>
            <?php
            try {
                $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl');
                //$parameters=array('value'=>35);
                $result = $server->getProvince();
                foreach ($result->return as $temp) {
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
        <select id="comune" onchange="caricaAnnunci(0)">
            <option>Seleziona prima una provincia</option>
        </select>
    </form>
</div>
<script type="text/javascript">
    caricaAnnunci(0);
</script>
</body>
</html>