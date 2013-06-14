<?php
include_once "controlloaccessi.php";
include_once "logout.php";
include_once "menu.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Pannello Amministrazione - TimeBank</title>
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
        <?php
        if (!$isAdmin) {
            echo "<div class=\"erroriCercaAnnunci\">Pagina riservata ai soli Amministratori!</div>";
            exit;
        }  ?>
        <div id="adminSX">
            <div style="text-align: center"><h4 style="top: 0px;">Categorie</h4></div>
            <div id="innerAdminSX" style="margin-right:5px;">
                <select id="categoriaAnnuncio" style="float: none; margin: 0 auto;width: 400px;">
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
                <input type="submit" style="width: 200px;" onclick="goModificaUtente()" value="Modifica"/>
                <input type="submit" style="width: 200px;" onclick="eliminaUtente()" value="Elimina"/>


            </div>
        </div>
        <div style="text-align: center"><h4 style="top: 0px;">Annunci</h4></div>
        <div style="border: 1px dashed white;">
            <div class="listaAnnuncio">
            </div>
            <span style="display: block; width : 100%; text-align: center">Annuncio non richiesto|<span
                    style="color:#D9AA55">Annuncio richiesto</span>  </span>

            <form id="registrazione" sytle="margin-top: 50px">
                <h4 style="top: 0px;">Filtra la ricerca</h4>
                <br/>

                <label for="creatore">Creatore: </label>
                <input id="creatore" onchange="caricaAnnunci(1)"/>

                <label for="categoria">Categoria:</label>
                <select id="categoria" onchange="caricaAnnunci(1)">
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

                <select id="provincia" onchange="sceltaProvincia(1)">
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
                <select id="comune" onchange="caricaAnnunci(1)">
                    <option>Seleziona prima una provincia</option>
                </select>
            </form>

        </div>
        <script type="text/javascript">
            caricaAnnunci(1);
        </script>
</body>
</html>