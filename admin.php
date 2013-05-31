<?php
include_once "controlloaccessi.php";
include_once "SOAP.php";
include_once "logout.php";
global $server;


?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>TimeBank</title>
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

            <div class="clessidra">
            </div>
        </div>
    </div>
    <div id="corpoAdmin">
        <div id="adminSX">
            <div style="text-align: center"><h4 style="top: 0px;">Categorie</h4></div>
            <div id="innerAdminSX" style="margin-right:5px;">
                <select id="categoria" style="float: none; margin: 0 auto;width: 400px;">
                    <?php
                    try {
                        //$server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
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
                <input type="submit" style="width: 200px;" onclick="modificaCategoria()" value="Modifica"/>
                <input type="submit" style="width: 200px;" onclick="eliminaCategoria()" value="Elimina"/>


            </div>


        </div>
        <div id="adminDX">
            <div style="text-align: center"><h4 style="top: 0px;">Utenti</h4></div>
            <div id="innerAdminDX" style="margin-left:5px;">
                <select id="utente" style="float: none; margin: 0 auto;width: 400px;">
                    <?php
                    try {
                        //$server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
                        $result = $server->getUtenti();
                        $categorie = json_decode($result->return, true);
                        foreach ($categorie as $temp) {
                            echo "<option value=\"" . $temp . "\">" . $temp . "</option>";
                        }
                    } catch (Exception $e) {
                        echo "<h2>Exception Error! " . $e->getMessage() . "</h2>";
                    }
                    ?>
                </select>
                <input type="submit" style="width: 200px;" onclick="modificaUtente()" value="Modifica"/>
                <input type="submit" style="width: 200px;" onclick="eliminaUtente()" value="Elimina"/>


            </div>
        </div>


    </div>