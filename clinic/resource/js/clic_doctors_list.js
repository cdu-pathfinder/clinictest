$(function(){
    // ajax获取商品列表
    $('i[nctype="ajaxdoctorsList"]').toggle(
        function(){
            $(this).removeClass('icon-plus-sign').addClass('icon-minus-sign');
            var _parenttr = $(this).parents('tr');
            var _commonid = $(this).attr('data-comminid');
            var _div = _parenttr.next().find('.ncsc-doctors-sku');
            if (_div.html() == '') {
                $.getJSON('index.php?act=clic_doctors_online&op=get_doctors_list_ajax' , {commonid : _commonid}, function(date){
                    if (date != 'false') {
                        var _ul = $('<ul class="ncsc-doctors-sku-list"></ul>');
                        $.each(date, function(i, o){
                            $('<li><div class="doctors-thumb" title="商家货号：' + o.doctors_serial + '"><a href="' + o.url + '" target="_blank"><image src="' + o.doctors_image + '" ></a></div>' + o.doctors_spec + '<div class="doctors-price">价格：<em title="￥' + o.doctors_price + '">￥' + o.doctors_price + '</em></div><div class="doctors-storage" ' + o.alarm + '>库存：<em title="' + o.doctors_storage + '" ' + o.alarm + '>' + o.doctors_storage + '</em></div><a href="' + o.url + '" target="_blank" class="ncsc-btn-mini">查看商品详情</a></li>').appendTo(_ul);
                        });
                        _ul.appendTo(_div);
                        _parenttr.next().show();
                        _div.perfectScrollbar();
                    }
                });
            } else {
            	_parenttr.next().show()
            }
        },
        function(){
            $(this).removeClass('icon-minus-sign').addClass('icon-plus-sign');
            $(this).parents('tr').next().hide();
        }
    );
});