// редактирование комментария
function edit (elem) {
    if (confirm ('Отредактировать данный комментарий?')) {
        var text_id = '#text' + $(elem).attr("data-id");
        $.ajax ({
            url   : '/comments/ajax?ajax',
            type  : "POST",
            cache : false,
            data  : {
                'edit_id'   : $(elem).attr("data-id"),
                'edit_text' : $(text_id).text()
            },
            timeout : 15000,
            success : function (resp) {
                if (resp == 'ok') {
                    // всплывающий блок
                    $("#info_back").css('display', 'block');
                    $("#info_back").css('opacity', 0.5);
                    $("#fixed_back").css('display', 'block');
                    $("#fixed_back").css('opacity', 1);
                    $('#success_comment_title').html('Редактирование комментария');
                    $('#success_comment_body').html('Комментарий был успешно отредактирован! Его обновленная версия ' +
                        'уже сейчас доступна для просмотра на сайте');
                } else {
                    $("#info_back").css('display', 'block');
                    $("#info_back").css('opacity', 0.5);
                    $("#fixed_back").css('display', 'block');
                    $("#fixed_back").css('opacity', 1);
                    $('#success_comment_title').html('Редактирование комментария');
                    $('#success_comment_body').html('Ой, возникли какие-то проблемы... Комментарий не отредактирован. ' +
                        'Попробуйте повторить попытку позже. В случае повторного обнаружения наполадки, свяжитесь с ' +
                        'нами по контактам, указанным в футере сайта.');
                }
            },
            error   : function (x, t) {
                if (t === "timeout") {
                    alert ('Ожидание ответа с сервера слишком велико');
                } else {
                    alert ('При отправке комментария возникли какие-то проблемы');
                }
            }
        });
    }
}

// Анимация поля после успешного добавления комментария

$("#info_back").on("click", function () {
    $("#fixed_back").animate({ opacity: 0}, 200);
    $("#info_back").css("display", "none");
    setTimeout('$("#fixed_back").css("display", "none");', 200);
});

$('#close_button').on('click', function() {
    $('#fixed_back').animate( {opacity: 0}, 200);
    $("#info_back").css("display", "none");
    setTimeout("$('#fixed_back').css('display', 'none')", 200);
});

$('#close_times').on('click', function() {
    $('#fixed_back').animate( {opacity: 0}, 200);
    $("#info_back").css("display", "none");
    setTimeout("$('#fixed_back').css('display', 'none')", 200);
});

// функция валидации email-а с сайта https://ruseller.com/lessons.php?rub=32&id=152

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}

// функция отправки комментария

