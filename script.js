jQuery(function($){
    $(".hz-footnote-block a").on("click",function(e){
        e.preventDefault();
        var h = $(this).attr("href");
        $('html,body').animate({scrollTop: $(h).offset().top - 200}, 1500);
    });
});