<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * 設置 語言包
 */

$lang['update_cycle_only_number'] = '更新周期 必須為數字類型';
$lang['user_info_del_ok']     = '清除成功！';
$lang['user_info_del_fail']   = '清除失敗！';
$lang['test_email']           = '測試郵件';
$lang['this_is_to']           = '這是一封來自';
$lang['test_email_set_ok']    = '的測試郵件，證明您所郵件設置正常';
$lang['test_email_send_fail'] = '測試郵件發送失敗，請重新配置郵件伺服器';
$lang['test_email_send_ok']   = '測試郵件發送成功';

$lang['web_set']        = '站點設置';
$lang['account_syn']     = '賬號同步';
$lang['sys_set']        = '系統設置';
$lang['basic_info']     = '基本信息';
$lang['upload_set']		= '上傳設置';
$lang['default_thumb']	= '預設圖片';
$lang['upload_set_ftp'] = '遠程圖片';
$lang['upload_param']	= '上傳參數';
$lang['point_set']		= '積分設置';
$lang['user_auth'] 		= '用戶權限';
$lang['dis_dump']   	= '防灌水設置';
$lang['open_store_set'] = '店舖設置';
$lang['credit']         = '信用評價';
$lang['domain']         = '二級域名';
$lang['ucenter_integration']   = '會員整合';
$lang['goldSettings']   = '金幣設置';
$lang['ztcSettings']   = '直通車';
$lang['qqSettings']   = 'QQ互聯';
$lang['qqSettings_notice'] = '開啟後可使用QQ賬號登錄商城系統';
$lang['sinaSettings']   = '新浪微博';
$lang['loginSettings']   = '登錄主題圖片';
$lang['login_set_help1']   	= '設置登錄頁左側主題圖片';
$lang['login_click_open']   = '點擊打開';
$lang['ftp_set_help1']   	= 'FTP設置測試通過後，請更改data/config/confi.ini.php中 $config[\'thumb\'][\'save_type\'] = 3';
$lang['ftp_set_help2']   	= '如果伺服器已配置NFS等網絡檔案系統，建議關閉FTP存儲，使用NFS等檔案系統實際圖片共享。';
$lang['pointssettings']   = '積分規則';

$lang['email_set']		= '郵件設置';
$lang['email_tpl']		= '郵件模板';
$lang['message_tpl']	= '站內信模板';
$lang['message_tpl_state']	= '消息模板狀態更改';

$lang['time_zone_set']         = '預設時區';
$lang['set_sys_use_time_zone'] = '設置系統使用的時區，中國為';
$lang['default_product_pic']   = '預設商品圖片';
$lang['default_store_logo']    = '預設店舖標誌';
$lang['default_user_pic']      = '預設會員頭像';
$lang['flow_static_code']      = '第三方流量統計代碼';
$lang['flow_static_code_notice']     = '前台頁面底部可以顯示第三方統計';
$lang['image_dir_type']		= '圖片存放類型';
$lang['image_dir_type_0']	= '按照檔案名存放 (例:/店舖id/圖片)';
$lang['image_dir_type_1']	= '按照年份存放 (例:/店舖id/年/圖片)';
$lang['image_dir_type_2']	= '按照年月存放 (例:/店舖id/年/月/圖片)';
$lang['image_dir_type_3']	= '按照年月日存放 (例:/店舖id/年/月/日/圖片)';
$lang['image_width']	= '寬';
$lang['image_height']	= '高';
$lang['image_typeerror']	= '上傳圖片格式不正確';
$lang['image_thumb_quality']	= '壓縮質量';
$lang['image_thumb_quality_tips'] = '設置圖片附件縮略圖的質量參數，範圍為 0～100 的整數，數值越大圖片效果越好，但尺寸也越大，推薦 75';
$lang['image_thumb_tool']	= '壓縮工具';
$lang['image_thumb_tool_tips']	= '商城預設使用GD庫生成縮略圖，GD使用廣泛但占用系統資源較多，ImageMagick速度快系統資源占用少，但需要伺服器有執行命令行命令的權限。可到 http://www.imagemagick.org 下載安裝，如改用ImageMagick，可編輯data/config/config.ini.php檔案(用EditPlus): <br/>$config[\'thumb\'][\'cut_type\'] = \'im\';<br/>$config[\'thumb\'][\'impath\'] = \'ImageMagick下convert工具所在路徑\'; 如：<br/>$config[\'thumb\'][\'impath\'] = \'/usr/local/ImageMagick/bin\';';

