function generateJSON(elem, obj, first) {
    if (first === undefined) first = true;
    if (first) $(elem).append('{\n');
    Object.keys(obj).forEach(function(key) {
        if (Array.isArray(obj[key])) {
            $(elem).append('"' + key + '": {\n');
            generateJSON(elem, obj[key], false);
            $(elem).append('},\n');
        } else {
            $(elem).append('"' + key + '": "' + obj[key] + '",\n');
        }
    });
    if (first) $(elem).append('}\n');
}

function generateXML (elem, obj, first) {
    if (first === undefined) first = true;
    if (first) $(elem).append('&lt;response&gt;\n');
    for (var i = 0; i < obj.length ;i++) {
        if (obj[i].childNodes[0].nodeValue == null) {
            $(elem).append('&lt;' + obj[i].nodeName + '&gt;\n');
            generateXML(elem,obj[i].childNodes, false);
            $(elem).append('&lt;/' + obj[i].nodeName + '&gt;\n');
        } else {
            $(elem).append('&lt;' + obj[i].nodeName + '&gt;' + obj[i].childNodes[0].nodeValue + '&lt;/' + obj[i].nodeName + '&gt;\n');
        }
    }
    if (first) $(elem).append('&lt;/response&gt;');
}

function api(query) {
    if (query === undefined) {
        alert ('Не был указан тип запроса');
    } else {
        var login = $('#login').val(),
            pass = $('#pass').val(),
            format = $('.format:checked').val(),
            url = (query == 'del_social') ? 'http://todo.kh.ua/api/user/delsocial' : 'http://todo.kh.ua/api/user/data';

        if (login.trim() == '') {
            alert('Вы не ввели логин');
        } else if (pass.trim() == '') {
            alert('Вы не ввели пароль');
        } else if (!format || (format != 'json' && format != 'xml')) {
            alert ('Передан неккоректный формат ожидаемого ответа');
        } else {
            if (query == 'del_social') {
                query = $('.del_soc input:checkbox:checked').map(function() {return this.value;}).get();
            }

            if (!query.length) {
                alert('Вы не выбрали удаляемую соцсеть');
            } else {
                $.ajax({
                    url: url,
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
                               generateJSON("#response", resp);
                            } else {
                                var xml_obj = resp.documentElement.childNodes;
                                generateXML("#response", xml_obj);
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