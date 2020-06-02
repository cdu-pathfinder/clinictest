<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();

        $('#btn_fail').on('click', function() {
            if($('#joinin_message').val() == '') {
                $('#validation_message').text('请输入审核意见');
                $('#validation_message').show();
                return false;
            } else {
                $('#validation_message').hide();
            }
            if(confirm('确认拒绝申请？')) {
                $('#verify_type').val('fail');
                $('#form_store_verify').submit();
            }
        });
        $('#btn_pass').on('click', function() {
            var valid = true;
            $('[nctype="commis_rate"]').each(function(commis_rate) {
                rate = $(this).val();
                if(rate == '') {
                    valid = false;
                    return false;
                }

                var rate = Number($(this).val());
                if(isNaN(rate) || rate < 0 || rate >= 100) {
                    valid = false;
                    return false;
                }
            });
            if(valid) {
                $('#validation_message').hide();
                if(confirm('确认通过申请？')) {
                    $('#verify_type').val('pass');
                    $('#form_store_verify').submit();
                }
            } else {
                $('#validation_message').text('请正确填写分佣比例');
                $('#validation_message').show();
            }
        });
    });
</script>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['store'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=store&op=store"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?act=store&op=store_joinin" ><span><?php echo $lang['pending'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $output['joinin_detail_title'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">Company and contact information</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">Company name:</th>
        <td colspan="20"><?php echo $output['joinin_detail']['company_name'];?></td>
      </tr>
      <tr>
        <th>Company location:</th>
        <td><?php echo $output['joinin_detail']['company_address'];?></td>
        <th>Company address:</th>
        <td colspan="20"><?php echo $output['joinin_detail']['company_address_detail'];?></td>
      </tr>
      <tr>
        <th>Company telephone:</th>
        <td><?php echo $output['joinin_detail']['company_phone'];?></td>
        <th>number of employees:</th>
        <td><?php echo $output['joinin_detail']['company_employee_count'];?>&nbsp;people</td>
        <th>Registered capital:</th>
        <td><?php echo $output['joinin_detail']['company_registered_capital'];?>&nbsp;billion </td>
      </tr>
      <tr>
        <th>Contact name:</th>
        <td><?php echo $output['joinin_detail']['contacts_name'];?></td>
        <th>Contact number:</th>
        <td><?php echo $output['joinin_detail']['contacts_phone'];?></td>
        <th>Email address:</th>
        <td><?php echo $output['joinin_detail']['contacts_email'];?></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">Business license information (copy)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">Business license no. :</th>
        <td><?php echo $output['joinin_detail']['business_licence_number'];?></td></tr><tr>
      
        <th>Location of business license:</th>
        <td><?php echo $output['joinin_detail']['business_licence_address'];?></td></tr><tr>
      
        <th>Validity of business license:</th>
        <td><?php echo $output['joinin_detail']['business_licence_start'];?> - <?php echo $output['joinin_detail']['business_licence_end'];?></td>
      </tr>
      <tr>
        <th>Legal business scope:</th>
        <td colspan="20"><?php echo $output['joinin_detail']['business_sphere'];?></td>
      </tr>
      <tr>
        <th>Business license no.<br />
Electronic version:</th>
        <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">Organization code certificate</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Organization code:</th>
        <td colspan="20"><?php echo $output['joinin_detail']['organization_code'];?></td>
      </tr>
      <tr>
        <th>Organization code certificate<br/>          Electronic version:</th>
        <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">General taxpayer certificate:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>General taxpayer certificate:</th>
        <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['general_taxpayer']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['general_taxpayer']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">Bank information：</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">Bank account name:</th>
        <td><?php echo $output['joinin_detail']['bank_account_name'];?></td>
      </tr><tr>
        <th>Company bank account:</th>
        <td><?php echo $output['joinin_detail']['bank_account_number'];?></td></tr>
      <tr>
        <th>Bank branch name:</th>
        <td><?php echo $output['joinin_detail']['bank_name'];?></td>
      </tr>
      <tr>
        <th>Branch no. :</th>
        <td><?php echo $output['joinin_detail']['bank_code'];?></td>
      </tr><tr>
        <th>Bank is located:</th>
        <td colspan="20"><?php echo $output['joinin_detail']['bank_address'];?></td>
      </tr>
      <tr>
        <th>Bank license<br/>Electronic version:</th>
        <td colspan="20"><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['bank_licence_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['bank_licence_electronic']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
    
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">Settlement account information:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">Bank account name:</th>
        <td><?php echo $output['joinin_detail']['settlement_bank_account_name'];?></td>
      </tr>
      <tr>
        <th>Company bank account:</th>
        <td><?php echo $output['joinin_detail']['settlement_bank_account_number'];?></td>
      </tr>
      <tr>
        <th>Bank branch name:</th>
        <td><?php echo $output['joinin_detail']['settlement_bank_name'];?></td>
      </tr>
      <tr>
        <th>Branch no. :</th>
        <td><?php echo $output['joinin_detail']['settlement_bank_code'];?></td>
      </tr>
      <tr>
        <th>Bank is located:</th>
        <td><?php echo $output['joinin_detail']['settlement_bank_address'];?></td>
      </tr>
    </tbody>
    
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">ax registration certificate</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">Tax registration no. :</th>
        <td><?php echo $output['joinin_detail']['tax_registration_certificate'];?></td>
      </tr>
      <tr>
        <th>Taxpayer identification no. :</th>
        <td><?php echo $output['joinin_detail']['taxpayer_id'];?></td>
      </tr>
      <tr>
        <th>Tax registration number<br />
Electronic version:</th>
        <td><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']);?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  <form id="form_store_verify" action="index.php?act=store&op=store_joinin_verify" method="post">
    <input id="verify_type" name="verify_type" type="hidden" />
    <input name="member_id" type="hidden" value="<?php echo $output['joinin_detail']['member_id'];?>" />
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="20">clinic operation information</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">admin account:</th>
          <td><?php echo $output['joinin_detail']['seller_name'];?></td>
        </tr>
        <tr>
          <th class="w150">clinic name:</th>
          <td><?php echo $output['joinin_detail']['store_name'];?></td>
        </tr>
        <tr>
          <th>clinic level：</th>
          <td><?php echo $output['joinin_detail']['sg_name'];?></td>
        </tr>
        <tr>
          <th>clinic classification:</th>
          <td><?php echo $output['joinin_detail']['sc_name'];?></td>
        </tr>
        <tr>
          <th>Business category:</th>
          <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
              <thead>
                <tr>
                  <th>Category 1</th>
                  <th>Category 2</th>
                  <th>Category 3</th>
                  <th>proportion</th>
                </tr>
              </thead>
              <tbody>
                <?php $store_class_names = unserialize($output['joinin_detail']['store_class_names']);?>
                <?php if(!empty($store_class_names) && is_array($store_class_names)) {?>
                <?php $store_class_commis_rates = explode(',', $output['joinin_detail']['store_class_commis_rates']);?>
                <?php for($i=0, $length = count($store_class_names); $i < $length; $i++) {?>
                <?php list($class1, $class2, $class3) = explode(',', $store_class_names[$i]);?>
                <tr>
                  <td><?php echo $class1;?></td>
                  <td><?php echo $class2;?></td>
                  <td><?php echo $class3;?></td>
                  <td>
                <?php if(intval($output['joinin_detail']['joinin_state']) === 10) {?>
                  <input type="text" nctype="commis_rate" value="<?php echo $store_class_commis_rates[$i];?>" name="commis_rate[]" class="w100" />%
                <?php }else { ?>
                <?php echo $store_class_commis_rates[$i];?>
                <?php } ?>
                </td>
                </tr>
                <?php } ?>
                <?php } ?>
                </tbody>
        </table></td>
    </tr>
    <?php if(in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_PAY, STORE_JOIN_STATE_FINAL))) {?>
    <tr>
        <th>Payment voucher:</th>
        <td><a nctype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['paying_money_certificate']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['paying_money_certificate']);?>" alt="" /> </a></td>
    </tr>
    <tr>
        <th>Payment voucher description:</th>
        <td><?php echo $output['joinin_detail']['paying_money_certificate_explain'];?></td>
    </tr>
    <?php } ?>
   <?php if(in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) { ?>
    <tr>
        <th>Audit opinions:</th>
        <td colspan="2"><textarea id="joinin_message" name="joinin_message"></textarea></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
   <?php if(in_array(intval($output['joinin_detail']['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) { ?>
    <div id="validation_message" style="color:red;display:none;"></div>
    <div><a id="btn_fail" class="btn" href="JavaScript:void(0);"><span>refuse</span></a> <a id="btn_pass" class="btn" href="JavaScript:void(0);"><span>pass</span></a></div>
    <?php } ?>
  </form>
</div>