$lang['allowed_visitors_consult']           = '允許遊客諮詢';
$lang['allowed_visitors_consult_notice']    = '允許遊客在商品的詳細展示頁面，對當前商品進行諮詢';
$lang['open_pseudo_static']                 = '啟用偽靜態';
$lang['open_kefu'] = '啟用在綫客服';
$lang['open_kefu_yes'] = '是';
$lang['open_kefu_no'] = '否';
$lang['promotion_allow'] = '商品促銷';
$lang['promotion_notice'] = '啟用商品促銷功能後，賣家可以通過限時打折和滿即送活動，對店舖商品進行促銷';
$lang['open_pointshop_isuse'] = '積分中心';
$lang['open_pointshop_isuse_notice'] = '積分中心和積分同時啟用後，網站將增加積分中心頻道';
$lang['open_pointprod_isuse'] = '積分兌換';
$lang['open_pointprod_isuse_notice'] = '積分兌換、積分功能以及積分中心啟用後，平台發佈禮品，會員的積分在達到要求時可以在積分中心中兌換禮品';
$lang['points_isuse_notice'] = '積分系統啟用後，可設置會員的註冊、登錄、購買商品送一定的積分';
$lang['open_predeposit_isuse'] = '預存款';
$lang['open_predeposit_isuse_notice'] = '預存款啟用後，會員可以給自己帳戶充值進行交易，加強平台對資金的管理';
$lang['voucher_allow'] = '代金券';
$lang['voucher_allow_notice'] = '代金券功能、積分功能、金幣功能、積分中心啟用後，賣家可以申請代金券活動；會員積分達到要求時可以在積分中心兌換代金券；<br>擁有代金券的會員可在代金券所屬店舖內購買商品時，選擇使用而得到優惠';
$lang['groupbuy_allow'] = '團購';
$lang['groupbuy_isuse_notice']    = '團購功能啟用後，平台可以發佈團購活動，商家通過活動發佈團購商品，進行促銷';
$lang['complain_time_limit'] = '投訴時效';
$lang['complain_time_limit_desc'] = '單位為天，訂單完成後開始計算，多少天內可以發起投訴，根據具體情況賣家和買家都可發起投訴';
$lang['open_rewrite_tips']                  = '啟用偽靜態可以提高搜索引擎抓取<br/>如果您使用的是Apache伺服器，啟用前，請先確保已開啟apache伺服器的rewrite.module功能模組，並將商城目錄下的"htaccess.txt"檔案重命名為".htaccess"。<br/>如果您使用的是IIS伺服器，啟用前，請確保已正確安裝ISAPI_Rewrite，並將商城目錄下的"htaccess.txt"檔案重命名為".htaccess"，<br/>如果您使用的是Nginx伺服器，啟用前，請將htaccess.txt內的nginx版偽靜態規則複製到nginx.conf中，然後重啟nginx。';
$lang['update_cycle_hour']                  = '更新周期(小時)';
$lang['web_name']                           = '網站名稱';
$lang['web_name_notice']					= '網站名稱，將顯示在前台頂部歡迎信息等位置';
$lang['site_description']                   = '網站描述';
$lang['site_description_notice']			= '網站描述，出現在前台頁面頭部的 Meta 標籤中，用於記錄該頁面的概要與描述';
$lang['site_keyword']                       = '網站關鍵字';
$lang['site_keyword_notice']                = '網站關鍵字，出現在前台頁面頭部的 Meta 標籤中，用於記錄該頁面的關鍵字，多個關鍵字間請用半形逗號 "," 隔開';
$lang['site_logo']                          = '網站Logo';
$lang['member_logo']                        = '會員中心Logo';
$lang['member_logo_notice']                 = '預設為網站Logo，在會員中心頭部顯示，建議使用180px * 50px';
$lang['icp_number']                         = 'ICP證書號';
$lang['icp_number_notice']                  = '前台頁面底部可以顯示 ICP 備案信息，如果網站已備案，在此輸入你的授權碼，它將顯示在前台頁面底部，如果沒有請留空';
$lang['site_phone']                         = '平台客服聯繫電話';
$lang['site_phone_notice']                  = '前台賣家中心頁面右下側可以顯示，方便賣家遇到問題時諮詢，多個請用半形逗號 "," 隔開';
$lang['site_email']                         = '電子郵件';
$lang['site_email_notice']                  = '前台賣家中心頁面右下側可以顯示，方便賣家遇到問題時諮詢';
$lang['site_state']                         = '站點狀態';
$lang['site_state_notice']                  = '可暫時將站點關閉，其他人無法訪問，但不影響管理員訪問後台';
$lang['closed_reason']                      = '關閉原因';
$lang['closed_reason_notice']               = '當網站處于關閉狀態時，關閉原因將顯示在前台';
$lang['hot_search']                         = '熱門搜索';
$lang['field_notice']                       = '熱門搜索，將顯示在前台搜索框下面，前台點擊時直接作為關鍵詞進行搜索，多個關鍵詞間請用半形逗號 "," 隔開';
$lang['email_type_open']                    = '郵件功能開啟';
$lang['email_type']                         = '郵件發送方式';
$lang['use_other_smtp_service']             = '採用其他的SMTP服務';
$lang['use_server_mail_service']            = '採用伺服器內置的Mail服務';
$lang['if_choose_server_mail_no_input_follow'] = '如果您選擇伺服器內置方式則無須填寫以下選項';
$lang['smtp_server']             = 'SMTP 伺服器';
$lang['set_smtp_server_address'] = '設置 SMTP 伺服器的地址，如 smtp.163.com';
$lang['smtp_port']               = 'SMTP 連接埠';
$lang['set_smtp_port']           = '設置 SMTP 伺服器的連接埠，預設為 25';
$lang['sender_mail_address']     = '發信人郵件地址';
$lang['if_smtp_authentication']  = '使用SMTP協議發送的郵件地址，如 shopnc@163.com';
$lang['smtp_user_name']          = 'SMTP 身份驗證用戶名';
$lang['smtp_user_name_tip']      = '如 shopnc';
$lang['smtp_user_pwd']           = 'SMTP 身份驗證密碼';
$lang['smtp_user_pwd_tip']       = 'shopnc@163.com郵件的密碼，如 123456';
$lang['test_mail_address']       = '測試接收的郵件地址';
$lang['test']                    = '測試';
$lang['open_checkcode']          = '使用驗證碼';
$lang['front_login']             = '前台登錄';
$lang['front_goodsqa']           = '商品諮詢';
$lang['front_regist']            = '前台註冊';
$lang['allow_open_store']        = '開店申請';
$lang['setting_store_creditrule']        = '店舖信用';
$lang['setting_store_creditrule_grade']        = '等級';
$lang['setting_store_creditrule_gradenum']        = '信用介於';

