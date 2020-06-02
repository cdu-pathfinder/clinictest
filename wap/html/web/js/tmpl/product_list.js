$(function (){
    $(".page-warp").click(function (){
        $(this).find(".pagew-size").toggle();
    });
    $(".doc-filter a").click(function (){
        $(this).addClass("current").siblings().removeClass("current");
    });
});