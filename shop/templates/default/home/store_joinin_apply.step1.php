<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript">
$(document).ready(function(){

    $('#company_address').nc_region();
    $('#business_licence_address').nc_region();
    
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();

    $('#btn_apply_agreement_next').on('click', function() {
        if($('#input_apply_agreement').prop('checked')) {
            $('#apply_agreement').hide();
            $('#apply_company_info').show();
        } else {
            alert('Please read and agree to the agreement');
        }
    });

    $('#form_company_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules : {
            company_name: {
                required: true,
                maxlength: 50 
            },
            company_address: {
                required: true,
                maxlength: 50 
            },
            company_address_detail: {
                required: true,
                maxlength: 50 
            },
            company_phone: {
                required: true,
                maxlength: 20 
            },
            company_employee_count: {
                required: true,
                digits: true 
            },
            company_registered_capital: {
                required: true,
                digits: true 
            },
            contacts_name: {
                required: true,
                maxlength: 20 
            },
            contacts_phone: {
                required: true,
                maxlength: 20 
            },
            contacts_email: {
                required: true,
                email: true 
            },
            business_licence_number: {
                required: true,
                maxlength: 20
            },
            business_licence_address: {
                required: true,
                maxlength: 50
            },
            business_licence_start: {
                required: true
            },
            business_licence_end: {
                required: true
            },
            business_sphere: {
                required: true,
                maxlength: 500
            },
            business_licence_number_electronic: {
                required: true
            },
            organization_code: {
                required: true,
                maxlength: 20
            },
            organization_code_electronic: {
                required: true
            }
        },
        messages : {
            company_name: {
                required: 'Please enter the company name',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            company_address: {
                required: 'Please select the area',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            company_address_detail: {
                required: 'Please enter the company address',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            company_phone: {
                required: 'Please enter the company phone',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            company_employee_count: {
                required: 'Please enter the total number of employees',
                digits: 'Must be a number'
            },
            company_registered_capital: {
                required: 'Please enter the capital',
                digits: 'Must be a number'
            },
            contacts_name: {
                required: 'Please enter the contact name',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            contacts_phone: {
                required: 'Please enter the contact number',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            contacts_email: {
                required: 'Please enter  usual email',
                email: 'Please fill in the correct email'
            },
            business_licence_number: {
                required: 'Please enter the business license number',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            business_licence_address: {
                required: 'Please select the location of the business license',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            business_licence_start: {
                required: 'Please select the effective date'
            },
            business_licence_end: {
                required: 'Please select the end date'
            },
            business_sphere: {
                required: 'Please fill in the business license and legal business scope',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            business_licence_number_electronic: {
                required: 'Please upload the electronic version of the business license'
            },
            organization_code: {
                required: 'Please fill in the organization code',
                maxlength: jQuery.validator.format("less than {0} words")
            },
            organization_code_electronic: {
                required: 'Please upload the electronic version of the organization code certificate'
            }
        }
    });

    $('#btn_apply_company_next').on('click', function() {
        if($('#form_company_info').valid()) {
            $('#form_company_info').submit();
        }
    });
});
</script>

<!-- 公司信息 -->

<div id="apply_company_info" class="apply-company-info">
  <div class="note"><i></i>The following electronic qualification documents need to be uploaded only support JPG\GIF\PNG format pictures, please control the size within 1M。</div>
  <form id="form_company_info" action="index.php?act=store_joinin&op=step2" method="post" enctype="multipart/form-data" >
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="2">Bank is located:</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>company name:</th>
          <td><input name="company_name" type="text" class="w200"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>location：</th>
          <td><input id="company_address" name="company_address" type="hidden" value=""/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>detailed address：</th>
          <td><input name="company_address_detail" type="text" class="w200">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>company phone：</th>
          <td><input name="company_phone" type="text" class="w100">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>employee count:</th>
          <td><input name="company_employee_count" type="text" class="w50"/>
            &nbsp;people <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>company capital:</th>
          <td><input name="company_registered_capital" type="text" class="w50">
            &nbsp;billion<span></span></td>
        </tr>
        <tr>
          <th><i>*</i>contacts name:</th>
          <td><input name="contacts_name" type="text" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>contacts phone:</th>
          <td><input name="contacts_phone" type="text" class="w100" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>contacts email:</th>
          <td><input name="contacts_email" type="text" class="w200" />
            <span></span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">Business license information (copy）</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>Business license No.:</th>
          <td><input name="business_licence_number" type="text" class="w200" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>Location of BL:</th>
          <td><input id="business_licence_address" name="business_licence_address" type="hidden" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>Validity of BL:</th>
          <td><input id="business_licence_start" name="business_licence_start" type="text" class="w90" />
            <span></span>-
            <input id="business_licence_end" name="business_licence_end" type="text" class="w90" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>Legal business scope:</th>
          <td><textarea name="business_sphere" rows="3" class="w200"></textarea>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>Electronic version:</th>
          <td><input name="business_licence_number_electronic" type="file" class="w200" />
            <span class="block">Please make sure the picture is clear, the text is legible</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">Organization code certificate:</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i>Organization code:</th>
          <td><input name="organization_code" type="text" class="w200"/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>OCC Electronic version:</th>
          <td><input name="organization_code_electronic" type="file" />
            <span class="block">Please make sure the picture is clear, the text is legible</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20">General taxpayer certificate:<em>Tip：this is required if general taxpayer.</em></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150"><i>*</i>Taxpayer certificate:</th>
          <td><input name="general_taxpayer" type="file" />
            <span class="block">Please make sure the picture is clear, the text is legible</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
  </form>
  <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">next</a></div>
</div>