$lang['user_info_del']           = '會員信息清除';
$lang['click_clear']             = '點擊清除';
$lang['ucenter_qq_ucenter_tips']    	 	= '由於社區系統帳號的註冊機制問題（郵箱必填，QQ互聯介面不開放郵箱信息），開啟會員整合將會自動關閉QQ互聯功能';
$lang['user_info_clear']         = '會員信息清除，其擁有的店舖和商品也會被清除，您確定要清除嗎?';
$lang['first_integration']       = '<span>如果是第一次整合，</span><span style=" color: #F00;">需要</span><span style=" color: #F00;">清除商城會員</span><span>信息，清除前建議您備份數據</span>';
$lang['click_bak']               = '點擊備份';
$lang['ucenter_type']            = '請選擇整合的社區系統';
$lang['ucenter_uc_discuz']       = 'Discuz';
$lang['ucenter_uc_phpwind']      = 'PHPWind';
$lang['ucenter_application_id']  = '應用ID';
$lang['ucenter_help_url']		 = '點擊查看會員整合教程';
$lang['ucenter_address']         = '訪問地址';
$lang['ucenter_key']             = '通訊密鑰';
$lang['ucenter_ip']              = 'IP地址';
$lang['ucenter_mysql_server']    = '資料庫地址';
$lang['ucenter_mysql_username']  = '資料庫用戶名';
$lang['ucenter_mysql_passwd']    = '資料庫密碼';
$lang['ucenter_mysql_name']      = '資料庫名';
$lang['ucenter_mysql_pre']       = '表首碼';

