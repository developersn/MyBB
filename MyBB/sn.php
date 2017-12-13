<?php
define("IN_MYBB", "1");
require("./global.php");	
global $mybb;

$ui = $mybb->user['uid'];
$ug = $mybb->user['usergroup'];
	
if (!$mybb->user['uid'])
	error_no_permission();

$ban = explode(",",$mybb->settings['sn_ban']) ;
if(in_array($ui,$ban))
	error_no_permission();

$bang = explode(",",$mybb->settings['sn_bang']) ;
if(in_array($ug,$bang))
	error_no_permission();
	
$query = $db->simple_select('usergroups', 'title, gid', '', array('order_by' => 'gid', 'order_dir' => 'asc'));
while($group = $db->fetch_array($query, 'title, gid'))
	$groups[$group['gid']] = $group['title'];


$query = $db->simple_select('sn', '*', '', array('order_by' => 'price', 'order_dir' => 'ASC'));

while ($sn = $db->fetch_array($query))
{
	$bgcolor = alt_trow();
	$sn['num'] = intval($sn['num']);
	$sn['title'] = htmlspecialchars_uni($sn['title']);

	$sn['price'] = floatval($sn['price']).' تومان ';
	$sn['usergroup'] = $groups[$sn['group']];

	if($sn['time']== 1)
		$time= "روز";

	if($sn['time']== 2)
		$time= "هفته";
	
	if($sn['time']== 3)
		$time= "ماه";
	
	if($sn['time']== 4)
		$time= "سال";	

	$period = intval($sn['period']);
	$sn['period'] = intval($sn['period'])." ".$time;
	$uid = $mybb->user['uid'];
	$query5 = $db->query("SELECT * FROM ".TABLE_PREFIX."sn_tractions WHERE uid=$uid AND stauts = 1");
	$check5 = $db->fetch_array($query5);
	if ($check5)
	{
		$note = "<div class=\"red_alert\">به دلیل اینکه شما قبلاً یکی از این بسته ها را خریداری کرده اید و زمان عضویت شما به پایان نرسیده است ، نمی توانید  بسته ی جدیدی را خریداری نمایید </div>";
		$buybutton = "<input type='image' src='{$mybb->settings['bburl']}/images/buy-pack.png' border='0'  name='submit'alt='خرید بسته {$sn['title']}' />";
	}
	else
		$buybutton = "<form action='{$mybb->settings['bburl']}/gotb.php' method='post'>
						<input type='hidden' name='sn_num' value='{$sn['num']}' /> 
						<input type='image' src='{$mybb->settings['bburl']}/images/buy-pack.png' border='0'  name='submit'alt='خرید بسته {$sn['title']}' />
					</form>";

	eval("\$list .= \"".$templates->get('sn_list_table')."\";");
}

if (!$list)
	eval("\$list = \"".$templates->get('sn_no_list')."\";");

eval("\$snpage = \"".$templates->get('sn_list')."\";");
output_page($snpage);
?>