function api(query) {
    if (query === undefined) {
        alert ('Не был указан тип запроса');
    } else {
        var login = $('#login').val(),
            pass = $('#pass').val(),
            format = $('.format:checked').val();

        if (login.trim() == '') {
            alert('Вы не ввели логин');
        } else if (pass.trim() == '') {
            alert('Вы не ввели пароль');
        } else if (!format || (format != 'json' && format != 'xml')) {
            alert ('Передан неккоректный формат ожидаемого ответа');
        } else {
            $.ajax({
                url: 'http://todo.kh.ua/api/user/data',
                type: "POST",
                cache: false,
                dataType: format,
                data: {
                    'query': query,
                    'login': login,
                    'pass' : pass,
                    'format' : format
                },
                timeout: 15000,
                success: function(resp) {
                    if (resp) {
                        $("#response").empty();
                        if (format == 'json') {
                           $("#response").append('{\n');
                           Object.keys(resp).forEach(function(key) {
                               $("#response").append('     "' + key + '": "' + resp[key] + '",\n');
                           });
                           $("#response").append('}\n');
                        } else {
                            var XMLdoc = resp.documentElement.childNodes;
                            $("#response").append('&lt;response&gt;\n');
                            for (var i = 0; i < XMLdoc.length ;i++) {
                                $("#response").append('    &lt;' + XMLdoc[i].nodeName + '&gt;' + XMLdoc[i].childNodes[0].nodeValue + '&lt;/' + XMLdoc[i].nodeName + '&gt;\n');
                            }
                            $("#response").append('&lt;/response&gt;');
                        }
                        $("#info_back").css("display", "block");
                        $("#info_text").css("display", "block");
                        $("#info_text").css("opacity", 1);
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
    }
}

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