$lang['ucenter_application_id_tips'] 	= 'ShopNC商城系統在待整合中的應用ID';
$lang['ucenter_address_tips'] 			= '需要填寫待整合系统的訪問地址';
$lang['ucenter_ip_tips'] 				= '需要整合應用的IP地址';
$lang['ucenter_mysql_server_tips'] 		= '需要整合應用的資料庫地址';
$lang['ucenter_mysql_username_tips'] 	= '需要整合應用的資料庫訪問賬號';
$lang['ucenter_mysql_passwd_tips'] 		= '需要整合應用的資料庫訪問密碼';
$lang['ucenter_mysql_name_tips'] 		= '需要整合應用的資料庫名稱';
$lang['ucenter_mysql_pre_tips'] 		= '需要整合應用的表首碼';

$lang['default_img_wrong']       = '圖片限于png,gif,jpeg,jpg格式';

$lang['upload_image_filesize']	= '圖片檔案大小';
$lang['image_allow_ext']	= '圖片副檔名';
$lang['image_allow_ext_notice']	= '圖片副檔名，用於判斷上傳圖片是否為後台允許，多個尾碼名間請用半形逗號 "," 隔開。';
$lang['image_allow_ext_not_null']	= '圖片副檔名不能為空';
$lang['upload_image_file_size']	= '大小';
$lang['upload_image_filesize_is_number']    = '圖片檔案大小僅能為數字';
$lang['image_max_size_tips'] = '當前伺服器環境，最大允許上傳'.ini_get('upload_max_filesize').'B 的檔案，您的設置請勿超過該值。';
$lang['upload_image_size_c_num'] = '圖片像素最多四位數';
$lang['image_max_size_only_num'] = '圖片檔案大小僅能為數字';
$lang['image_max_size_c_num'] = '圖片檔案大小最多四位數';

$lang['upload_goods_image_size_tiny'] = '商品微圖尺寸';
$lang['upload_goods_image_size_tiny_tips'] = '設定生成商品微圖的尺寸，單位為像素。';
$lang['upload_goods_image_size_small'] = '商品小圖尺寸';
$lang['upload_goods_image_size_small_tips'] = '設定生成商品小圖的尺寸，單位為像素。';
$lang['upload_goods_image_size_medium'] = '商品中圖尺寸';
$lang['upload_goods_image_size_medium_tips'] = '設定生成商品中圖的尺寸，單位為像素。';
$lang['upload_goods_image_size_large'] = '商品大圖尺寸';
$lang['upload_goods_image_size_large_tips'] = '設定生成商品大圖的尺寸，單位為像素。';

$lang['gold_isuse']    = '金幣';
$lang['gold_isuse_notice']    = '金幣功能啟用後，店主可通過平台提供的交易方式進行購買，金幣可用來購買廣告、直通車等';
$lang['gold_isuse_open']    = '開啟';
$lang['gold_isuse_close']    = '關閉';
$lang['gold_rmbratio']    = '金幣市值';
$lang['gold_rmbratiodesc_1']    = '人民幣一元兌換';
$lang['gold_rmbratiodesc_2']    = '枚金幣';
$lang['gold_isuse_check']    = '請選擇是否啟用金幣功能';
$lang['gold_rmbratio_isnum']    = '金幣市值必須為正整數';
$lang['gold_rmbratio_min']    = '金幣市值最小為1';
$lang['edit_gold_set_ok']       = '編輯金幣設置成功。';
$lang['edit_gold_set_fail']     = '編輯金幣設置失敗。';

