<?php
if (!defined("IN_MYBB"))
	die ("You can directly not access this file ");

$plugins->add_hook("admin_user_menu", "sn_plugin_admin_cp");
$plugins->add_hook('admin_user_action_handler', 'sn_handle');



function mybb_sn_info()
{
 return array(
 
    "name"  => "عضویت آنی پس از پرداخت (پرداخت آنلاین)",
	"description"   => "اين پلاگين بلافاصله پس از پرداخت توسط کاربر او را به گروه کاربري مورد نظر انتقال مي دهد.",
	"website"   => "http://",
	"author"   => "sn",
	"authorsite"   => "http://",
	"version"    => "4.1",
	"compatibility"   => "18*",
	);
}

function mybb_sn_install()
{
global $db, $mybb, $settings;

     $settings_group = array(
        'gid'          => NULL,
        'name'         => 'sns',
        'title'        => 'تنظيمات پلاگين درگاه پرداخت پرداخت آنلاین',
        'description'  => '',
        'disporder'    => $rows++,
        'isdefault'    => 'no'
    );
    $db->insert_query('settinggroups', $settings_group);
    $gid = $db->insert_id();

	$sn2= array(
	'name' => 'api_sn',
	'title' =>'ApiID',
	'description' =>'API کدی را که از سایت پرداخت آنلاین دریافت کرده اید در این قسمت وارد کنید.',
	'optionscode' => 'text',
	'value' =>'12345',
	'disporder' => 2,
	'gid' => intval($gid));
    $db->insert_query('settings',$sn2);
	
	$sn3 = array(
	'name' => 'sn_uid',
	'title' =>'شناسه کاربر مدیرکل',
	'description' =>'شناسه کاربری مدیر کل که پیام خصوصی پس از پرداخت توسط او به کاربر ارسال می شود.',
	'optionscode' => 'text',
	'value' =>'1',
	'disporder' => 3,
	'gid' => intval($gid));





	
    $db->insert_query('settings',$sn3);
	$sn4 = array(
	'name' => 'sn_pm',
	'title' =>'متن پیام خصوصی',
	'description' =>"پیام خصوصی‌ای که پس از عضویت کاربر در سایت به عنوان <strong>سند</strong> به او ارسال می‌شود. (BBCODE) </br>
	راهنما : {username} = نام کاربری | {group} = گروه جدید | {refid} = شماره تراکنش | {expdate} = تاریخ پایان عضویت | {bank} = افزایش موجودی دربانک </br>
	<script type=\"text/javascript\">
function insertText(value, textarea)
{
	// Internet Explorer
	if(document.selection)
	{
		textarea.focus();
		var selection = document.selection.createRange();
		selection.text = value;
	}
	// Firefox
	else if(textarea.selectionStart || textarea.selectionStart == \'0\')
	{
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		textarea.value = textarea.value.substring(0, start)	+ value	+ textarea.value.substring(end, textarea.value.length);
	}
	else
	{
		textarea.value += value;
	}
}
</script>
<br />
<b onclick=\"insertText(\'{username}\', $(\'setting_sn_pm\'));\">{username}</b>
<b onclick=\"insertText(\'{group}\', $(\'setting_sn_pm\'));\">{group}</b>
<b onclick=\"insertText(\'{refid}\', $(\'setting_sn_pm\'));\">{refid}</b>
<b onclick=\"insertText(\'{expdate}\', $(\'setting_sn_pm\'));\">{expdate}</b>
<b onclick=\"insertText(\'{bank}\', $(\'setting_sn_pm\'));\">{bank}</b>",

	'optionscode' => 'textarea',
	'value' =>'[B]{username}[/B] گرامی، درود!
 عضویت شما در گروه [B]{group}[/B] انجام شد و شما به این گروه منتقل شدید. 
 شماره ی تراکنش شما: [B]{refid}[/B] 
 تاريخ پايان عضويت شما: [B]{expdate}[/B]
 مقدار افزایش موجودی در بانک: [B]{bank}[/B]
 ',
	'disporder' => 5,
	'gid' => intval($gid));
    $db->insert_query('settings',$sn4);

	$sn5 = array(
	'name' => 'sn_soap',
	'title' =>'Curl',
	'description' =>'curl می بایست بر روی هاست شما فعال باشد',
	'optionscode' => 'select\n0=Curl',
	'value' =>'0',
	'disporder' => 7,
	'gid' => intval($gid));
    $db->insert_query('settings',$sn5);


    $sn9 = array(
	'name' => 'webservice',
    'title' =>'آیتم های اختیاری وب سرویس  ',
	'description' =>'فعال سازی / غیر فعال سازی',
   'optionscode' => 'select\n0=Yes \n1=No',
	'value' =>'0',
	'disporder' => 8,
	'gid' => intval($gid));
    $db->insert_query('settings',$sn9);




	
	$sn6 = array(
	'name' => 'sn_note',
	'title' =>'پیام پس از عضویت',
	'description' =>"پیامی که بلافاصله پس از انتقال کاربر از پرداخت آنلاین به سایت شما به او نمایش داده می‌شود. (HTML) </br>
	راهنما : {username} = نام کاربری | {group} = گروه جدید | {refid} = شماره تراکنش | {expdate} = تاریخ پایان عضویت | {bank} = افزایش موجودی دربانک </br>
	<script type=\"text/javascript\">
function insertText(value, textarea)
{
	// Internet Explorer
	if(document.selection)
	{
		textarea.focus();
		var selection = document.selection.createRange();
		selection.text = value;
	}
	// Firefox
	else if(textarea.selectionStart || textarea.selectionStart == \'0\')
	{
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		textarea.value = textarea.value.substring(0, start)	+ value	+ textarea.value.substring(end, textarea.value.length);
	}
	else
	{
		textarea.value += value;
	}
}
</script>
<br />
<b onclick=\"insertText(\'{username}\', $(\'setting_sn_note\'));\">{username}</b>
<b onclick=\"insertText(\'{group}\', $(\'setting_sn_note\'));\">{group}</b>
<b onclick=\"insertText(\'{refid}\', $(\'setting_sn_note\'));\">{refid}</b>
<b onclick=\"insertText(\'{expdate}\', $(\'setting_sn_note\'));\">{expdate}</b>
<b onclick=\"insertText(\'{bank}\', $(\'setting_sn_note\'));\">{bank}</b>",

	'optionscode' => 'textarea',
	'value' =>'<strong>{username}</strong> گرامی٬ از عضویت شما در گروه <strong>{group}</strong> سپاس گزاریم! </br> عضویت شما در این گروه انجام شد و شما به این گروه منتقل شدید. </br> اطلاعات عضویت شما:</br>
نام کاربری: <strong>{username}</strong> </br> گروه: <strong>{group}</strong> </br> شماره تراکنش: <strong>{refid}</strong> </br>تاریخ پایان عضویت: <strong>{expdate}</strong> </br>مقدار افزایش موجودی در بانک: <strong>{bank}</strong> </br> ضمناً یک پیام خصوصی به عنوان <strong>سند</strong> برای شما ارسال شد. لطفاً این پیام را برای اطمنیان نزد خود نگه دارید.</br>
با سپاس از عضویت شما.',
	'disporder' => 6,
	'gid' => intval($gid));
    $db->insert_query('settings',$sn6);
	
	$sn7 = array(
	'name' => 'sn_ban',
	'title' =>'اعضای محروم',
	'description' =>'شناسه کاربرانی را که می‌خواهید از خرید بسته‌ها محروم شوند را وارد کنید. (به وسیله‌ی کاما(,) متمایز کنید)',
	'optionscode' => 'text',
	'value' =>'',
	'disporder' => 4,
	'gid' => intval($gid));
    $db->insert_query('settings',$sn7);

	$sn8 = array(
	'name' => 'sn_bang',
	'title' =>'گروه‌های محروم',
	'description' =>'شناسه گروه‌هایی را که می‌خواهید از خرید بسته‌ها محروم شوند را وارد کنید. (به وسیله‌ی کاما(,) متمایز کنید)',
	'optionscode' => 'text',
	'value' =>'7,5',
	'disporder' => 4,
	'gid' => intval($gid));
    $db->insert_query('settings',$sn8);

	
		 $sn_task = array(
			"title" => "بررسی ابطال عضویت اعضای گروه ویژه",
			"description" => "این وظیفه که هر‌دقیقه اجرا می‌شود٬ ابطال عضویت اعضای گروه ویژه را بررسی می‌کند",
			"file" => "sn",
			"minute" => '0',
			"hour" => '*',
			"day" => '*',
			"month" => '*',
			"weekday" => '*',
			"enabled" => '1',
			"logging" => '1',
			"nextrun" => time()
		);
	 $db->insert_query("tasks", $sn_task);

	
	    rebuild_settings();


	$db->write_query("CREATE TABLE `".TABLE_PREFIX."sn` (
	  `num` bigint(30) UNSIGNED NOT NULL auto_increment,
	  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
	  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	  `time` varchar(1) NOT NULL default '',
	  `period` int(5) UNSIGNED NOT NULL default '0',
	  `price` int(5) UNSIGNED NOT NULL default '0',
	  `group` smallint(5) UNSIGNED NOT NULL default '0',
	  `bank` int(5) UNSIGNED NOT NULL default '0',	  
	  PRIMARY KEY  (`num`)
	  ) ENGINE=MyISAM");
	  
	  	$db->write_query("CREATE TABLE `".TABLE_PREFIX."sn_tractions` (
	  `tid` bigint(30) UNSIGNED NOT NULL auto_increment,
	  `packnum` bigint(30) UNSIGNED NOT NULL default '0',
	  `uid` int(10) UNSIGNED NOT NULL default '0',
	  `gid` smallint(5) UNSIGNED NOT NULL default '0',
	  `pgid` smallint(5) UNSIGNED NOT NULL default '0',
	  `stdateline` bigint(30) UNSIGNED NOT NULL default '0',	  
	  `dateline` bigint(30) UNSIGNED NOT NULL default '0',
	  `trackid` int UNSIGNED NOT NULL default '0',	  
	  `payed` int(5) UNSIGNED NOT NULL default '0',
	  `stauts` int(5) UNSIGNED NOT NULL default '0',

	  PRIMARY KEY  (`tid`)
	  ) ENGINE=MyISAM");


}	  
	 function mybb_sn_is_installed()
{
	global $db;
		return $db->table_exists("sn");
}

function mybb_sn_uninstall()
{
global $db;
	if ($db->table_exists('sn'))
		$db->drop_table('sn');
	if ($db->table_exists('sn_tractions'))
		$db->drop_table('sn_tractions');
		
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('sn_activation', 'sns')");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('sn_uid', 'sns')");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('api_sn', 'sns')");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('sn_pm', 'sns')");	
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('sn_soap', 'sns')");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('sn_note', 'sns')");	
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('sn_ban', 'sns')");										
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('sn_bang', 'sns')");										
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups where name='sns'");
	$db->delete_query("tasks", "file='sn'");

		rebuild_settings();
}
 
