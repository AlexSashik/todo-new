// Выплывание картинки (монитор) в 4-м блоке

$(document).on('scroll',	function() {
    if ($('#main_fourth_block_img').offset().top-$(document).scrollTop ()< document.documentElement.clientHeight-100) {
        $("#main_fourth_block_img").animate({ opacity: 1, marginRight: 15}, 500);
    }
});


// Галерея в 1-м блоке главной страницы

$("#title1").css("border","1px solid #fff");
$("#title1").css("background-color","#fff");

first_animation = true;
second_animation = true;
third_animation = true;

document.getElementById("title1").onclick = function () {
    second_animation = false;
    third_animation = false;
    if (!first_animation ) return false;
    first_animation = false;
    $("#title2").css("border","1px solid #76B7D7");
    $("#title2").css("background-color","#76B7D7");
    $("#title3").css("border","1px solid #76B7D7");
    $("#title3").css("background-color","#76B7D7");
    $("#title1").css("border","1px solid #fff");
    $("#title1").css("background-color","#fff");
    $("#main_first_block_layer1").animate({ opacity: 1}, 500);
    $("#main_first_block_content_layer2").animate({ opacity: 0}, 500);
    $("#main_first_block_content_layer3").animate({ opacity: 0}, 500);
    $("#main_first_block_layer2").animate({ opacity: 0}, 500);
    $("#main_first_block_layer3").animate({ opacity: 0}, 500);
    setTimeout("$('#main_first_block_content_layer1').animate({ opacity: 1, top: 185}, 500)", 800);
    setTimeout("$('#main_first_block_content_layer2').css('top', '220px')", 500);
    setTimeout("$('#main_first_block_content_layer3').css('top', '220px')", 500);
    setTimeout("first_animation = true; second_animation = true; third_animation = true", 800);
};

document.getElementById("title2").onclick = function () {
    first_animation = false;
    third_animation = false;
    if (!second_animation ) return false;
    second_animation = false;
    $("#title1").css("border","1px solid #76B7D7");
    $("#title1").css("background-color","#76B7D7");
    $("#title3").css("border","1px solid #76B7D7");
    $("#title3").css("background-color","#76B7D7");
    $("#title2").css("border","1px solid #fff");
    $("#title2").css("background-color","#fff");
    $("#main_first_block_layer2").animate({ opacity: 1}, 500);
    $("#main_first_block_content_layer1").animate({ opacity: 0}, 500);
    $("#main_first_block_content_layer3").animate({ opacity: 0}, 500);
    $("#main_first_block_layer1").animate({ opacity: 0}, 500);
    $("#main_first_block_layer3").animate({ opacity: 0}, 500);
    setTimeout("$('#main_first_block_content_layer2').animate({ opacity: 1, top: 185}, 500)", 800);
    setTimeout("$('#main_first_block_content_layer1').css('top', '220px')", 500);
    setTimeout("$('#main_first_block_content_layer3').css('top', '220px')", 500);
    setTimeout("first_animation = true;  second_animation = true; third_animation = true", 800);
};

document.getElementById("title3").onclick = function () {
    first_animation = false;
    second_animation = false;
    if (!third_animation ) return false;
    third_animation = false;
    $("#title1").css("border","1px solid #76B7D7");
    $("#title1").css("background-color","#76B7D7");
    $("#title2").css("border","1px solid #76B7D7");
    $("#title2").css("background-color","#76B7D7");
    $("#title3").css("border","1px solid #fff");
    $("#title3").css("background-color","#fff");
    $("#main_first_block_layer3").animate({ opacity: 1}, 500);
    $("#main_first_block_content_layer1").animate({ opacity: 0}, 500);
    $("#main_first_block_content_layer2").animate({ opacity: 0}, 500);
    $("#main_first_block_layer1").animate({ opacity: 0}, 500);
    $("#main_first_block_layer2").animate({ opacity: 0}, 500);
    setTimeout("$('#main_first_block_content_layer3').animate({ opacity: 1, top: 185}, 500)", 800);
    setTimeout("$('#main_first_block_content_layer2').css('top', '220px')", 500);
    setTimeout("$('#main_first_block_content_layer1').css('top', '220px')", 500);
    setTimeout("first_animation = true; second_animation = true; third_animation = true", 800);
};

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