$lang['ztc_isuse']    = '直通車狀態';
$lang['ztc_isuse_open']    = '開啟';
$lang['ztc_isuse_close']    = '關閉';
$lang['ztc_dayprod']    = '直通車單價';
$lang['ztc_unit']    = '金幣/天';
$lang['ztc_isuse_check']    = '請選擇是否啟用直通車';
$lang['ztc_isuse_notice']    = '直通車功能啟用後，店主用金幣來購買，申請的商品在列表中會靠前';
$lang['ztc_dayprod_isnum']    = '直通車單價必須為正整數';
$lang['ztc_dayprod_min']    = '直通車單價最小為1';

$lang['qq_isuse']   			= '是否啟用QQ互聯功能';
$lang['qq_isuse_open']    	 	= '開啟';
$lang['qq_isuse_close']    	 	= '關閉';
$lang['qq_apply_link']    	 	= '立即在綫申請';
$lang['qq_appcode']    	 		= '域名驗證信息';
$lang['qq_appid']    	 		= '應用標識';
$lang['qq_appkey']    	 		= '應用密鑰';
$lang['qq_appid_error']    	 	= '請添加應用標識';
$lang['qq_appkey_error']    	= '請添加應用密鑰';
$lang['qq_ucenter_error']    	 	= '請關閉會員整合，才可啟用QQ互聯功能';

$lang['sina_isuse']   			= '新浪微博登錄功能';
$lang['sina_isuse_open']    	= '開啟';
$lang['sina_isuse_close']    	= '關閉';
$lang['sina_apply_link']    	= '立即在綫申請';
$lang['sina_appcode']    	 		= '域名驗證信息';
$lang['sina_wb_akey']    	 	= '應用標識';
$lang['sina_wb_skey']    	 	= '應用密鑰';
$lang['sina_wb_akey_error']    	= '請添加應用標識';
$lang['sina_wb_skey_error']    	= '請添加應用密鑰';
$lang['sina_function_fail_tip'] = '該功能需要在  php.ini 中 開啟 php_curl 擴展，才能使用。';

$lang['points_isuse']   		= '積分';
$lang['points_isuse_open']    	= '開啟';
$lang['points_isuse_close']    	= '關閉';
$lang['points_ruletip']    		= '積分規則如下';
$lang['points_item']    	 	= '項目';
$lang['points_number']    	 	= '贈送積分';
$lang['points_number_reg']    	= '會員註冊';
$lang['points_number_login']    = '會員每天登錄';
$lang['points_number_comments']    = '訂單商品評論';
$lang['points_number_order']    = '購物並付款';
$lang['points_number_orderrate']    = '消費額與贈送積分比例';
$lang['points_number_orderrate_tip']    = '例:設置為10，表明消費10單位貨幣贈送1積分';
$lang['points_number_ordermax']    = '每訂單最多贈送積分';
$lang['points_number_ordermax_tip']    = '例:設置為100，表明每訂單贈送積分最多為100積分';
$lang['points_update_success']    = '更新成功';
$lang['points_update_fail']    	= '更新失敗';

$lang['open_yes']    	= '是';
$lang['open_no']    	= '否';

$lang['font_set'] = '水印字型';
$lang['font_help1'] = '如果圖片空間中水印使用漢字則要下載並安裝相應字型庫。';
$lang['font_help2'] = '使用方法：將您下載到的字型庫上傳到網站根目錄下\resource\font這個檔案夾內，同時需要修改此檔案夾下的font.info.php檔案。例如：您下載了一個“宋體”字型檔simsun.ttf，將其放置於前面所述檔案夾內，打開font.info.php檔案在其中的$fontInfo = array(\'arial\'=>\'Arial\')數組後面添加宋體字型檔信息,“=>”符號左邊是檔案名，右邊是您想在網站上顯示的文字信息，添加後的樣子是array(\'arial\'=>\'Arial\',\'simsun\'=>\'宋體\')';
$lang['font_info'] = '已經安裝字型如下';

$lang['ftp_state'] = '圖片遠程存儲';
$lang['ftp_ssl_state'] = '啟用 SSL 連接';
$lang['ftp_server'] = 'FTP 伺服器地址';
$lang['ftp_port'] = 'FTP 伺服器連接埠';
$lang['ftp_username'] = 'FTP 帳號';
$lang['ftp_password'] = 'FTP 密碼';
$lang['ftp_pasv'] 		= '被動模式(pasv)連接';
$lang['ftp_attach_dir'] = '遠程附件目錄';
$lang['ftp_access_url'] = '遠程訪問 URL';
$lang['ftp_timeout'] = 'FTP 傳輸超時時間';
$lang['ftp_test'] = '遠程附件測試';

