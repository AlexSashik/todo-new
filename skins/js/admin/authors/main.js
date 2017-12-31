// Обработка отправки формы на удаление товаров

function del() {
    return confirm ('Вы точно хотите удалить выделенного автора? При этом будут автоматически удалены все его книги!');
}

// Стилизация поля загрузки файлов
$("#picture").on("change", function (event) {
    var files = $(this)[0].files;
    if (files.length >= 2) {
        $("#file_label_span").text("Количество файлов: " + files.length);
    } else {
        var filename = event.target.value.split('\\').pop();
        if (filename == '') {
            $("#file_label_span").text('Выберите фото');
        } else {
            $("#file_label_span").text(filename);
        }
    }
});