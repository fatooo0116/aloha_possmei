(function($){
    console.log('init');

    $("#prodcut_header .controller a").on("click",function(e){
        $(this).addClass("active");
        $(this).siblings().removeClass("active");

        e.preventDefault();
    });
})(jQuery);