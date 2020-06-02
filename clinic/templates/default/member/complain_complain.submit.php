<script type="text/javascript">
$(document).ready(function(){
    //默认选中第一个radio
    $(":radio").first().attr("checked",true);
    //提交表单
    $("#submit_button").click(function(){
       submit_add_form();
    });
    //兼容ie暂时全部显示
    //默认不现实问题输入框
    $(".problem_desc").hide();
    $(".checkitem").click(function(){
        if($(this).attr('checked')) {
            $(this).parents('tr').next('.problem_desc').show();
        }
        else {
            $(this).parents('tr').next('.problem_desc').hide();
        }
    });
    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
            rules : {
                input_complain_pic1 : {
                    accept : 'jpg|jpeg|gif|png'
                },
                input_complain_pic2 : {
                    accept : 'jpg|jpeg|gif|png'
                },
                input_complain_pic3 : {
                    accept : 'jpg|jpeg|gif|png'
                }
            },
                messages : {
                    input_complain_pic1: {
                        accept : '<?php echo $lang['complain_pic_error'];?>'
                    },
                    input_complain_pic2: {
                        accept : '<?php echo $lang['complain_pic_error'];?>'
                    },
                    input_complain_pic3: {
                        accept : '<?php echo $lang['complain_pic_error'];?>'
                    }
                }
    });

});
function submit_add_form() {
    var items = get_checked_items();
    if(items == '') {
    	showDialog('<?php echo $lang['complain_doctors_select'];?>');
    }
    else {
        var complain_content = $("#input_complain_content").val();
        if(complain_content == ''||complain_content.length>100) {
            showDialog("<?php echo $lang['complain_content_error'];?>");
        }
        else {
        	if($("#add_form").valid()){
        		ajaxpost('add_form', '', '', 'onerror');
            }
        }
    }
}
function get_checked_items() {
    /* 获取选中的项 */
    var items = '';
    $('.checkitem:checked').each(function(){
        items += this.value + ',';
    });
    return items;
}
</script>
  <h3><?php echo $lang['complain_detail'];?></h3>
  <form action="index.php?act=member_complain&op=complain_save" method="post" id="add_form" enctype="multipart/form-data">
    <input name="input_appointment_id" type="hidden" value="<?php echo $output['appointment_info']['appointment_id'];?>" />
    <dl>
      <h4><?php echo $lang['complain_subject_select'];?></h4>
      <dd style="width:95%; padding-left:24px;">
        <?php foreach($output['subject_list'] as $subject) {?>
        <p>
          <input name="input_complain_subject" type="radio" value="<?php echo $subject['complain_subject_id'].','.$subject['complain_subject_content']?>" />
          <span class="mr30"><strong><?php echo $subject['complain_subject_content']?></strong></span><?php echo $subject['complain_subject_desc'];?> </p>
        <?php } ?>
      </dd>
    </dl>
    <dl>
      <h4><?php echo $lang['complain_doctors_select'];?></h4>
      <table class="appointment ncu-table-style">
        <thead>
          <tr>
            <th class="w30">&nbsp;</th>
            <th class="w70"></th>
            <th class="tl"><?php echo $lang['complain_doctors_message'];?></th>
            <th class="w200"><?php echo $lang['complain_text_num'];?></th>
            <th class="w200"><?php echo $lang['complain_text_price'];?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($output['appointment_doctors_list'] as $appointment_doctors) { ?>
          <tr>
            <td class="sep-row" colspan="19"></td>
          </tr>
          <tr>
            <th colspan="21"><span class="ml10"><?php echo $lang['complain_text_problem'].$lang['nc_colon'];?>
              <input type="text" name="input_doctors_problem[<?php echo $appointment_doctors['rec_id'];?>]" maxlength="50" class="w400 text"/>
              </span> <span class="error">(<?php echo $lang['max_fifty_chars'];?>)</span></th>
          </tr>
          <tr>
            <td class="bdl"><input class="checkitem" name="input_doctors_check[<?php echo $appointment_doctors['rec_id'];?>]" type="checkbox" value="<?php echo $appointment_doctors['rec_id'];?>" /></td>
            <td><div class="doctors-pic-small"><span class="thumb size60"><i></i><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$appointment_doctors['doctors_id']));?>" target="_blank"> <img onload="javascript:DrawImage(this,60,60);"  src="<?php echo cthumb($appointment_doctors['doctors_image'], 60, $output['appointment_info']['clic_id']);?>" /> </a></span></div></td>
            <td class="tl"><dl class="doctors-name">
              <dt><a href="<?php echo urlclinic('doctors', 'index', array('doctors_id'=>$appointment_doctors['doctors_id']));?>" target="_blank"><?php echo $appointment_doctors['doctors_name'];?></a></dt>
              </td>
            <td class="bdl"><?php echo $appointment_doctors['doctors_num'];?></td>
            <td class="bdl bdr"><em class="doctors-price"><?php echo $appointment_doctors['doctors_price'];?></em></td>
          </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="20"></td>
          </tr>
        </tfoot>
      </table>
    </dl>
    <dl>
      <h4><?php echo $lang['complain_content'].$lang['nc_colon'];?></h4>
      <dd style="padding-left:24px;">
        <textarea name="input_complain_content" rows="3" class="w600" id="input_complain_content"></textarea>
      </dd>
    </dl>
    <dl>
      <h4><?php echo $lang['complain_evidence_upload'];?><span class="error">(<?php echo $lang['complain_pic_error'];?>)</h4>
      <dd class="upload-appeal-pic">
        <p>
          <input id="input_complain_pic1" name="input_complain_pic1" type="file" />
        </p>
        <p>
          <input id="input_complain_pic2" name="input_complain_pic2" type="file" />
        </p>
        <p>
          <input id="input_complain_pic3" name="input_complain_pic3" type="file" />
        </p>
      </dd>
    </dl>
    <dl class="tc">
      <input id="submit_button" type="button" class="submit" value="<?php echo $lang['complain_text_submit'];?>" >
    </dl>
  </form>

