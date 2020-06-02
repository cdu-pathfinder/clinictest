<?php defined('InclinicNC') or exit('Access Invalid!');?>
<?php if(!empty($_SESSION['member_id'])) { ?>
<script type="text/javascript">
$(document).ready(function(){
    //分享
    $("#btn_sns_share").click(function(){
        $("#div_sns_share").microclinic_form_show({width:480});
    });
    $("#div_sns_share").microclinic_form({title:'<?php echo $lang['microclinic_text_share'];?>'});
    $("#commend_message").microclinic_publish({
        button_item:'#btn_publish',
        allow_null:'true'
    },function(){
        $("#div_sns_share").hide();
        ajaxpost('share_form', '', '', 'onerror'); 
    });
});
</script>
<!-- 弹出层开始 -->
<div id="div_sns_share" style="display:none;">
    <form action="<?php echo MICROclinic_SITE_URL;?>/index.php?act=share&op=share_save&type=<?php echo $_GET['act'];?>" method="post" id="share_form" class="feededitor">
        <?php if($_GET['act'] == 'doctors') { ?>
        <input type="hidden" value="<?php echo $output['detail']['commend_id'];?>" name="share_id" id="share_id"></input>
        <div class="command-doctors">
            <div class="pic">
                <span class="thumb size100">
                    <i></i>
                <?php $image_url = cthumb($output['detail']['commend_doctors_image'], 60,$output['detail']['commend_doctors_clic_id']);?>
                <img src="<?php echo $image_url;?>" title="<?php echo $output['detail']['commend_doctors_name'];?>" alt="<?php echo $output['detail']['commend_doctors_name'];?>" /> </a>
                </span>
            </div>
            <div><?php echo $output['detail']['commend_doctors_name'];?></div>
        </div>
        <?php } ?>
        <?php if($_GET['act'] == 'personal') { ?>
        <input type="hidden" value="<?php echo $output['detail']['personal_id'];?>" name="share_id" id="share_id"></input>
        <div class="command-doctors">
            <div class="pic">
                <span class="thumb size100">
                    <i></i>
                    <?php $personal_image_array = getMicroclinicPersonalImageUrl($output['detail'],'tiny');?>
                    <img src="<?php echo $personal_image_array[0];?>" /> 
                </span>
            </div>
            <div><?php echo $output['detail']['clic_name'];?></div>
        </div>
        <?php } ?>
        <?php if($_GET['act'] == 'clic') { ?>
        <input type="hidden" value="<?php echo $output['detail']['microclinic_clic_id'];?>" name="share_id" id="share_id"></input>
        <div class="command-doctors">
            <div class="pic">
                <span class="thumb size100">
                    <i></i>
                        <img src="<?php echo getclicLogo($output['detail']['clic_label']);?>" alt="<?php echo $output['detail']['clic_name'];?>" />
                </span>
            </div>
            <div><?php echo $output['detail']['clic_name'];?></div>
        </div>
        <?php } ?>

        <dl class="share">
            <dt><?php echo $lang['microclinic_sns_share_title'];?></dt>
            <dd></dd>
            <textarea name="commend_message" id="commend_message" ></textarea>
        </dl>
        <div class="handle"><input id="btn_publish" type="button" value="<?php echo $lang['microclinic_text_share'];?>" /> <!-- 站外分享 -->
            <?php require('widget_share.php');?>
        </div>
    </form>
</div>
<!-- 弹出层结束 -->
<?php } else { ?>
<script type="text/javascript">
$(document).ready(function(){
    //分享
    $("#btn_sns_share").click(function(){
        var tooltips = $(this).parent().parent().find(".like_tooltips");
        tooltips.html('<?php echo $lang['no_login'];?>').show();
        setTimeout(function(){tooltips.hide()},2000);
    });
});
</script>
<?php } ?>


