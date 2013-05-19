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
            success: function (risultato) {
                alert("Bravo bambino speciale");
            }

        });
        event.preventDefault();//da togliere dopo il debug
    } else {
        event.preventDefault();
        alert("Compila tutti i campi");
    }
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
        $("#password").css({'background': 'url(spunta_verde.png)'});
        $("#password").css({'background-position': '-80px -32px'});
        $("#password2").css({'background': 'url(spunta_verde.png)'});
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
            $("#password").css({'background': 'url(spunta_verde.png)'});
            $("#password").css({'background-position': '-80px 0px'});
            $("#password2").css({'background': 'url(spunta_verde.png)'});
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