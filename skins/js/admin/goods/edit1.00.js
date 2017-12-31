// Удаление фото
function del() {
    return confirm ('Вы точно хотите удалить данное фото товара?');
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