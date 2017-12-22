// Управление progress-bar HP сервера и клиента

var user_hp = $('#leftValue').attr('data-leftValue');
var server_hp = $('#rightValue').attr('data-rightValue');
$('#leftValue').css('width', user_hp);
$('#rightValue').css('width', server_hp);