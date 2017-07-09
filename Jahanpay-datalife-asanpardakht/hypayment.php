<?php

/**
 * DLEPERSIAN DEVELOPMENT TEAM
 * ------------------------------------------------------
 * @author Hamid Yousefi <mails.hamidyousefi@gmail.com>
 * @link http://dlepersian.ir/10-jahanpay.html
 * @version 1.1 Farsi
 * ------------------------------------------------------
 * @date 2015/02/24
 */

@ob_start ();
@ob_implicit_flush ( 0 );

if( !defined( 'E_DEPRECATED' ) ) {

    @error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
    @ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

} else {

    @error_reporting ( E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
    @ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );

}

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );

define ( 'DATALIFEENGINE', true );
define ( 'ROOT_DIR', dirname ( __FILE__ ) );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

require_once dirname(__FILE__) . '/engine/api/api.class.php';
$dle_api->install_admin_module("hypayment", "پرداخت آنلاین", "ماژول پرداخت آنلاین با سرویس درگاه واسط جهان پی", "hypayment.png");

$sql = array();
$sql[] = <<< SQL
CREATE TABLE IF NOT EXISTS `dle_hypayments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `hypay_name` varchar(255) NOT NULL,
  `hypay_email` varchar(255) NOT NULL,
  `hypay_price` int(10) unsigned NOT NULL DEFAULT '0',
  `hypay_info` text NOT NULL,
  `date` int(11) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(255) NOT NULL,
  `verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gateway` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
SQL;

foreach($sql as $query) $db->query($query);
?>

<!DOCTYPE html>
<html lang="fa">
    <head>
        <meta charset="utf-8">
        <title>دی الـ ای پرشین &bullet; نصب ماژول</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style type="text/css">
            * { margin:0;padding:0;outline:0 none; }
            a, a:hover, a:focus, a:active { color:#38d;text-decoration:none; }
        </style>
    </head>
    <body bgcolor="#efefef" style="font-family:Tahoma, Geneva, sans-serif;font-size:11px;line-height:24px;text-align:center;">
        <a href="http://dlepersian.ir" target="_blank" style="margin:50px 0 0;display:block;">
            <img alt="دیتالایف انجین فارسی" title="توسعه و پشتیبانی سیستم مدیریت محتوای دیتالایف انجین فارسی" src="http://static.dlepersian.ir/images/logo.png">
        </a>
        <div style="margin:50px auto 0;max-width:300px;width:80%;text-shadow:0 0 3px rgba(0,0,0,0.2);color:#555;">
            <div style="display:block;border-radius:3px;background-color:#fff;padding:7px 15px 11px;border:1px solid #dcdcdc;">
                <p dir="rtl" style="margin:0;padding:0;">تغییرات مورد نیاز اعمال گردید</p>
            </div>
        </div>
        <div style="display:block;width:92%;text-align:center;color:#555;position:fixed;bottom:20px;padding:0 4%;left:0;">
            <p dir="rtl">تمام حقوق مادی و معنوی محصول متعلق به <a href="http://depersian.ir" target="_blank">دی الـ ای پرشین</a> می باشد...</p>
        </div>
    </body>
</html>