(function($){
    console.log('init');

    $("#prodcut_header .controller a").on("click",function(e){
        $(this).addClass("active");
        $(this).siblings().removeClass("active");

        alert("xxxs");

            $.ajax({
                type: "POST",
                url: '/wp-json/cargo/v1/product_layout',
                data: {
                    term_id: 1, 
                    layout: 2
                  },
                success: function(res){
                    if(res){
                        console.log(res);
                    }else{
                        console.log('none');
                    }
                    
                }               
            });

       // e.preventDefault();


    });
})(jQuery);