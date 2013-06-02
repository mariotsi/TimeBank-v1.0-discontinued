function inserisciUtente() {
    if (controllaCompilazioneCampi(false)) {
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
                alert("Utente correttamente Registrato");
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
            if (window.location.href.indexOf("registrazione.php") > -1) {
                $("#comune").html(risultato);                             //se siamo su registrazione.php
            }
            else {
                $("#comune").html("<option></option>" + risultato);    //se siamo altrove, in particolare index.php
                caricaAnnunci();
            }
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

function controllaCompilazioneCampi(skipPassword) {

    if ($('#username').val() != '') {
        if ($('#password').val() != '' || skipPassword) {
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

function checkData(dataForm) {
    if ($('#calendario').val() !== "") {
        var data = $('#calendario').val();
        var pattern = '(19|20)[0-9]{2}-(0|1)[0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]';
        var re = new RegExp(pattern);
        if (data.length == 16 && data.match(re)) {
            var date_array = data.split(/-|:|\s/);
            var year = date_array[0];
            var month = date_array[1] - 1; //i mesi partono da 0 a 11 in JS
            var day = date_array[2];
            var hour = date_array[3];
            var minute = date_array[4];
            minute = arrotondaMinuti(minute);
            var dataArr = data.substring(0, 14);
            if (minute == 0)
                dataArr += '00';
            else
                dataArr += minute;
            var source_date = new Date();

            if (year != source_date.getFullYear()) {
                var errMess = source_date.getFullYear();
                $('#errore').html("Errore: anno consentito " + errMess);
                $('#calendario').val(dataForm);
                return false;
            }

            if (month > 11 || day > 31 || hour > 23) {
                $('#errore').html("Errore: Data non valida");
                $('#calendario').val(dataForm);
                return false;
            }

            var bisestile = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0)) ? true : false;

            if (month == 1 && !bisestile && day > 28) {
                $('#errore').html("Errore: Data non valida");
                $('#calendario').val(dataForm);
                return false;
            }

            if (month == 1 && bisestile && day > 29) {
                $('#errore').html("Errore: Data non valida");
                $('#calendario').val(dataForm);
                return false;
            }

            if ((month == 3 || month == 5 || month == 8 || month == 10) && day > 30) {
                $('#errore').html("Errore: Data non valida");
                $('#calendario').val(dataForm);
                return false;
            }

            var monthLimit = source_date.getMonth() + 2;

            if (month > monthLimit || month < source_date.getMonth() || (month == monthLimit && day > source_date.getDate())) {
                monthLimit++;
                $('#errore').html("Errore: puoi inserire solo date comprese tra oggi e il " + source_date.getDate() + "/" + monthLimit + "/" + year);
                $('#calendario').val(dataForm);
                return false;
            }

        } else {
            $('#errore').html("Errore: formato data errato<br/>(formato corretto: aaaa-mm-gg hh:mm)");
            $('#calendario').val(dataForm);
            return false;
        }
    } else {
        return false;
    }

    $('#calendario').val(dataArr);
    return true;
}

function arrotondaMinuti(ora) {
    if (ora >= 0 && ora < 15)
        return 0;
    if (ora >= 15 && ora < 30)
        return 15;
    if (ora >= 30 && ora < 45)
        return 30;
    if (ora >= 45 && ora <= 59)
        return 45;
}

function caricaAnnunci(all) {


    if ($('#comune').val() == "Seleziona prima una provincia")
        comune = "";
    else
        comune = $('#comune').val();

    if ($('#categoria').val() == 0)
        categoria = -1;
    else
        categoria = $('#categoria').val();


    esito = -3;
    $.ajax({
        type: "POST",
        url: "comunicatoreSOAP.php",
        data: ({
            ACTION: "7",
            CREATORE: $('#creatore').val(),
            PROVINCIA: $('#provincia').val(),
            COMUNE: comune,
            CATEGORIA: categoria,
            ALL: all //asking for all or only requested
        }),
        dataType: "html",
        //async: false,
        success: function (risultato) {
            esito = risultato;
            $('.listaAnnuncio').html("<div id=\"headerAnnuncio\"><span class=\"descrizioneAnnuncio\">Descrizione</span><span class=\"categoriaAnnuncio\">Categoria</span><span class=\"comuneAnnuncio\">Comune</span><span class=\"provinciaAnnuncio\">Prov.</span></div>" + risultato);
        }
    });
}

function toggleCambiaPassword() {
    $('input[type=password]').prop('disabled', !$('input[type=password]').is(":disabled"));   //do alla proprietà disabled il negato del valore attuale
}

function toggleMakeAdmin() {
    if ($('#makeAdmin').is(":checked")) {
        if (confirm("Sei sicuro di voler promuovere " + $('#username').val() + " ad amministratore?")) {
            return true;
        } else {
            return false;
        }
    }
}

function modificaCategoria() {
    vecchioNome = $('#categoria option:selected').text();
    var nuovoNome = prompt("Inserisci il nuovo nome della categoria", vecchioNome);
    if (nuovoNome != null && nuovoNome != vecchioNome && nuovoNome != "") {
        $.ajax({
            type: "POST",
            url: "comunicatoreSOAP.php",
            data: ({
                ACTION: "8",
                ID_CATEGORIA: $('#categoria').val(),
                NUOVONOME: nuovoNome
            }),
            dataType: "html",
            //async: false,
            success: function (risultato) {
                window.location.reload();
            }
        });
    }
}

function eliminaCategoria() {
    if (confirm("Sei sicuro di voler eliminare la categoria \"" + $('#categoria option:selected').text() + "\"?")) {
        $.ajax({
            type: "POST",
            url: "comunicatoreSOAP.php",
            data: ({
                ACTION: "9",
                ID_CATEGORIA: $('#categoria').val()
            }),
            dataType: "html",
            //async: false,
            success: function (risultato) {
                window.location.reload();
            }
        });
    }
}

function goModificaUtente() {
    window.location = "modificautente.php?utente=" + $('#utente').val();
}

function eliminaUtente() {
    if (confirm("Sei sicuro di voler eliminare l'utente \"" + $('#utente').val() + "\"?")) {
        $.ajax({
            type: "POST",
            url: "comunicatoreSOAP.php",
            data: ({
                ACTION: "11",
                USERNAME: $('#utente').val()
            }),
            dataType: "html",
            //async: false,
            success: function (risultato) {
                window.location.reload();
            }
        });
    }
}

function modificaUtente() {
    var bo = $('#makeAdmin').is(':checked') ? 1 : 0;

    if (controllaCompilazioneCampi($('input[type=password]').is(":disabled"))) {  //se i campi password sono disabilitati salta il controllo password!
        if ($('input[type=password]').is(":disabled")) {
            password = "";
        }
        else {
            password = $('#password').val();
        }
        $.ajax({
            type: "POST",
            url: "comunicatoreSOAP.php",
            data: ({
                ACTION: "12",
                USERNAME: $('#username').val(),
                PASSWORD: password,
                EMAIL: $('#email').val(),
                INDIRIZZO: $('#indirizzo').val(),
                CAP: $('#cap').val(),
                PROVINCIA: $('#provincia').val(),
                COMUNE: $('#comune').val(),
                ADMIN: bo,
                OLDUSERNAME: getURLParameter('utente')
            }),
            dataType: "html",
            async: false,
            success: function (risultato) {
                if (risultato == -2) {
                    alert("Utente non trovato");
                    return false;
                }
                alert("Utente correttamente modificato");
                window.location = "?utente=" + $('#username').val();
                return true;
            }
        });
    } else {
        alert("Compila tutti i campi");
        return false;
    }
}

