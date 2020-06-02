<?php defined('InclinicNC') or exit('Access Invalid!');?>

    <ul class="tabs-nav">
                  <?php if (!empty($output['code_sale_list']['code_info']) && is_array($output['code_sale_list']['code_info'])) { 
                    $i = 0;
                    ?>
                  <?php foreach ($output['code_sale_list']['code_info'] as $key => $val) { 
                    $i++;
                    ?>
        <li class="<?php echo $i==1 ? 'tabs-selected':'';?>"><i class="arrow"></i><h3><?php echo $val['recommend']['name'];?></h3></li>
                  <?php } ?>
                  <?php } ?>
    </ul>
                  <?php if (!empty($output['code_sale_list']['code_info']) && is_array($output['code_sale_list']['code_info'])) { 
                    $i = 0;
                    ?>
                  <?php foreach ($output['code_sale_list']['code_info'] as $key => $val) { 
                    $i++;
                    ?>
                          <?php if(!empty($val['doctors_list']) && is_array($val['doctors_list'])) { ?>
                                  <div class="tabs-panel sale-doctors-list <?php echo $i==1 ? '':'tabs-hide';?>">
                                    <ul>
                                    <?php foreach($val['doctors_list'] as $k => $v){ ?>
                                      <li>
                                        <dl>
                                          <dt class="doctors-name"><a target="_blank" href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$v['doctors_id'])); ?>" title="<?php echo $v['doctors_name']; ?>">
                                          	<?php echo $v['doctors_name']; ?></a></dt>
                                          <dd class="doctors-thumb">
                                          	<a target="_blank" href="<?php echo urlclinic('doctors','index',array('doctors_id'=>$v['doctors_id'])); ?>">
                                          	<img src="<?php echo strpos($v['doctors_pic'],'http')===0 ? $v['doctors_pic']:UPLOAD_SITE_URL."/".$v['doctors_pic'];?>" alt="<?php echo $v['doctors_name']; ?>" />
                                          	</a></dd>
                                          <dd class="doctors-price"><?php echo $lang['index_index_clic_doctors_price'].$lang['nc_colon'];?><em><?php echo ncPriceFormatForList($v['doctors_price']); ?></em></dd>
                                        </dl>
                                      </li>
                                    <?php } ?>
                                    </ul>
                                  </div>
                          <?php } ?>
                  <?php } ?>
                  <?php } ?>