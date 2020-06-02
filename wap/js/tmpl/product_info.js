$(function() {
    var doctors_id = GetQueryString("doctors_id");
    $.ajax({
        url: ApiUrl + "/index.php?act=doctors&op=doctors_body",
        data: {doctors_id: doctors_id},
        type: "get",
        success: function(result) {
            $(".fixed-tab-pannel").html(result);
        }
    });
});