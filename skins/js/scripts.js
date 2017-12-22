// Слайдер

function button (elem1, elem2, time, addmargintop) {
    if (addmargintop === undefined) {
        addmargintop = 0;
    }
    elem1.on('click',	function() {
        //$(document).scrollTop (elem2.offset().top); //- прокрутка без анимации
        $("html, body").stop(true);
        $("html, body").animate({ scrollTop: elem2.offset().top-addmargintop},time, 'linear');
    });
}
button($('#upward'),$('header'), 600);

$(document).on('scroll',	function() {
    if ($(document).scrollTop () > 160) {
        $('#upward').css ("display", "block");
    } else {
        $('#upward').css ("display", "none");
    }
});

// Поиск по сайту

document.getElementById("search").onclick = function () {
    $('#first_layer').stop(true);
    $('#second_layer').stop(true);
    $("#first_layer").animate({ opacity: 0, paddingTop: 20}, 200);
    setTimeout("$('#first_layer').css('display', 'none'); $('#second_layer').css('display', 'block')", 200);
    $("#second_layer").animate({ opacity: 1, paddingTop: 30}, 400);
}

document.getElementById("close").onclick = function () {
    $('#first_layer').stop(true);
    $('#second_layer').stop(true);
    $('#second_layer').animate({ opacity: 0, paddingTop: 0}, 200);
    setTimeout("$('#second_layer').css('display', 'none'); $('#first_layer').css('display', 'block')", 200);
    $('#first_layer').animate({ opacity: 1, paddingTop: 0}, 400);
}

// Анимация поднавигации навигации
function anim_nav (nav, sub_nav) {
    var x = $(nav).css('color');

    $(nav).mouseenter (function () {
        $(sub_nav).stop(true);
        $(sub_nav).css('display', 'block');
        $(sub_nav).animate({ opacity: 1, "border-top-width": 7}, 250);
        setTimeout("$('"+sub_nav+"').css('display', 'block');", 250);
        if (x != 'rgb(255, 255, 255)') {
            $(nav).css({
                'color' : 'white',
                'background-color' : '#5AAAD0',
                'border-color'     : '#5AAAD0',
                'border-width'    : 1
            });
        }
    });

    $(sub_nav).mouseenter (function () {
        $(sub_nav).stop(true);
        $(sub_nav).css({"opacity": 1,  "border-top-width": 7});
        setTimeout("$('"+sub_nav+"').css('display', 'block');", 250);
        if (x != 'rgb(255, 255, 255)') {
            $(nav).css({
                'color' : 'white',
                'background-color' : '#5AAAD0',
                'border-color'     : '#5AAAD0',
                'border-width'    : 1
            });
        }
    });

    $(nav).mouseleave (function () {
        $(sub_nav).animate({ opacity: 0, "border-top-width": 30}, 250);
        setTimeout("$('"+sub_nav+"').css('display', 'none');", 250);
        if (x != 'rgb(255, 255, 255)') {
            $(nav).css({
                "color": '#57576A',
                "background-color": '#fff',
                "border-color": '#fff',
                "border-width": 1
            });
        }
    });

    $(sub_nav).mouseleave (function () {
        $(sub_nav).animate({ opacity: 0, "border-top-width": 30}, 250);
        setTimeout("$('"+sub_nav+"').css('display', 'none');", 250);
        if (x != 'rgb(255, 255, 255)') {
            $(nav).css({
                "color": '#57576A',
                "background-color": '#fff',
                "border-color": '#fff',
                "border-width": 1
            });
        }
    });
}

anim_nav('#game', '#game_nav');
anim_nav('#demo', '#demo_nav');