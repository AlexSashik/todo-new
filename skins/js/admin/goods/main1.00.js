// Выделение всех checkbox

$("#all_goods").on('click', function () {
    if ($("#all_goods").prop("checked") == true ) {
        $('.goods_checkboxes').prop("checked", true);
    } else {
        $('.goods_checkboxes').prop("checked", false);
    }
});

// Обработка отправки формы на удаление товаров
function delAll () {
    if ($('input:checkbox:checked').length == 0) {
        return (true);
    } else {
        return confirm ('Вы точно хотите удалить все выделенные товары?');
    }
}

function del() {
    return confirm ('Вы точно хотите удалить выделенный товар?');
}