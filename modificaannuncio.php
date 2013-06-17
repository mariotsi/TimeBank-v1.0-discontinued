<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php include_once "controlloaccessi.php";
include_once "logout.php";
include_once "menu.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Modifica Annuncio - Time Bank</title>
    <link href="TimeBank.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="jquery-1.9.1.js"></script>
    <script type="text/javascript" src="TimeBank.js"></script>

</head>
<body>

<div id="container">
    <div id="top">
        <div id="top_container">
            <?php include_once "logout.php" ?>
            <div class="clessidra"></div>
            <h3>Time Bank</h3>

            <div class="clessidra"></div>
            <br/>
            <h4>Modifica Annuncio</h4>
        </div>
    </div>
</div>
<div id="corpo">
    <?php
    if (!$isAdmin) {
        echo "<div class=\"erroriCercaAnnunci\">Pagina riservata ai soli Amministratori!</div>";
        exit;
    }
    if (!isset($_GET['id_annuncio'])) {
        echo "<div class=\"erroriCercaAnnunci\">Non è stato fornito nessun annuncio da modificare</div>";
        exit;
    }
    $result = $server->getAnnuncio(array('id_annuncio' => $_GET['id_annuncio']));
    $result = json_decode($result->return, true);
    global $result;
    if ($result['codiceErrore'] == -2) {
        echo "<div class=\"erroriCercaAnnunci\">Non è stato trovato nessun annuncio da modificare</div>";
        exit;
    }
    ?>
    <form id="nuovoAnnuncio">
        <span class="etichetta">Creatore:</span><span
            class="dati" onclick="alert('Non si può modificare il creatore');"><?= $result['creatore'] ?> (Non modificabile)</span>
        <label for="testoAnnuncio">Testo dell'annuncio:</label>
        <textarea id="testoAnnuncio" type="text" maxlength="1000"
                  style="width: 322px"><?= $result["descrizione"] ?></textarea>

        <label for="categoria">Categoria:</label>
        <select id="categoria">
            <option></option>
            <?php
            try {
                $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
                $result2 = $server->getCategorie();
                $categorie = json_decode($result2->return, true);
                foreach ($categorie as $temp) {
                    if ($temp["id_categoria"] == $result["id_categoria"])
                        echo "<option value=\"" . $temp["id_categoria"] . "\" selected>" . $temp["nome_cat"] . "</option>";
                    else
                        echo "<option value=\"" . $temp["id_categoria"] . "\">" . $temp["nome_cat"] . "</option>";
                }
            } catch (Exception $e) {
                echo "<h2>Exception Error! " . $e->getMessage() . "</h2>";
            }
            ?>
        </select>
        <label for="calendario">Quando:</label>
        <input style="width: 322px" type="datetime-local" id="calendario" min="<?php
        $date = getdate();
        if ($date['mon'] < 10) {
            $date['mon'] = "0" . $date['mon'];
        }
        if ($date['mday'] < 10) {
            $date['mday'] = "0" . $date['mday'];
        }
        if ($date['hours'] < 10) {
            $date['hours'] = "0" . $date['hours'];
        }
        if ($date['minutes'] < 10) {
            $date['minutes'] = "0" . $date['minutes'];
        }
        if ($date['seconds'] < 10) {
            $date['seconds'] = "0" . $date['seconds'];
        }
        /*echo $date['year']."-".$date['mon']."-".$date['mday']."T".$date['hours'].":".$date['minutes'].":".$date['seconds'];
    */
        print_r(date('Y-m-d', strtotime("+1 hours")) . "T" . date('H', strtotime("+1 hours")) . ":00"); ?>"
               max="<?php print_r(date('Y-m-d', strtotime("+2 months")) . "T" . date('H', strtotime("+1 hours")) . ":00"); ?>"
               step="900"
               value="<?= str_replace(" ", "T", $result["data_annuncio"]) ?>"
            <?php
            $dateForm1 = (date('Y-m-d', strtotime("+1 hours")) . " " . date('H', strtotime("+1 hours")) . ":00");
            $dateForm2 = (date('Y-m-d', strtotime("+1 hours")) . "T" . date('H', strtotime("+1 hours")) . ":00");
            if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('(MSIE|Firefox)', $_SERVER['HTTP_USER_AGENT'])) {
                echo ' onchange="checkData(\'' . $dateForm1 . '\')" value="' . $dateForm1 . '"';
            } else {
                echo 'value="' . $dateForm2 . '"';
            }
            ?>/>
        <label for="richiedente">Richiedente:</label>
        <select id="richiedente" style="color:#dedede">
            <option value="Non Richiesto" style="color: red">Non Richiesto</option>
            <?php
            try {
                $result3 = $server->getUtenti();
                $utenti = json_decode($result3->return, true);
                foreach ($utenti as $temp) {
                    if ($result['creatore'] != $temp) {
                        if ($result['richiedente'] == $temp)
                            echo "<option value=\"" . $temp . "\" selected>" . $temp . "</option>";
                        else
                            echo "<option value=\"" . $temp . "\">" . $temp . "</option>";
                    }
                }
            } catch (Exception $e) {
                echo "<h2>Exception Error! " . $e->getMessage() . "</h2>";
            }
            ?>
        </select>
        <input id="modificaAnnuncio" type="button" value="Modifica" onclick="modificaAnnuncio1()"/>
        <input id="eliminaAnnuncio" style="background-color: #BA3A13" type="button" value="Elimina"
               onclick="eliminaAnnuncio1()"/>

        <div class="clearer"></div>

        <span class="errori" id="errore"></span>
    </form>
</div>

</body>
</html>
