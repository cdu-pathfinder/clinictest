<?php defined('InclinicNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            click: function(score) {
                $(this).find('[nctype="score"]').val(score);
            }
        });

        $('.raty-x2').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            starOff: 'star-off-x2.png',
            starOn: 'star-on-x2.png',
            width: 150,
            click: function(score) {
                $(this).find('[nctype="score"]').val(score);
            }
        });


        $('#btn_submit').on('click', function() {
			ajaxpost('evalform', '', '', 'onerror')
        });
    });
</script>

<div class="wrap-shadow">
  <div class="wrap-all ncu-appointment-view">
    <h2><?php echo $lang['member_evaluation_toevaluatedoctors'];?></h2>
    <form id="evalform" method="post" action="index.php?act=member_evaluate&op=add&appointment_id=<?php echo $_GET['appointment_id'];?>">
      <h3 class="mt20 mb10">商品评价</h3>
      <div class="ncm-notes">
        <ul>
          <li><?php echo $lang['member_evaluation_rule_1'];?></li>
          <li><?php echo $lang['member_evaluation_rule_3'];?></li>
          <li><?php echo $lang['member_evaluation_rule_4'];?></li>
        </ul>
      </div>
      <table class="ncu-table-style appointment deliver">
        <tbody>
          <tr>
            <th colspan="20"><span class="ml10"><?php echo $lang['member_evaluation_appointment_desc'];?></span><span class="fr mr20">
              <input type="checkbox" name="anony" checked>
              &nbsp;<?php echo $lang['member_evaluation_modtoanonymous'];?></span> </th>
          </tr>
          <?php if(!empty($output['appointment_doctors'])){?>
          <?php foreach($output['appointment_doctors'] as $doctors){?>
          <tr>
            <td class="bdl w10"></td>
            <td class="w70"><div class="doctors-pic-small"><span class="thumb size60"><i></i><a href="index.php?act=doctors&doctors_id=<?php echo $doctors['doctors_id']; ?>" target="_blank"><img src="<?php echo $doctors['doctors_image_url']; ?>" onload="javascript:DrawImage(this,60,60);" /></a></span></div></td>
            <td class="tl doctors-info"><dl>
                <dt><a href="index.php?act=doctors&doctors_id=<?php echo $doctors['doctors_id'];?>" target="_blank"><?php echo $doctors['doctors_name'];?></a></dt>
                <dd class="tr"><span class="price"><?php echo $doctors['doctors_price'];?></span>&nbsp;x&nbsp;<?php echo $doctors['doctors_num'];?></dd>
              </dl></td>
            <td class="bdr"><div class="ncgeval mb10">
              <div class="raty">
                <input nctype="score" name="doctors[<?php echo $doctors['doctors_id'];?>][score]" type="hidden">
              </div>
              <textarea name="doctors[<?php echo $doctors['doctors_id'];?>][comment]" cols="150" class="w400" maxlength="250"></textarea></td>
          </tr>
        </tbody>
        <?php }?>
        <?php }?>
        <tfoot>
          <tr>
            <td colspan="20"></td>
          </tr>
        </tfoot>
      </table>
      <h3>店铺信息及服务评价</h3>
      <div class="ncu-evaluation-clic">
      <div class="ncs-info">
        <div class="title">
          <h4><?php echo $output['clic_info']['clic_name']; ?></h4>
        </div>
        <div class="content">
          <dl class="all-rate">
            <dt>综合评分：</dt>
            <dd>
              <div class="rating"><span style="width: <?php echo $output['clic_info']['clic_credit_percent'];?>%"></span></div>
              <em><?php echo $output['clic_info']['clic_credit_average'];?></em>分</dd>
          </dl>
          <div class="detail-rate">
            <h5><strong><?php echo $lang['member_evaluation_clicevalstat'];?></strong>与行业相比</h5>
            <ul>
              <?php  foreach ($output['clic_info']['clic_credit'] as $value) {?>
              <li> <?php echo $value['text'];?><span class="credit"><?php echo $value['credit'];?> 分</span> <span class="<?php echo $value['percent_class'];?>"><i></i><?php echo $value['percent_text'];?><em><?php echo $value['percent'];?></em></span> </li>
              <?php } ?>
            </ul>
          </div>
          <?php if(!empty($output['clic_info']['clic_qq']) || !empty($output['clic_info']['clic_ww'])){?>
          <dl class="messenger">
            <dt>联系方式：</dt>
            <dd member_id="<?php echo $output['clic_info']['member_id'];?>">
              <?php if(!empty($output['clic_info']['clic_qq'])){?>
              <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['clic_info']['clic_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['clic_info']['clic_qq'];?>"><img bappointment="0" src="http://wpa.qq.com/pa?p=2:<?php echo $output['clic_info']['clic_qq'];?>:52" style=" vertical-align: middle;"/></a>
              <?php }?>
              <?php if(!empty($output['clic_info']['clic_ww'])){?>
              <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['clic_info']['clic_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" ><img bappointment="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $output['clic_info']['clic_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="<?php echo $lang['nc_message_me'];?>" style=" vertical-align: middle;"/></a>
              <?php }?>
            </dd>
          </dl>
          <?php } ?>
          <dl class="no-bappointment">
            <dt>公司名称：</dt>
            <dd><?php echo $output['clic_info']['clic_company_name'];?></dd>
          </dl>
        </div>
      </div>
      <div class="ncu-form-style">
        <h4>我对该店此次服务的评分</h4>
        <dl>
          <dt><?php echo $lang['member_evaluation_evalclic_type_1'].$lang['nc_colon'];?></dt>
          <dd style=" width:450px;">
            <div class="raty-x2">
              <input nctype="score" name="clic_desccredit" type="hidden">
            </div>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['member_evaluation_evalclic_type_2'].$lang['nc_colon'];?></dt>
          <dd style=" width:450px;">
            <div class="raty-x2">
              <input nctype="score" name="clic_servicecredit" type="hidden">
            </div>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['member_evaluation_evalclic_type_3'].$lang['nc_colon'];?></dt>
          <dd style=" width:450px;">
            <div class="raty-x2">
              <input nctype="score" name="clic_deliverycredit" type="hidden">
            </div>
          </dd>
        </dl>
      </div>
      <div class="clear"></div>
       <div class="mt30 tc"><input id="btn_submit" type="button" class="submit" value="<?php echo $lang['member_evaluation_submit'];?>"/>
      </div>
    </form>
  </div>
</div>