$lang['ftp_state_tip'] 	= '只有開啟狀態下以下配置才會生效';
$lang['ftp_ssl_tip'] 	= '只有伺服器開啟了SSL連結才可設置';
$lang['ftp_server_tip'] = '可以是FTP伺服器的IP地址或域名';
$lang['ftp_port_tip'] 	= '預設連接埠是21';
$lang['ftp_username_tip'] 	= '該帳號必需具有以下權限：讀取檔案、寫入檔案、刪除檔案、創建目錄、子目錄繼承';
$lang['ftp_pasv_tip'] 	= '一般情況下非被動模式即可，如果存在上傳失敗問題，可嘗試打開此設置';
$lang['ftp_attach_dir_tip'] 	= '遠程附件目錄的絶對路徑或相對於 FTP 主目錄的相對路徑，如果是根目錄請填"/"，非根目錄，請保證伺服器端該目錄已存在，結尾不需要"/"，如"/upload/shopnc"';
$lang['ftp_access_url_tip'] = '支持 HTTP 和 FTP 協議，結尾不要加斜杠“/”；如果使用 FTP 協議，FTP 伺服器必需支持 PASV 模式，為了安全起見，使用 FTP 連接的帳號不要設置可寫權限和列表權限。如果是根目錄，直接輸入域名即可，如“http://www.shopnc.net”，如果非根目錄，需要填寫域名+目錄形式，如“http://www.shopnc.net/upload/shopnc”';
$lang['ftp_timeout_tip'] = '單位：秒，0 為伺服器預設';
$lang['ftp_test_tip'] 	= '無需保存設置即可測試，請在測試通過後再保存';

$lang['ftp_error-100'] = '當前伺服器 PHP 沒有安裝 FTP 擴展模組或 FTP 函數被禁用';
$lang['ftp_error-101'] = '遠程附件功能未開啟';
$lang['ftp_error-102'] = '嘗試連接到 FTP 伺服器失敗，請檢查 FTP 伺服器地址和連接埠號設置是否正確';
$lang['ftp_error-103'] = '嘗試登錄到 FTP 伺服器失敗，請檢查 FTP 帳號密碼設置是否正確';
$lang['ftp_error-104'] = '嘗試切換目錄失敗，請檢查遠程附件目錄設置是否正確';
$lang['ftp_error-105'] = '嘗試創建目錄失敗，請檢查遠程附件目錄設置是否正確，並檢查 FTP 帳號是否具有創建目錄的權限';
$lang['ftp_error-106'] = '嘗試上傳檔案失敗，請檢查站點附件目錄是否具有上傳檔案的權限';
$lang['ftp_error-107'] = '嘗試上傳檔案失敗，請檢查 FTP 帳號是否具有上傳檔案的權限，如果確認權限正常，請嘗試使用被動模式(pasv)連接';
$lang['ftp_error_geterr'] = '嘗試下載檔案失敗，請檢查遠程訪問 URL 設置是否正確';
$lang['ftp_errord_elerr'] = '嘗試刪除檔案失敗，請檢查 FTP 帳號是否具有刪除檔案的權限';
$lang['ftp_test_ok'] = '遠程附件設置一切正常';

$lang['share_allow'] 	= '是否開啟站外分享功能';
$lang['share_notice'] 	= '開啟站外分享功能並設置站外分享綁定的相應介面後，SNS分享店舖和商品信息功能中將可以使用站外分享信息功能';


