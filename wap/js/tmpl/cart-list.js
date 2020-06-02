$(function (){
    var key = getcookie('key');
    if(key==''){
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
    }else{
        //初始化页面数据
        function initCartList(){
             $.ajax({
                url:ApiUrl+"/index.php?act=member_cart&op=cart_list",
                type:"post",
                dataType:"json",
                data:{key:key},
                success:function (result){
                    if(checklogin(result.login)){
                        if(!result.datas.error){
                            var rData = result.datas;
                            rData.WapSiteUrl = WapSiteUrl;
                            var html = template.render('cart-list', rData);
                            $("#cart-list-wp").html(html);
                            //删除chart
                            $(".cart-list-del").click(delCartList);
                             //购买数量，减
                            $(".minus-wp").click(minusBuyNum);
                            //购买数量加
                            $(".add-wp").click(addBuyNum);
                            //去结算
                            $(".goto-settlement").click(goSettlement);
                            $(".buynum").blur(buyNumer);
                        }else{
                           alert(result.datas.error);
                        }
                    }
                }
            });
        }
        initCartList();
        //删除chart
        function delCartList(){
            var  cart_id = $(this).attr("cart_id");
            $.ajax({
                url:ApiUrl+"/index.php?act=member_cart&op=cart_del",
                type:"post",
                data:{key:key,cart_id:cart_id},
                dataType:"json",
                success:function (res){
                    if(checklogin(res.login)){
                        if(!res.datas.error && res.datas == "1"){
                            initCartList();
                        }else{
                            alert(res.datas.error);
                        }
                    }
                }
            });
        }
        //购买数量减
        function minusBuyNum(){
            var self = this;
            editQuantity(self,"minus");
        }
        //购买数量加
        function addBuyNum(){
            var self = this;
            editQuantity(self,"add");
        }
        //购买数量增或减，请求获取新的price
        function editQuantity(self,type){
            var sPrents = $(self).parents(".cart-litemw-cnt")
            var cart_id = sPrents.attr("cart_id");
            var numInput = sPrents.find(".buy-num");
            var buynum = parseInt(numInput.val());
            var quantity = 1;
            if(type == "add"){
                quantity = parseInt(buynum+1);
                // 
            }else {
                if(buynum >1){
                    quantity = parseInt(buynum-1);
                }else {
                    $.sDialog({
                        skin:"red",
                        content:'购买数目必须大于1',
                        okBtn:false,
                        cancelBtn:false
                    });
                    return;
                }
            }
            $.ajax({
                url:ApiUrl+"/index.php?act=member_cart&op=cart_edit_quantity",
                type:"post",
                data:{key:key,cart_id:cart_id,quantity:quantity},
                dataType:"json",
                success:function (res){
                    if(checklogin(res.login)){
                        if(!res.datas.error){
                            numInput.val(quantity);
                            sPrents.find(".doctors-total-price").html(res.datas.total_price);
                            var doctorsTotal = $(".doctors-total-price");
                            var totalPrice = parseFloat("0.00");
                            for(var i = 0;i<doctorsTotal.length;i++){
                                totalPrice += parseFloat($(doctorsTotal[i]).html());
                            }
                            $(".total_price").html("$"+totalPrice.toFixed(2));
                        }else{
                            $.sDialog({
                                skin:"red",
                                content:res.datas.error,
                                okBtn:false,
                                cancelBtn:false
                            });
                        }
                    }
                }
            });
        }
        //去结算
        function goSettlement(){
            //chartID
            var cartIdArr = [];
            var cartIdEl = $(".cart-litemw-cnt");
            for(var i = 0;i<cartIdEl.length;i++){
                var cartId = $(cartIdEl[i]).attr("cart_id");
                var cartNum = parseInt($(cartIdEl[i]).find(".buy-num").val());
                var cartIdNum = cartId+"|"+cartNum;
                cartIdArr.push(cartIdNum);
            }
            var cart_id = cartIdArr.toString();
            window.location.href = WapSiteUrl + "/tmpl/appointment/buy_step1.html?ifcart=1&cart_id="+cart_id;
        }
        //验证
        $.sValid.init({
            rules:{
                buynum:"digits"
            },
            messages:{
                buynum:"请输入正确的数字"
            },
            callback:function (eId,eMsg,eRules){
                if(eId.length >0){
                    var errorHtml = "";
                    $.map(eMsg,function (idx,item){
                        errorHtml += "<p>"+idx+"</p>";
                    });
                    $.sDialog({
                        skin:"red",
                        content:errorHtml,
                        okBtn:false,
                        cancelBtn:false
                    });
                }
            }  
        });
        function buyNumer(){
            $.sValid();
        }
    }
});