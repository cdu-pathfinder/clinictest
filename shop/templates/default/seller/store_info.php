<?php defined('InShopNC') or exit('Access Invalid!');?>

        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
                <tr>
                    <th colspan="20">clinic and contact information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="w150">clinic name:</th>
                    <td colspan="20"><?php echo $output['joinin_detail']['company_name'];?></td>
                </tr>
                <tr>
                    <th>clinic location:</th>
                    <td><?php echo $output['joinin_detail']['company_address'];?></td>
                    <th>clinic address:</th>
                    <td colspan="20"><?php echo $output['joinin_detail']['company_address_detail'];?></td>
                </tr>
                <tr>
                    <th>clinic phone number</th>
                    <td><?php echo $output['joinin_detail']['company_phone'];?></td>
                    <th>Total number of employees:</th>
                    <td><?php echo $output['joinin_detail']['company_employee_count'];?>&nbsp;people</td>
                    <th>Registered capital:</th>
                    <td><?php echo $output['joinin_detail']['company_registered_capital'];?>&nbsp;Billion </td>
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
                    <th>Organization code</th>
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
                    <th colspan="20">Bank information:</th>
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
                    <th>Bank address:</th>
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
                    <th>Bank address:</th>
                    <td><?php echo $output['joinin_detail']['settlement_bank_address'];?></td>
                </tr>
            </tbody>

        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
                <tr>
                    <th colspan="20">Tax registration certificate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="w150">Tax registration certificate no. :</th>
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
        <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
            <thead>
                <tr>
                    <th colspan="20"> clinic operation information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="w150">clinic account:</th>
                    <td><?php echo $output['joinin_detail']['seller_name'];?></td>
                </tr>
                <tr>
                    <th class="w150">clinic name：</th>
                    <td><?php echo $output['joinin_detail']['store_name'];?></td>
                </tr>
                <tr>
                    <th class="w150">clinic level:</th>
                    <td><?php echo $output['store_grade_name'];?></td>
                </tr>
                <tr>
                    <th class="w150">clinic classification:</th>
                    <td><?php echo $output['store_class_name'];?></td>
                </tr>
                <tr>
                    <th>Business category:</th>
                    <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
                            <thead>
                                <tr>
                                    <th>category1</th>
                                    <th>category2</th>
                                    <th>category3</th>
                                    <th>proportion</th>
                                </tr>
                            </thead>
                            <?php if(!empty($output['store_bind_class_list']) && is_array($output['store_bind_class_list'])) {?>
                            <?php foreach($output['store_bind_class_list'] as $key=>$value) {?>
                                <tr>
                                    <td><?php echo $value['class_1_name'];?></td>
                                    <td><?php echo $value['class_2_name'];?></td>
                                    <td><?php echo $value['class_3_name'];?></td>
                                    <td><?php echo $value['commis_rate'];?>%</td>
                                </tr>
                            <?php } ?>
                            <?php } ?>
                            </tbody>
                    </table></td>
                </tr>
                <tr>
                    <th>Audit opinions:</th>
                    <td colspan="2"><?php echo $output['joinin_detail']['joinin_message'];?></td>
                </tr>
            </tbody>
        </table>

