<?php
?>
<ul id="menu">
    <li>
        <a href="index.php" <?php if (strpos($_SERVER['PHP_SELF'], 'index.php') !== false) echo "style=\"font-weight: bold; color: #B4BA22; font-style: italic\"" ?>>Ricerca
            Annunci</a></li>
    <li>
        <a href="annuncio.php"  <?php if (strpos($_SERVER['PHP_SELF'], 'annuncio.php') !== false && strpos($_SERVER['PHP_SELF'], 'aannuncio.php') == false) echo "style=\"font-weight: bold; color: #B4BA22; font-style: italic\"" ?>>Inserisci
            Annuncio</a></li>
    <li>
        <a href="registrazione.php"  <?php if (strpos($_SERVER['PHP_SELF'], 'registrazione.php') !== false) echo "style=\"font-weight: bold; color: #B4BA22; font-style: italic\"" ?>>Registrati</a>
    </li>
</ul>