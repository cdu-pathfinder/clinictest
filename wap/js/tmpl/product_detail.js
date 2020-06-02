$(function (){
     // 图片轮播
    function picSwipe(){
      var elem = $("#mySwipe")[0];
      window.mySwipe = Swipe(elem, {
        continuous: true,
        // disableScroll: true,
        stopPropagation: true,
        callback: function(index, element) {
          $(".pds-cursize").html(index+1);
        }
      });
    } 
    var doctors_id = GetQueryString("doctors_id");
    //渲染页面
    $.ajax({
       url:ApiUrl+"/index.php?act=doctors&op=doctors_detail",
       type:"get",
       data:{doctors_id:doctors_id},
       dataType:"json",
       success:function(result){
          var data = result.datas;
          if(!data.error){
            //商品图片格式化数据
            if(data.doctors_image){
              var doctors_image = data.doctors_image.split(",");
              data.doctors_image = doctors_image;
            }else{
               data.doctors_image = [];
            }
            //商品规格格式化数据
            if(data.doctors_info.spec_name){
              var doctors_map_spec = $.map(data.doctors_info.spec_name,function (v,i){
                var doctors_specs = {};
                doctors_specs["doctors_spec_id"] = i;
                doctors_specs['doctors_spec_name']=v;
                if(data.doctors_info.spec_value){
	                $.map(data.doctors_info.spec_value,function(vv,vi){
	                    if(i == vi){
	                      doctors_specs['doctors_spec_value'] = $.map(vv,function (vvv,vvi){
	                        var specs_value = {};
	                        specs_value["specs_value_id"] = vvi;
	                        specs_value["specs_value_name"] = vvv;
	                        return specs_value;
	                      });
	                    }
	                  });
	                  return doctors_specs;               	
                }else{
                	data.doctors_info.spec_value = [];
                }
              });
              data.doctors_map_spec = doctors_map_spec;
            }else {
              data.doctors_map_spec = [];
            }
            //渲染模板
            var html = template.render('doc_detail', data);
            $("#doc_detail_wp").html(html);
            //图片轮播
            picSwipe();
            //商品描述
            $(".pddcp-arrow").click(function (){
              $(this).parents(".pddcp-one-wp").toggleClass("current");
            });
            //规格属性
            var myData = {};
            myData["spec_list"] = data.spec_list;
            $(".pddc-stock a").click(function (){
              var self = this;
              arrowClick(self,myData);
            });
            //购买数量，减
            $(".minus-wp").click(function (){
               var buynum = $(".buy-num").val();
               if(buynum >1){
                  $(".buy-num").val(parseInt(buynum-1));
               }
            });
            //购买数量加
            $(".add-wp").click(function (){
               var buynum = parseInt($(".buy-num").val());
               if(buynum < data.doctors_info.doctors_storage){
                  $(".buy-num").val(parseInt(buynum+1));
               }
            });
            //收藏
            $(".pd-collect").click(function (){
                var key = getcookie('key');//login标记
                if(key==''){
                  window.location.href = WapSiteUrl+'/tmpl/member/login.html';
                }else {
                  $.ajax({
                    url:ApiUrl+"/index.php?act=member_favorites&op=favorites_add",
                    type:"post",
                    dataType:"json",
                    data:{doctors_id:doctors_id,key:key},
                    success:function (fData){
                     if(checklogin(fData.login)){
                        if(!fData.datas.error){
                          $.sDialog({
                            skin:"green",
                            content:"收藏成功！",
                            okBtn:false,
                            cancelBtn:false
                          });
                        }else{
                          $.sDialog({
                            skin:"red",
                            content:fData.datas.error,
                            okBtn:false,
                            cancelBtn:false
                          });
                        }
                      }
                    }
                  });
                }
            });
            //加入chart
            $(".add-to-cart").click(function (){
              var key = getcookie('key');//login标记
               if(key==''){
                  window.location.href = WapSiteUrl+'/tmpl/member/login.html';
               }else{
                  var quantity = parseInt($(".buy-num").val());
                  $.ajax({
                     url:ApiUrl+"/index.php?act=member_cart&op=cart_add",
                     data:{key:key,doctors_id:doctors_id,quantity:quantity},
                     type:"post",
                     success:function (result){
                        var rData = $.parseJSON(result);
                        if(checklogin(rData.login)){
                          if(!rData.datas.error){
                             $.sDialog({
                                skin:"block",
                                content:"添加chart成功！",
                                "okBtnText": "再逛逛",
                                "cancelBtnText": "去chart",
                                okFn:function (){},
                                cancelFn:function (){
                                  window.location.href = WapSiteUrl+'/tmpl/cart_list.html';
                                }
                              });
                          }else{
                            $.sDialog({
                              skin:"red",
                              content:rData.datas.error,
                              okBtn:false,
                              cancelBtn:false
                            });
                          }
                        }
                     }
                  })
               }
            });

            //立即购买
            $(".buy-now").click(function (){
               var key = getcookie('key');//login标记
               if(key==''){
                  window.location.href = WapSiteUrl+'/tmpl/member/login.html';
               }else{
            	  var json = {};
            	  var buynum = $('.buy-num').val();
            	  json.key = key;
            	  json.cart_id = doctors_id+'|'+buynum;
            	  $.ajax({
            		  type:'post',
            		  url:ApiUrl+'/index.php?act=member_buy&op=buy_step1',
            		  data:json,
            		  dataType:'json',
            		  success:function(result){
            			  if(typeof(result.datas.error) == 'undefined'){
            				  location.href = WapSiteUrl+'/tmpl/appointment/buy_step1.html?doctors_id='+doctors_id+'&buynum='+buynum;               				        				  
            			  }else{
                              $.sDialog({
                                  skin:"red",
                                  content:result.datas.error,
                                  okBtn:false,
                                  cancelBtn:false
                                });            				  
            			  }
            		  }
            	  });             	  
               }
            });
          }else {
            var html = data.error;
            $("#doc_detail_wp").html(html);
          }
          //验证购买数量是不是数字
          $("#buynum").blur(buyNumer);
          AddView();
       }
       

    });
  //点击商品规格，获取新的商品
  function arrowClick(self,myData){
    $(self).addClass("current").siblings().removeClass("current");
    //拼接属性
    var curEle = $(".pddc-stock-spec").find("a.current");
    var curSpec = [];
    $.each(curEle,function (i,v){
      curSpec.push($(v).attr("specs_value_id"));
    });
    var spec_string = curSpec.sort().join("|");
    //获取商品ID
    var spec_doctors_id = myData.spec_list[spec_string];
    window.location.href = "doc_detail.html?doctors_id="+spec_doctors_id;
  }
  
  function AddView(){//增加浏览记录
	  var doctors_info = getcookie('doctors');
	  var doctors_id = GetQueryString('doctors_id');
	  if(doctors_id<1){
		  return false;
	  }

	  if(doctors_info==''){
		  doctors_info+=doctors_id; 
	  }else{

		  var doctorsarr = doctors_info.split('@');
		  if(contains(doctorsarr,doctors_id)){
			  return false;
		  }
		  if(doctorsarr.length<5){
			  doctors_info+='@'+doctors_id;
		  }else{
			  doctorsarr.splice(0,1);
			  doctorsarr.push(doctors_id);
			  doctors_info = doctorsarr.join('@');
		  }
	  }

	  addcookie('doctors',doctors_info);
	  return false;
  }
  
  function contains(arr, str) {//检测doctors_id是否存入
	    var i = arr.length;
	    while (i--) {
	           if (arr[i] === str) {
	           return true;
	           }   
	    }   
	    return false;
	}
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
  //检测appointments是否为正整数
  function buyNumer(){
    $.sValid();
  }
});