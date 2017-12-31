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