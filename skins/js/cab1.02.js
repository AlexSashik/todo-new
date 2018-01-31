// Скрытие блока оповещения
$("#info_back").on("click", function () {
    $("#info_text").animate({ opacity: 0}, 200);
    $("#info_back").css("display", "none");
    setTimeout('$("#info_text").css("display", "none");', 200);
});

$("#info_close").on("click", function () {
    $("#info_text").animate({ opacity: 0}, 200);
    $("#info_back").css("display", "none");
    setTimeout('$("#info_text").css("display", "none");', 200);
});

// Стилизация поля загрузки файлов
$("#file").on("change", function (event) {
    var filename = event.target.value.split('\\').pop();
    if (filename == '') {
        $("#file_label_span").text('Новый аватар');
    } else {
        $("#file_label_span").text(filename);
    }
});

// Функция проверка полей формы регистрации
function checkRegForm () {
    var flag = true;

    // проверка логина
    if ($('#login').val().match(/^\s*$/gi)) {
        if ($('#log_err').val() === undefined) {
            $('<div id="log_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>Вы не ввели логин</div>' ).insertAfter( "#login" );
        } else {
            $('#log_err').html('<i class="fa fa-times" aria-hidden="true"></i>Вы не ввели логин');
        }
        flag = false;
    } else if ($('#login').val().match(/^\s*.\s*$/gi) ) {
        if ($('#log_err').val() === undefined) {
            $('<div id="log_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>Cлишком короткий логин</div>' ).insertAfter( "#login" );
        } else {
            $('#log_err').html('<i class="fa fa-times" aria-hidden="true"></i>Cлишком короткий логин');
        }
        flag = false;
    } else if ($('#login').val().length > 30) {
        if ($('#log_err').val() === undefined) {
            $('<div id="log_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>Cлишком длинный логин</div>' ).insertAfter( "#login" );
        } else {
            $('#log_err').html('<i class="fa fa-times" aria-hidden="true"></i>Cлишком длинный логин');
        }
        flag = false;
    } else {
        if ($('#log_err').val() !== undefined) {
            $("#log_err").remove();
        }
    }

    // проверка email-а
    if (!$('#email').val().match(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i)) {
        if ($('#email_err').val() === undefined) {
            $('<div id="email_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>Некорректрный email</div>' ).insertAfter( "#email" );
        } else {
            $('#email_err').html('<i class="fa fa-times" aria-hidden="true"></i>Некорректрный email');
        }
        flag = false;
    } else {
        if ($('#email_err').val() !== undefined) {
            $("#email_err").remove();
        }
    }

    // проверка пароля
    if ($('#pass').val().match(/^\s*$/gi)) {
        if ($('#pass_err').val() === undefined) {
            $('<div id="pass_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>Вы не ввели пароль</div>' ).insertAfter( "#pass" );
        } else {
            $('#pass_err').html('<i class="fa fa-times" aria-hidden="true"></i>Вы не ввели пароль');
        }
        flag = false;
    } else if ($('#pass').val().match(/^\s*.(.)?\s*$/gi) ) {
        if ($('#pass_err').val() === undefined) {
            $('<div id="pass_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>Cлишком короткий пароль</div>' ).insertAfter( "#pass" );
        } else {
            $('#pass_err').html('<i class="fa fa-times" aria-hidden="true"></i>Cлишком короткий пароль');
        }
        flag = false;
    } else if ($('#pass').val().length > 50) {
        if ($('#pass_err').val() == undefined) {
            $('<div id="pass_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>Cлишком длинный пароль</div>' ).insertAfter( "#pass" );
        } else {
            $('#pass_err').html('<i class="fa fa-times" aria-hidden="true"></i>Cлишком длинный пароль');
        }
        flag = false;
    } else {
        if ($('#pass_err').val() !== undefined) {
            $("#pass_err").remove();
        }
    }

    // проверка возраста
    if (!$('#age').val().match(/^\s*$/gi)) {
        if (!$('#age').val().match(/^([1-9]\d?|[1]\d\d)$/gi)) {
            if ($('#age_err').val() == undefined) {
                $('<div id="age_err" class="info_err"><i class="fa fa-times" aria-hidden="true"></i>Некорректрный возраст</div>' ).insertAfter( "#age" );
            } else {
                $('#age_err').html('<i class="fa fa-times" aria-hidden="true"></i>Некорректрный возраст');
            }
            flag = false;
        } else {
            if ($('#age_err').val() !== undefined) {
                $("#age_err").remove();
            }
        }
    }

    return flag;
}


function delApi() {
    if (confirm ('Вы точно хотите открепить ранее прикреплённый социальный аккаунт в FaceBook?')) {
        $.ajax({
            url: '/cab/delapi?ajax',
            type: "POST",
            cache: false,
            data: {
                'action': 'delapi'
            },
            timeout: 15000,
            success: function(resp) {
               if (resp) {
                   $('.right_side>a').remove();
                   $('.right_side>div').remove();
                   $(".right_side").append(
                       '<div>Отсутствует</div>' +
                       '<a href="https://www.facebook.com/v2.11/dialog/oauth?client_id=1645997168798553&redirect_uri=http://todo.kh.ua/cab&response_type=code" class="add-api">Добавить</a>'
                   );
               } else {
                   alert ('Ой, что-то пошло не так и соцсеть не откреплена. Свяжитесь с нашей техподдержкой для решения вопроса.');
               }
            },
            error: function (x, t) {
                send = false;
                if (t === "timeout") {
                    alert('Ожидание ответа с сервера слишком велико');
                } else {
                    alert('При отправке запроса возникли какие-то проблемы');
                }
            }
        });
    }
    return false;
}