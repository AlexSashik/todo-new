// Скрытие центрального блока оповещения
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

// Скрытие правого блока оповещения
$("#info_err_close").on("click", function () {
    $("#info_err").animate({ opacity: 0}, 200);
    setTimeout('$("#info_err").css("display", "none");', 200);
});