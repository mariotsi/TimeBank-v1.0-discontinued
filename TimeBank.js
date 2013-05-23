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
            success: function(risultato) {
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
            success: function(risultato) {
                switch (parseInt(risultato)) {
                    case -2:
                        $('#errore').html("Errore nell'inserimento dell'annuncio");
                        esito = false;
                        break
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
        success: function(risultato) {
            esito = inserisciAnnuncio(risultato);
        }
    });
    return esito;
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
        success: function(risultato) {
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

$(document).ready(function() {

    $('#password').keyup(function() {

//alert($('#password').css('background').contains('spunta_verde.png').toString());
        if ($('#password').css('background').indexOf('spunta_verde.png') >= 0) {

            $("#password").css({'background': '#35403b'});
            $("#password2").css({'background': '#35403b'});
            $("#password2").val("");
        }
    })

    $('#email').keyup(function() {

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
                alert('Anno consentito: '+source_date.getFullYear());
                $('#calendario').val(dataForm);
                return false;
            }

            if (month > 11 || day > 31 || hour > 23) {
                alert('Data non Valida');
                $('#calendario').val(dataForm);
                return false;
            }

            var bisestile = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0)) ? true : false;

            if (month == 1 && !bisestile && day > 28) {
                alert('Data non Valida');
                $('#calendario').val(dataForm);
                return false;
            }

            if (month == 1 && bisestile && day > 29) {
                alert('Data non Valida');
                $('#calendario').val(dataForm);
                return false;
            }

            if ((month == 3 || month == 5 || month == 8 || month == 10) && day > 30) {
                alert('Data non Valida');
                $('#calendario').val(dataForm);
                return false;
            }

            var monthLimit = source_date.getMonth() + 2;

            if (month > monthLimit || month < source_date.getMonth() || (month == monthLimit && day > source_date.getDate())) {
                monthLimit++;
                alert('Data non valida!\npuoi inserire solo date comprese\ntra oggi e il ' + source_date.getDate() + "/" + monthLimit + "/" + year);
                $('#calendario').val(dataForm);
                return false;
            }

        } else {
            alert('formato data errato!\n (formato corretto: aaaa-mm-gg hh:mm)');
            $('#calendario').val(dataForm);
            return false;
        }
    } else {
        alert('riempi il campo data');
        $('#calendario').val(dataForm);
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
