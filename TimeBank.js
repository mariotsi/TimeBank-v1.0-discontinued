function inserisciUtente() {


    if (controllaCompilazioneCampi()) {

        $.ajax({
            type: "POST",
            url: "comunicatoreSOAP.php",
            data: ({
                ACTION: "2",
                USERNAME: $('#username').val(),
                PASSWORD: $('#password').val(),
                EMAIL: $('#email').val(),
                INDIRIZZO: $('#indirizzo').val(),
                CAP: $('#cap').val(),
                PROVINCIA: $('#provincia').val(),
                COMUNE: $('#comune').val()

            }),
            dataType: "html",
            async: false,
            success: function (risultato) {
                alert("Bravo bambino speciale"); //WTF?!?! :D
                return true;
            }

        });


    } else {
        alert("Compila tutti i campi");
        return false;

    }
}

function inserisciAnnuncio(creatore) {
    esito = true;
    if (checkCampiAnnuncio()) {
        $.ajax({
            type: "POST",
            url: "comunicatoreSOAP.php",
            data: ({
                ACTION: "4",
                DESCRIZIONE: $('#testoAnnuncio').val(),
                CREATORE: creatore,
                CATEGORIA: $('#categoria').val(),
                DATAORA: $('#calendario').val().replace('T', ' ')
            }),
            dataType: "html",
            async: false,
            success: function (risultato) {
                switch (parseInt(risultato)) {
                    case -2:
                        $('#errore').html("Errore nell'inserimento dell'annuncio");
                        esito = false;
                        break;
                    default:
                        esito = true;
                        break;
                }
            }
        });
        return esito;
    } else {
        $('#errore').html("Compila tutti i campi");
        return false;

    }
}

function insAnnuncio() {
    esito = true;
    $.ajax({
        type: "POST",
        url: "comunicatoreSOAP.php",
        data: ({
            ACTION: "5"
        }),
        dataType: "html",
        async: false,
        success: function (risultato) {
            esito = inserisciAnnuncio(risultato);
        }
    });
    return esito;
}

function getUsername() {
    username = '';
    $.ajax({
        type: "POST",
        url: "comunicatoreSOAP.php",
        data: ({
            ACTION: "5"
        }),
        dataType: "html",
        async: false,
        success: function (risultato) {
            username = risultato;
        }
    });
    //  alert ( $(".dati").eq(0).text());
    return username;


}
function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}


function sceltaProvincia() {
    value = $("#provincia").val();
    //$("#comune").focus();
    $.ajax({
        type: "POST",
        url: "comunicatoreSOAP.php",
        data: ({
            ACTION: "3",
            PROVINCIA: value
        }),
        dataType: "html",
        success: function (risultato) {
            $("#comune").html(risultato);
        }
    });
}
function checkPassword() {
    password = $("#password").val();
    password2 = $("#password2").val();
    //alert(password+" "+password2);
    if (password.localeCompare(password2) != 0) {
        $("#password").css({'background': 'url(img/spunta_verde.png)'});
        $("#password").css({'background-position': '-80px -32px'});
        $("#password2").css({'background': 'url(img/spunta_verde.png)'});
        $("#password2").css({'background-position': '-80px -32px'});
        $("#password").val("");
        $("#password").focus();
        $("#password2").val("");

        // alert("Le due password non coincidono");
    } else {
        if (password.length < 6) {
            $("#password").val("");
            $("#password").focus();
            $("#password2").val("");
            alert("Password troppo corta. Minimo 6 caratteri");

        } else {
            $("#password").css({'background': 'url(img/spunta_verde.png)'});
            $("#password").css({'background-position': '-80px 0px'});
            $("#password2").css({'background': 'url(img/spunta_verde.png)'});
            $("#password2").css({'background-position': '-80px 0px'});
        }


    }

}

$(document).ready(function () {

    $('#password').keyup(function () {

        //alert($('#password').css('background').contains('spunta_verde.png').toString());
        if ($('#password').css('background').indexOf('spunta_verde.png') >= 0) {

            $("#password").css({'background': '#35403b'});
            $("#password2").css({'background': '#35403b'});
            $("#password2").val("");
        }
    })

    $('#email').keyup(function () {

        $('#email').css({'font-weight': 'normal'});
        if ($('#password').css('background').indexOf('spunta_verde.png') >= 0) {
        }
    })
});

function checkEmail() {

    if (!isEmail($("#email").val())) {
        $("#email").css({'color': 'red'});
    } else {
        $("#email").css({'color': ''});
    }


}

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function controllaCompilazioneCampi() {

    if ($('#username').val() != '') {
        if ($('#password').val() != '') {
            if ($('#email').val() != '') {
                if ($('#indirizzo').val() != '') {
                    if ($('#cap').val() != '' && $('#cap').val().length == 5) {
                        if ($('#provincia').val() != '') {
                            if ($('#comune').val() != '' && $('#comune').val() != 'Seleziona una provincia') {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        if ($('#cap').val().length < 5) {
                            $('#cap').val("");
                        }
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function loginUtente() {

    if ($('#username').val() == "" || $('#password').val() == "") {
        return false;
    }
    return true;
}

function checkCampiAnnuncio() {
    if ($('#testoAnnuncio').val() == "" || $('#categoria').val() == "" || $('#calendario').val() == "") {
        return false;
    }
    return true;
}

function indietro() {
    window.history.back();
}

function richiediAnnuncio() {
    esito = -3;
    $.ajax({
        type: "POST",
        url: "comunicatoreSOAP.php",
        data: ({
            ACTION: "6",
            ID_ANNUNCIO: getURLParameter('id'),    //id_articolo preso dall'URL
            RICHIEDENTE: getUsername(),   //prende il nome dell'utente da $_SESSION
            CREATORE: $(".dati").eq(0).text()//seleziona la prima occorrenza della classe "dati". Aggiungo il creatore per poter fare un controllo (lato server) id_annuncio <-> creatore
        }),
        dataType: "html",
        async: false,
        success: function (risultato) {
            esito = risultato;
        }
    });
    switch (parseInt(esito)) {
        case -4:
            $('#errore').html("L'annuncio non può essere richiesto da chi l'ha creato");
            $('#errore').css({display: 'inline-block'});
            break;
        case -3:
            $('#errore').html("Impossibile richiedere l'annuncio");
            $('#errore').css({display: 'inline-block'});
            break;
        case -2:
            $('#errore').html("Annuncio già richiesto");
            $('#errore').css({display: 'inline-block'});
            break;
        case -1:
            $('#errore').html("Errore DB - Annuncio non trovato");
            $('#errore').css({display: 'inline-block'});
            break;
        case -0:
            alert("Hai correttamente richiesto l'annuncio");
            window.location.reload();
            break;

    }  //fare il controllo errori
    // sul server controllare prima di richiedere l'annuncio che sia effettivamente non richiesto, errore altrimenti


}