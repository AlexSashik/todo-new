// нажатие enter = нажатие кнопрки "ГОТОВО" (#ready)
$(document).keypress(function (event) {
    if ( event.which == 13 ) {
        myAjax();
    }
});

function checkHP (hp, role) {
    if (hp == 2) {
        if (role == 'server') {
            $("#first-heart-server").toggleClass("fa-heart fa-heart-o");
        } else {
            $("#first-heart-user").toggleClass("fa-heart fa-heart-o");
        }
        return 1;
    } else if (hp == 1) {
        if (role == 'server') {
            $("#second-heart-server").toggleClass("fa-heart fa-heart-o");
        } else {
            $("#second-heart-user").toggleClass("fa-heart fa-heart-o");
        }
    } else if (hp == 0) {
        if (role == 'server') {
            $("#third-heart-server").toggleClass("fa-heart fa-heart-o");
        } else {
            $("#third-heart-user").toggleClass("fa-heart fa-heart-o");
        }
    }
}

var city_array = [''],
    letter = false,
    hp_sever = 3,
    hp_user = 3;

function myAjax (absence) {

    var check = true;
    if (absence === undefined) {
        if ($('#city').val() !== undefined) {
            if ($('#city').val().trim() === '') {
                alert('Вы не ввели название города!');
                $('#city').focus();
                check = false;
            } else if ($.inArray($('#city').val().trim(), city_array) !== -1) {
                alert($('#city').val() + ' уже был назван');
                $('#city').focus();
                check = false;
            } else if (letter !== false && $('#city').val()[0].toUpperCase() !== letter.toUpperCase()) {
                alert ('Вы называете город не с той буквы!');
                $('#city').val(letter.toUpperCase());
                $('#city').focus();
                check = false;
            } else if ($('#city').val().search(/[a-z]/gi) != -1) {
                alert ('Город вводится на русском языке');
                $('#city').focus();
                check = false;
            }
        } else {
            check = false;
        }
    } else {
        check = 2;
        hp_user--;
        checkHP(hp_user, 'user');
    }

    if (check) {
        var city = (check === true) ? $('#city').val() : 'false';

        $.ajax ({
            url   : '/games/ajax?ajax',
            type  : "POST",
            cache : false,
            data  : {
                'city' : city,
                'named_cities' : city_array
            },
            dataType : 'json',
            timeout : 15000,
            beforeSend : function () {
                $("#ready").prop( "disabled", true );
                $("#absence").prop( "disabled", true );
                $("#ready").addClass( "disable" );
                $("#absence").addClass( "disable" );
                $('#spiner').css('display', 'block');
            },
            success : function(response) {
                if (response.gameover !== undefined) {
                    window.location.href = "/games/gameover";
                } else {
                    if (response.name !== undefined) {
                        if (response.absence === undefined) {
                            city_array[city_array.length] = $('#city').val().trim();
                            $('#user_cities').append('<p>' + $('#city').val() + '</p>');
                            $('#user_cities').scrollTop('5000000');
                        }
                        city_array[city_array.length] = response.name.trim();
                        letter = response.letter;
                        $('#server_cities').append('<p>' + response.name + '</p>');
                        $('#server_cities').scrollTop('5000000');
                        $('#city').val(response.letter.toUpperCase());
                        $('#city').focus();
                    } else if (response.status !== undefined) {
                        alert (response.cause);
                        if (response.status == 'win') {
                            if (response.absence === undefined) {
                                city_array[city_array.length] = $('#city').val().trim();
                                $('#user_cities').append('<p>' + $('#city').val() + '</p>');
                                $('#user_cities').scrollTop('5000000');
                            }
                            letter = response.letter;
                            $('#city').val(response.letter.toUpperCase());
                            hp_sever--;
                            checkHP(hp_sever, 'server');
                        } else {
                            hp_user--;
                            checkHP(hp_user, 'user');
                        }
                        $('#city').focus();
                    }
                }
                $("#ready").prop( "disabled", false );
                $("#absence").prop( "disabled", false );
                $("#ready").removeClass( "disable" );
                $("#absence").removeClass( "disable" );
                $('#spiner').css('display', 'none');
            },
            error   : function (x, t) {
                if (t === "timeout") {
                    alert ('Ожидание ответа с сервера слишком велико');
                } else {
                    alert ('При отправке запроса возникли какие-то проблемы');
                }
                $("#ready").prop( "disabled", false );
                $("#absence").prop( "disabled", false );
                $("#ready").removeClass( "disable" );
                $("#absence").removeClass( "disable" );
                $('#spiner').css('display', 'none');
            }
        });
    }
    return false;
}