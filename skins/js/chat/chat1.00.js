var last_id = $('#chatSpace').attr('data-firstid'), send = false, get = false;

function chars2smiles (str) {
    var result = str.replace(/:D/g, '<span class="smile1"></span>');
    result = result.replace(/:''\(/g, '<span class="smile2"></span>');
    result = result.replace(/\^_\^/g, '<span class="smile3"></span>');
    result = result.replace(/:-\*/g, '<span class="smile4"></span>');
    result = result.replace(/&gt;:-\(/g, '<span class="smile5"></span>');
    result = result.replace(/:'\(/g, '<span class="smile6"></span>');
    result = result.replace(/;\)/g, '<span class="smile7"></span>');
    result = result.replace(/:\)/g, '<span class="smile8"></span>');
    result = result.replace(/B\)/g, '<span class="smile9"></span>');
    return result;
}

function mySend () {
    var text = $("#text").val();
    if (text !== undefined) {
        if (text.trim() == '') {
            $('#text').focus();
            alert('Вы не ввели сообщение');
        } else {
            if (!send) {
                $('#text').val('');
                (function () {
                    if (get) {
                        setTimeout(arguments.callee, 200);
                    } else {
                        send = true;
                        $.ajax({
                            url: '/chat/send?ajax',
                            type: "POST",
                            cache: false,
                            data: {
                                'text': text,
                                'lastId' : last_id
                            },
                            dataType: 'json',
                            timeout: 15000,
                            success: function (resp) {
                                send = false;
                                if (resp.err !== undefined) {
                                    if (resp.err == 'NO') {
                                        alert('Вы не авторизованы!');
                                    } else {
                                        alert('Вы забанены администратором сайта и не можете участвовать в чате.');
                                    }
                                } else {
                                    if (resp.login !== undefined && resp.login.length > 0) {
                                        last_id = resp.id[resp.id.length - 1];
                                        for (var i = 0; i < resp.login.length; i++) {
                                            var div = document.createElement('div');
                                            if (resp.forme !== undefined) {
                                                div.className = "for-me";
                                            }
                                            div.setAttribute('data-idBlock', resp.id[i]);
                                            if (resp.status !== undefined) {
                                                div.innerHTML = '<p><strong><em>' + resp.login[i] + '</em></strong>: ' +
                                                    chars2smiles(resp.text[i]) + '</p><i class="fa fa-trash fa-lg" aria-hidden="true" data-id="' + resp.id[i] + '"></i>';
                                            } else {
                                                div.innerHTML = '<p><strong><em>' + resp.login[i] + '</em></strong>: ' + chars2smiles(resp.text[i]) + '</p>';
                                            }
                                            chatSpace.appendChild(div);
                                        }
                                        //прокрутка скролла вниз
                                        chatSpace.scrollTop = chatSpace.scrollHeight;
                                    }
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
                }) ();
            }
        }
    }
}

function usersList(id) {
    if (id === undefined) id = -1;
    $.ajax({
        url: '/chat/userlist?ajax',
        type: "POST",
        cache: false,
        data: {
            'query': 'usersList',
            'id'   : id
        },
        dataType: 'json',
        timeout: 15000,
        beforeSend : function () {
            $('#backToChat').css('display', 'none');
            $('#refreshList').css('display', 'none');
            $("#loading").css('display', 'block');
            $(".group > p").remove();
            $(".group").css('display', 'none');
        },
        success: function (resp) {
            $('#backToChat').css('display', 'block');
            $('#refreshList').css('display', 'block');
            var i;
            $("#loading").css('display', 'none');
            if (resp.admin !== undefined) {
                for (i = 0; i < resp.admin.length; i++) {
                    $("#admins").append('<p><span class="span-for-name">' + resp.admin[i] + '</span></p>');
                }
                $("#admins").css('display', 'block');
            }
            if (resp.online !== undefined) {
                for (i = 0; i < resp.online.length; i++) {
                    if (resp.status !== undefined) {
                        if (resp.online[i].isAdmin === undefined) {
                            $("#online").append(
                                '<p>' +
                                '<span class="span-for-name">' + resp.online[i].login + '</span> ' +
                                '<span class="span-for-ban" data-id="' + resp.online[i].id + '">(забанить)</span>' +
                                '</p>'
                            );
                        } else {
                            $("#online").append(
                                '<p>' +
                                '<span class="span-for-name">' + resp.online[i].login + '</span> ' +
                                '</p>'
                            );
                        }
                    } else {
                        $("#online").append('<p><span class="span-for-name">' + resp.online[i].login + '</span></p>');
                    }
                }
                $("#online").css('display', 'block');
            }
            if (resp.offline !== undefined) {
                for (i = 0; i < resp.offline.length; i++) {
                    if (resp.status !== undefined) {
                        if (resp.offline[i].isAdmin === undefined) {
                            $("#offline").append(
                                '<p>' +
                                '<span class="span-for-name">' + resp.offline[i].login + '</span> ' +
                                '<span class="span-for-ban" data-id="' + resp.offline[i].id + '">(забанить)</span>' +
                                '</p>'
                            );
                        } else {
                            $("#offline").append(
                                '<p>' +
                                '<span class="span-for-name">' + resp.offline[i].login + '</span> ' +
                                '</p>'
                            );
                        }
                    } else {
                        $("#offline").append('<p><span class="span-for-name">' + resp.offline[i].login + '</span></p>');
                    }
                }
                $("#offline").css('display', 'block');
            }
            if (resp.ban !== undefined) {
                for (i = 0; i < resp.ban.length; i++) {
                    if (resp.status !== undefined) {
                        $("#ban").append(
                            '<p>' +
                            '<span class="span-for-name">' + resp.ban[i].login + '</span> ' +
                            '<span class="span-for-ban" data-id="'+resp.ban[i].id+'">(разбанить)</span>' +
                            '</p>'
                        );
                    } else {
                        $("#ban").append('<p><span class="span-for-name">' + resp.ban[i].login + '</span></p>');
                    }
                }
                $("#ban").css('display', 'block');
            }
        }
    });
}

// стилизация скролла
$(document).ready (function () {
    $('.main-chat').niceScroll();
    $('.chat-list-main').niceScroll();
});

// по нажатию на enter происходит отправка сообщения (и скрытие панели смайликов)
$(document).keydown(function(event){
    if (event.which == 13) {
        // очищаем textarea от enter-ов
        if(event.preventDefault) {
            event.preventDefault();
        }
        $('.smiles').css('display', 'none');
        $('#show-smiles').css('backgroundColor', '#FAF9FA');
        mySend();
    }
});

// переключатель режима "чат - список пользователей"
$('#users').on('click', function () {
    $('#chat-body').css('display', 'none');
    $('#chat-list').css('display', 'block');
});

$('#backToChat').on('click', function () {
    $(".group > p").remove();
    $(".group").css('display', 'none');
    $('#chat-list').css('display', 'none');
    $('#chat-body').css('display', 'block');
});

// Именная приставка из чата и из спискоа онлайн соответственно
$('#chatSpace').on('click', 'em', function() {
    $('#text').val($(this).text() + ', ');
    $('#text').focus();
});

$('#online').on('click', '.span-for-name', function() {
    $(".group > p").remove();
    $(".group").css('display', 'none');
    $('#chat-list').css('display', 'none');
    $('#chat-body').css('display', 'block');
    $('#text').val($(this).text() + ', ');
    $('#text').focus();
});

// обновление списка пользователей
$('#refreshList').on('click', function () {
    usersList();
});

//модерация пользователей
$('.group').on('click', '.span-for-ban', function() {
    if ($(this).attr('data-id') !== undefined) {
        usersList($(this).attr('data-id'));
    }
});

// появление и скрытие панели смайликов
$('#show-smiles').on('click', function () {
    if ($('.smiles').css('display') == 'none') {
        $('.smiles').css('display', 'block');
        $(this).css('backgroundColor', '#F2EFF5');
    } else {
        $('.smiles').css('display', 'none');
        $(this).css('backgroundColor', '#FAF9FA');
    }
});

$(document).click(function(event) {
    if ($(event.target).closest(".smiles").length || $(event.target).closest("#show-smiles").length || $(event.target).closest("#text").length) return;
    $('.smiles').css('display', 'none');
    $('#show-smiles').css('backgroundColor', '#FAF9FA');
});

//добавление смайла в текст
$('.smile').on('click', function () {
    $('#text').val($('#text').val() + $(this).attr('data-smile'));
    $('#text').focus();
});

//удаление сообщения
$('#chatSpace').on('click', '.fa-trash', function () {
    if (confirm('Вы точно хотите удалить это сообщение?')) {
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/chat/delmess?ajax',
            type: "POST",
            cache: false,
            data: {
                'id': id
            },
            timeout: 15000,
            success: function(resp) {
                if (resp !== undefined) {
                    $('[data-idBlock = ' + id + ']').remove();
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
});

//обновление чата
setTimeout(function refresh() {
    if (!send) {
        get = true;
        $.ajax({
            url: '/chat/refresh?ajax',
            type: "POST",
            cache: false,
            data: {
                'query': 'chat',
                'lastId': last_id
            },
            dataType: 'json',
            timeout: 15000,
            success: function (resp) {
                get = false;
                if (resp.login !== undefined && resp.login.length > 0) {
                    last_id = resp.id[resp.id.length - 1];
                    for (var i = 0; i < resp.login.length; i++) {
                        var div = document.createElement('div');
                        if (resp.forme !== undefined) {
                            div.className = "for-me";
                        }
                        div.setAttribute('data-idBlock', resp.id[i]);
                        if (resp.status !== undefined) {
                            div.innerHTML = '<p><strong><em>' + resp.login[i] + '</em></strong>: ' +
                                chars2smiles(resp.text[i]) + '</p><i class="fa fa-trash fa-lg" aria-hidden="true" data-id="' + resp.id[i] + '"></i>';
                        } else {
                            div.innerHTML = '<p><strong><em>' + resp.login[i] + '</em></strong>: ' + chars2smiles(resp.text[i]) + '</p>';
                        }
                        chatSpace.appendChild(div);
                    }
                    //прокрутка скролла вниз
                    chatSpace.scrollTop = chatSpace.scrollHeight;
                }
                if (resp.delid !== undefined) {
                    for (var j = 0; j < resp.delid.length; j++) {
                        $('[data-idBlock = ' + resp.delid[j] + ']').remove();
                    }
                    //console.log(resp.delid);
                }
            },
            error: function () {
                get = false;
            }
        });
    }
    setTimeout(refresh, 2000);
}, 2000);

//обновление списка пользователей
setTimeout(function refreshUserList() {
    if ($('#chat-list').css('display') == 'block' &&  $("#loading").css('display') == 'none') {
        usersList();
    }
    setTimeout(refreshUserList, 30000);
}, 30000);