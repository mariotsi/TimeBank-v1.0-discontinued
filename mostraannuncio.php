<?php include_once "controlloaccessi.php" ?>
<?php include_once "logout.php"; ?>
<?php global $server;

$result = $server->getAnnuncio(array('id_annuncio' => $_GET['id']));
$result = $result->return;
$annuncio = json_decode($result, true);
if ($annuncio['codiceErrore'] == -1)
    $errore = "Errore SQL";
else if ($annuncio['codiceErrore'] == -2)
    $errore = "Annuncio non trovato";


?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Visualizza Annuncio - TimeBank</title>
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
    <div id="visualizzaAnnuncio">
        <span class="errori" id="errore"
              style="text-align: center; display:  <?php if ($annuncio['richiesto'] || isset($errore)) {
                  echo "inline-block";
              } else {
                  echo "none";
              } ?>; margin:auto; width: 100%;"><?php if ($annuncio['richiesto']) echo "Attenzione: annuncio già richiesto."; else if (isset($errore)) echo $errore; ?></span>
        <span class="etichetta">Offerente:</span><span
            class="dati"><?php if (!isset($errore)) echo $annuncio['creatore']; ?></span>
        <span class="etichetta">Data inserimento:</span><span
            class="dati"><?php if (!isset($errore)) echo $annuncio['data_inserimento']; ?></span>
        <span class="etichetta">Data disponibilità:</span><span
            class="dati"><?php if (!isset($errore)) echo $annuncio['data_annuncio']; ?></span>
        <span class="etichetta">Categoria:</span><span
            class="dati"><?php if (!isset($errore)) echo $annuncio['nome_cat']; ?></span>
        <span class="etichetta">Descrizione:</span><span class="dati"
                                                         id="descrizione"><?php if (!isset($errore)) echo $annuncio['descrizione']; ?></span>
        <?php if ($annuncio['richiesto']) {
            echo "<span class=\"etichetta\">Richiedente:</span><span class=\"dati\" id=\"richiedente\">" . $annuncio['richiedente'] . "</span>";
        }
        ?>


        <input type="button" id="indietroAnnuncio" value="Indietro" onclick="indietro()"/>
        <input type="button" id="richiediAnnuncio" <?php if (!isset($errore)) {
            if ($annuncio['richiesto']) echo "style=\"background-color: #BA3A13\"";
        } ?> value="<?php if (!isset($errore)) {
            if ($annuncio['richiesto']) {
                echo "Già Richiesto";
            } else {
                echo "Richiedi";
            }
        } else {
            echo "Errore";
        } ?>" onclick="richiediAnnuncio()" <?php if (!isset($errore)) {
            if ($annuncio['richiesto']) echo "disabled";
        } ?>/>

    </div>
</div>
</body>
</html>