<?php defined('InclinicNC') or exit('Access Invalid!');?>
<?php if(!empty($output['clic_list']) && is_array($output['clic_list'])){ ?>
<div class="clic-select-box">
    <div class="arrow"></div>
    <ul id="clic_search_list" class="clic-search-list">
        <?php foreach($output['clic_list'] as $value){ ?>
        <li>
        <dl class="clic-info">
            <dt class="clic-name">
            <a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$value['clic_id']));?>" target="_blank" >
                <?php echo $value['clic_name'];?>
            </a>
            </dt>
            <dd class="clic-logo">
            <a href="<?php echo urlclinic('show_clic', 'index', array('clic_id'=>$value['clic_id']));?>" target="_blank" >
                <img src="<?php echo getclicLogo($value['clic_label']);?>" />
            </a>
            </dd>
            <dd class="member-name">店主：<?php echo $value['member_name'];?></dd>
            <dd nctype="btn_clic_select" class="handle-button" title="<?php echo $lang['cms_text_add'];?>"></dd>
        </dl>
        </li>
        <?php } ?>
    </ul>
    <div class="pagination"><?php echo $output['show_page'];?></div>
</div>
<?php }else { ?>
<div class="no-record"><?php echo $lang['no_record'];?></div>
<?php } ?>
