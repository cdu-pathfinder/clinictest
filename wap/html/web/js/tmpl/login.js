$(function (){
    $(".auto-login").click(function (){
        $(this).find(".s-chk1").toggleClass("on");
    });
    $.sValid.init({
        rules:{
            username:"required",
            userpwd:{
                required:true,
                maxlength:2
            }
        },
        messages:{
            username:"user name is required！",
            userpwd:{
                required:"password is required！",
                maxlength:'长度不能超过2'
            }
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $(".error-tips").html(errorHtml);
            }else{
                 $(".error-tips").html("");
            }
        }  
    });
    $(".l-btn-login").click(function (){
        if($.sValid()){
            alert(1);
        }
    });
});