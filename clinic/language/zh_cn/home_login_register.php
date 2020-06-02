<?php
defined('InclinicNC') or exit('Access Invalid!');
/**
 *  登录-公共语言	
 */

/**
 * 登录-注册
 */
$lang['login_register_input_username']		= "The username cannot be empty";
$lang['login_register_username_range']		= "The user name must be between 3 and 15 characters";
$lang['login_register_username_lettersonly']= "The user name cannot contain sensitive characters";
$lang['login_register_username_exists']		= "The user name already exists";
$lang['login_register_input_password']		= "The password cannot be empty";
$lang['login_register_password_range']		= "Password length should be between 6 and 20 characters";
$lang['login_register_input_password_again']= "You must confirm your password again";
$lang['login_register_password_not_same']	= "The passwords entered do not match";
$lang['login_register_input_email']			= "Your email address cannot be empty";
$lang['login_register_invalid_email']		= "This is not a valid email address";
$lang['login_register_email_exists']		= "The email already exists";
$lang['login_register_input_text_in_image']	= "Please enter a verification code";
$lang['login_register_code_wrong']			= "The captcha is incorrect";
$lang['login_register_must_agree']			= "Please read and agree to this agreement";
$lang['login_register_join_us']				= "User registration";
$lang['login_register_input_info']			= "Fill in the user registration information";
$lang['login_register_username']			= "user name";
$lang['login_register_username_to_login']	= "3-20 characters，can include, English and numerals and “_”、“-”";
$lang['login_register_pwd']					= "password";
$lang['login_register_password_to_login']	= "Your login password";
$lang['login_register_password_to_login']	= "6-16 characters, can be composed of English, numerals and punctuation";
$lang['login_register_ensure_password']		= "Confirm pwd";
$lang['login_register_input_password_again']= "Please enter your password again";
$lang['login_register_email']				= "email";
$lang['login_register_input_valid_email']	= "Please enter your usual email address, which will be used to retrieve your password, accept appointment notification, etc";
$lang['login_register_code']				= "Verification";
$lang['login_register_click_to_change_code']= "change it";
$lang['login_register_input_code']			= "Please enter a captcha, case - insensitive";
$lang['login_register_agreed']				= "agree";
$lang['login_register_agreement']			= "the agreement";
$lang['login_register_regist_now']			= "Register now";
$lang['login_register_enter_now']			= "Confirm to submit";
$lang['login_register_connect_now']			= "Binding account";
$lang['login_register_after_regist']		= "After registration you can";
$lang['login_register_buy_info']			= "make a appointment";
$lang['login_register_collect_info']		= "Collect clinic";
$lang['login_register_honest_info']			= "Safe transaction";
$lang['login_register_openclic_info']		= "Apply for a clinic";
$lang['login_register_sns_info']			= " share";
$lang['login_register_talk_info']			= "service evaluation";

$lang['login_register_already_have_account']= "If you are a user of this site";
$lang['login_register_login_now_1']			= "I have registered an account,";
$lang['login_register_login_now_2']			= "login";
$lang['login_register_login_now_3']			= "or";
$lang['login_register_login_forget']		= "Retrieve password?";
/**
 * 登录-用户保存
 */
$lang['login_usersave_login_usersave_username_isnull']	= "The username cannot be empty";
$lang['login_usersave_password_isnull']			= "The password cannot be empty";
$lang['login_usersave_password_not_the_same']	= "The password is not the same as the confirmation password, please re-enter from";
$lang['login_usersave_wrong_format_email']		= "The e-mail format is incorrect, please fill in again";
$lang['login_usersave_code_isnull']				= "The captcha cannot be null";
$lang['login_usersave_wrong_code']				= "Verification code error";
$lang['login_usersave_you_must_agree']			= "You must agree to the terms of service to register";
$lang['login_usersave_your_username_exists']	= "The user name you filled in already exists, please choose another user name to fill in";
$lang['login_usersave_your_email_exists']		= "The email address you filled in already exists, please choose another email address to fill in";
$lang['login_usersave_regist_success']			= "Registered successfully";
$lang['login_usersave_regist_success_ajax'] 	= 'Welcome tosite_nameI suggest you to improve the information as soon as possible, I wish you a happy Booking';
$lang['login_usersave_regist_fail']				= "Registration failed";
/**
 * 密码找回
 */
$lang['login_index_find_password']				    = 'Forgot password';
$lang['login_password_you_account']	= 'Login account';
$lang['login_password_you_email']	= 'email';
$lang['login_password_change_code']	= 'I cannot read it. will change it';
$lang['login_password_submit']		= 'Submit back';
$lang['login_password_input_email']	= 'Your email address cannot be empty';
$lang['login_password_wrong_email']	= 'Incorrect email address';
/**
 * 找回处理
 */
$lang['login_password_enter_find']			= 'About to enter the password recovery page...';
$lang['login_password_input_username']		= 'Please enter a login name';
$lang['login_password_username_not_exists']	= 'The login name does not exist';
$lang['login_password_input_email']			= 'Please enter your email address';
$lang['login_password_email_not_exists']	= 'Email address error';
$lang['login_password_email_fail']			= 'The sending time of the mail has expired. Please reapply';
$lang['login_password_email_success']		= 'The email has been sent out, please check';
?>