function mybb_sn_activate()

{
global $db, $template, $lang;

	$tmp_list = array(
		"title" => 'sn_list',
		"template" => $db->escape_string('
<html>
<head>
<title>بسته های عضویت ویژه</title>
{$headerinclude}
</head>
<body>
{$header}
{$note}
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="10"><strong>بسته های عضویت ویژه</strong></td>
</tr>
<tr>
<td class="tcat" width="25%"><strong>نام بسته</strong></td>
<td class="tcat" width="25%"><strong>توضیحات</strong></td>
<td class="tcat" width="15%" align="center"><strong>گروه کاربری</strong></td>
<td class="tcat" width="15%" align="center"><strong>مدت زمان عضویت</strong></td>
<td class="tcat" width="15%" align="center"><strong>هزینه عضویت</strong></td>
<td class="tcat" width="15%" align="center"><strong>خرید</strong></td>
</tr>
{$list}
</table>
</br>

{$footer}
</body>
</html>'),
		"sid" => "-1",
		);
	$db->insert_query("templates", $tmp_list);

	$tmp_table = array(
		"title" => 'sn_list_table',
		"template" => $db->escape_string('<html>
<tr>
<td class="{$bgcolor}" width="25%"><strong>{$sn[\'title\']}</strong></td>
<td class="{$bgcolor}" width="25%">{$sn[\'description\']}</td>
<td class="{$bgcolor}" width="15%" align="center">{$sn[\'usergroup\']}</td>
<td class="{$bgcolor}" width="15%" align="center">{$sn[\'period\']}</td>
<td class="{$bgcolor}" width="15%" align="center">{$sn[\'price\']}</td>
<td class="{$bgcolor}" width="15%" align="center">{$buybutton}</td>
</tr>'),
		"sid" => "-1",
		);

	$db->insert_query("templates", $tmp_table);
	
	$tmp_emp = array(
		"title" => 'sn_no_list',
		"template" => $db->escape_string('<html>
<tr>
<td class="trow1" width="100%" colspan="10">در حال حاضر بسته ی عضویت ویژه ای در این انجمن ثبت نشده است.</td>
</tr>'),
		"sid" => "-1",
		);

	$db->insert_query("templates", $tmp_emp);
	
	$tmp_info = array(
		"title" => 'sn_payinfo',
		"template" => $db->escape_string('<html>
<head>
<title>{$mybb->settings[bbname]} - گزارش پرداخت</title>
{$headerinclude}
</head>
<body>
{$header}
<br />

<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="2"><strong>گزارش پرداخت</strong></td>
</tr>
<tr>
<td class="trow1" colspan="6">
{$info}
</td>
</tr>
</table>
{$footer}
</body>
</html>
'),
		"sid" => "-1",
		);

	$db->insert_query("templates", $tmp_info);
	
	}
	


function mybb_sn_deactivate()	
{
	global $db;
    $db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='sn_list'");
    $db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='sn_list_table'");
    $db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='sn_no_list'");	
    $db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='sn_payinfo'");	
	
	rebuild_settings();
}

function sn_plugin_admin_cp($sub_menu)
{
	global $mybb, $lang;
			
	end($sub_menu);
	$key = (key($sub_menu))+10;
		
	if(!$key)
		$key = '20';
		
	$sub_menu[$key] = array('id' => 'sn', 'title' => "بسته های عضویت آنی (پرداخت آنلاین)", 'link' => "index.php?module=user-sn");
     return $sub_menu;
}

function sn_handle($action)
{
	$action['sn'] = array('active' => 'sn', 'file' => 'sn.php');
	return $action;
}

?>