$lang['seo_set_index'] 		= '首頁';
$lang['seo_set_group'] 		= '團購';
$lang['seo_set_brand'] 		= '品牌';
$lang['seo_set_coupon'] 	= '優惠券';
$lang['seo_set_point'] 		= '積分中心';
$lang['seo_set_article'] 	= '文章';
$lang['seo_set_shop'] 		= '店舖';
$lang['seo_set_product'] 	= '商品';
$lang['seo_set_category'] 	= '商品分類';
$lang['seo_set_prompt'] 	= '插入的變數必需包括花括號“{}”，當應用範圍不支持該變數時，該變數將不會在前台顯示(變數後邊的分隔符也不會顯示)，留空為系統預設設置，SEO自定義支持手寫。以下是可用SEO變數: <br/><a href="javascript:void(0);" id="toggmore">顯示/隱藏全部提示...</a>';
$lang['seo_set_tips1'] 	= '站點名稱 {sitename}，（應用範圍：全站）';
$lang['seo_set_tips2'] 	= '名稱 {name}，（應用範圍：團購名稱、商品名稱、品牌名稱、優惠券名稱、文章標題、分類名稱）';
$lang['seo_set_tips3'] 	= '文章分類名稱 {article_class}，（應用範圍：文章分類頁）';
$lang['seo_set_tips4'] 	= '店舖名稱 {shopname}，（應用範圍：店舖頁）';
$lang['seo_set_tips5'] 	= '關鍵詞 {key}，（應用範圍：商品關鍵詞、文章關鍵詞、店舖關鍵詞）';
$lang['seo_set_tips6'] 	= '簡單描述 {description}，（應用範圍：商品描述、文章摘要、店舖關鍵詞）';
$lang['seo_set_tips7'] 	= '<a>提交保存後，需要到 設置 -> 清理緩存 清理SEO，新的SEO設置才會生效</a>';
$lang['seo_set_group_content'] 		= '團購內容';
$lang['seo_set_brand_list'] 		= '某一品牌商品列表';
$lang['seo_set_coupon_content'] 	= '優惠券內容';
$lang['seo_set_point_content'] 		= '積分中心商品內容';
$lang['seo_set_atricle_list'] 		= '文章分類列表';
$lang['seo_set_atricle_content'] 	= '文章內容';
$lang['seo_set_insert_tips'] 		= '可用的代碼，點擊插入';

$lang['memory_set_opt']		= '內存優化';
$lang['memory_set_prompt1']		= '啟用內存優化功能將會大幅度提升程序性能和伺服器的負載能力，內存優化功能需要伺服器系統以及PHP擴展模組支持';
$lang['memory_set_prompt2']		= '目前支持的內存優化介面有 Memcache、eAccelerator、Alternative PHP Cache(APC)、Xcache 四種，系統將會依據當前伺服器環境選用介面';
$lang['memory_set_prompt3']		= '內存介面的主要設置位於 config.ini.php 當中，您可以使用EditPlus（禁止使用記事本、寫字板）通過編輯 config.ini.php 進行高級設置';
$lang['memory_set_prompt4']		= '內存優化功能設置的內容，只有開啟內存緩存後才會生效，更改緩存詳細參數需編輯 config.ini.php 檔案';
$lang['memory_set_cur_status']	= '當前內存工作狀態';
$lang['memory_set_type']		= '內存類型';
$lang['memory_set_php']			= 'PHP 擴展環境';
$lang['memory_set_config']		= 'Config 設置';
$lang['memory_set_cls']			= '清理';
$lang['memory_set_opt_moduleset']	= '內存優化功能設置';
$lang['memory_set_opt_module']	= '功能模組';
$lang['memory_set_opt_ifopen']	= '是否開啟';
$lang['memory_set_opt_ttl']		= '緩存周期(秒)';

$lang['fulltext_set']		= 'Sphinx 全文檢索設置';
$lang['fulltext_set_prompt1']		= '啟用全文檢索功能將會大幅提高商城搜索響應速度，Sphinx全文檢索功能需要伺服器系統支持';
$lang['fulltext_set_prompt2']		= 'Sphinx全文檢索介面的主要設置位於網站根目錄 config.ini.php 當中，您可以使用EditPlus（禁止使用記事本、寫字板）通過編輯 config.ini.php 進行高級設置';
$lang['fulltext_set_prompt3']		= '該系統支持“實時索引更新”，當數據集非常大，以至于難於經常性的重建索引，但是每次新增的記錄卻相當地少的情況下可以使用“實時索引更新”的配置方式，具體配置信息請參照“readme/sphinx實時索引更新.conf”文檔';