function myAjax () {

    // проверка полей формы д отправки запроса методом ajax
    var check = true;
    if ($('#login').val() !== undefined) {
        if ($('#login').val() == '') {
            $('#login_err').text('Поле обзательно');
            check = false;
        } else if ($('#login').val().length < 2) {
            $('#login_err').text('Минимум 2 символов');
            check = false;
        } else if ($('#login').val().length > 50) {
            $('#login_err').text('Максимум 50 символов');
            check = false;
        } else {
            $('#login_err').text('');
        }
    }

    if ($('#email').val() !== undefined) {
        if(!isValidEmailAddress($('#email').val())) {
            $('#email_err').text('Некорректный email');
            check = false;
        } else {
            $('#email_err').text('');
        }
    }

    if ($('#text').val() !== undefined) {
        if ($('#text').val() == '') {
            $('#text_err').text('Комментарий не может быть пустым');
            check = false;
        } else {
            $('#text_err').text('');
        }
    } else {
        check = false;
    }

    // в случае успешной проверки на js
    if (check === true) {
        $.ajax ({
            url   : '/comments/ajax?ajax',
            type  : "POST",
            cache : false,
            data  : {
                'login' : $('#login').val(),  // этого поля может не быть, соответствующая обработка - на ajax.php
                'email' : $('#email').val(),  // этого поля может не быть, соответствующая обработка - на ajax.php
                'text' :  $('#text').val()
            },
            dataType : 'json',
            timeout : 15000,
            success : function(response) {

                if (response.err !== undefined) {
                    if (response.err.access !== undefined) {
                        alert(response.err.access);
                    }
                    if (response.err.login !== undefined) {
                        $('#login_err').text(response.err.login);
                    } else {
                        $('#login_err').text('');
                    }
                    if (response.err.email !== undefined) {
                        $('#email_err').text(response.err.email);
                    } else {
                        $('#email_err').text('');
                    }
                    if (response.err.text !== undefined) {
                        $('#text_err').text(response.err.text);
                    } else {
                        $('#text_err').text('');
                    }
                } else {
                    $('#login_err').text('');
                    $('#email_err').text('');
                    $('#text_err').text('');
                    // обработка успешного добавления комментария

                    if(document.getElementById('login')) {
                        document.getElementById('login').value = '';
                    }
                    if(document.getElementById('email')) {
                        document.getElementById('email').value = '';
                    }
                    document.getElementById('text').value = '';

                    // динамическое создание блока с опубликованным комментарием
                    var hr   = document.createElement('hr');
                    var div1 = document.createElement('div');

                    div1.className = "comments";
                    if (status == 5) {
                        var for_edit_span_open  = "<span id='text" + response.id + "' contenteditable>";
                        var for_edit_span_close = "</span>";
                        var for_managment =
                            "<a onclick='return confirm (\"Вы точно хотите удалить данный комментарий?\")'  title='Удалить комментарий' href='/comments?action=delete&id="+response.id+"' class='delete'>" +
                            "<i class='fa fa-trash-o fa-2x' aria-hidden='true'></i>" +
                            "</a>" +
                            "<span onclick='return edit(this)' title='Редактировать комментарий' data-id='"+response.id+"' class='edit'>" +
                            "<i class='fa fa-pencil-square-o fa-2x' aria-hidden='true'></i>" +
                            "</span>";
                    } else {
                        var for_edit_span_open  = '';
                        var for_edit_span_close = '';
                        var for_managment = '';
                    }
                    div1.innerHTML =
                        "<div class='photo'>" +
                        "<img alt='' src='/skins/img/default/users/100x100/" + response.img_name + "'>" +
                        "</div>" +
                        "<div class='field'>" +
                        "<div class='login'>" +
                        response.login +
                        "<br>" +
                        "<span class='time'>Статус: " + response.status + " сайта</span>" +
                        "</div>" +
                        for_edit_span_open + response.text + for_edit_span_close +
                        "<br>" +
                        "<span class='time'>Опубликовано " + response.time + "</span>" +
                        "</div>" +
                        for_managment;

                    main.insertBefore(hr,   main.children[2]);
                    main.insertBefore(div1, main.children[3]);

                    // всплывающий блок
                    $("#info_back").css('display', 'block');
                    $("#info_back").css('opacity', 0.5);
                    $("#fixed_back").css('display', 'block');
                    $("#fixed_back").css('opacity', 1);
                    $('#success_comment_title').html('Добавление комментария');
                    if (response.status == 'гость') {
                        $('#success_comment_body').html('Уважаемый гость!<br><br> Ваш комментарий от имени <strong>' + response.login +
                            '</strong> был успешно добавлен и уже сейчас доступен для просмотра.');
                    } else {
                        $('#success_comment_body').html('Уважаетемый <strong>' + response.login +
                            '</strong>!<br><br>Ваш комментарий был успешно добавлен и уже сейчас доступен для просмотра.');
                    }

                    // счетчик количества комментариев
                    var count = +$('#comm_count').attr('data-count') + 1;
                    $('#comm_count').attr('data-count', count);
                    if (+count % 10 == 1 && $.inArray(+count % 100, [ 11, 12, 13, 14, 15, 16, 17, 18, 19 ] ) == -1) {
                        var word = 'комментарий';
                    } else if ($.inArray(+count % 10, [ 2, 3, 4 ] ) != -1 && $.inArray(+count % 100, [ 11, 12, 13, 14, 15, 16, 17, 18, 19 ] ) == -1) {
                        var word = 'комментария';
                    } else {
                        var word = 'комментариев';
                    }
                    $('#comm_count').html(count + ' ' + word);
                }
            },
            error   : function (x, t) {
                if (t === "timeout") {
                    alert ('Ожидание ответа с сервера слишком велико');
                } else {
                    alert ('При отправке комментария возникли какие-то проблемы');
                }
            }
        });
    }
}

// Скрытие блока оповещения об удалении  комментария
$("#info_background").on("click", function () {
    $("#info_text").animate({ opacity: 0}, 200);
    $("#info_background").css("display", "none");
    setTimeout('$("#info_text").css("display", "none");', 200);
});

$("#info_close").on("click", function () {
    $("#info_text").animate({ opacity: 0}, 200);
    $("#info_background").css("display", "none");
    setTimeout('$("#info_text").css("display", "none");', 200);
});

// Разъяснение правил редактирования
$(".main").on('mouseover', '.edit', function () {
    $("#help").css("display", "block");
});

$(".main").on('mouseout', '.edit', function () {
    $("#help").css("display", "none");
});