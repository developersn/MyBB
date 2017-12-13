<?php
session_start();
define("IN_MYBB", "1");
require("./global.php");
if (!$mybb->user['uid'])
	error_no_permission();

// Security
$sec=$_GET['sec'];
$mdback = md5($sec.'vm');
$mdurl=$_GET['md'];
// Security
$transData = $_SESSION[$sec];
$au=$transData['au']; //
	if(isset($_GET['sec']) or isset($_GET['md']) AND $mdback == $mdurl )
	{
	
	$api = $mybb->settings['api_sn'];
	$num = $_GET['num'];
	$query0 = $db->query("SELECT * FROM ".TABLE_PREFIX."sn WHERE num={$num}");
	$sn0 = $db->fetch_array($query0);
	
	$amount = $sn0['price']; 
	$gid = $sn0['group'];
	$pgid = $mybb->user['usergroup'];
	$uid = $mybb->user['uid'];
	$time = $sn0['time'];		
	$period = $sn0['period'];
	$bank = $sn0['bank'];


$bank_return = $_POST + $_GET ;
$data_string = json_encode(array (
'pin' => $api,
'price' => $amount,
'order_id' => $num,
'au' => $au,
'bank_return' =>$bank_return,
));

$ch = curl_init('https://developerapi.net/api/v1/verify');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json',
'Content-Length: ' . strlen($data_string))
);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result,true);
				
				$res = $json['result'];

					 switch ($res) {
						    case -1:
						    $msg = "پارامترهای ارسالی برای متد مورد نظر ناقص یا خالی هستند . پارمترهای اجباری باید ارسال گردد";
						    break;
						     case -2:
						    $msg = "دسترسی api برای شما مسدود است";
						    break;
						     case -6:
						    $msg = "عدم توانایی اتصال به گیت وی بانک از سمت وبسرویس";
						    break;

						     case -9:
						    $msg = "خطای ناشناخته";
						    break;

						     case -20:
						    $msg = "پین نامعتبر";
						    break;
						     case -21:
						    $msg = "ip نامعتبر";
						    break;

						     case -22:
						    $msg = "مبلغ وارد شده کمتر از حداقل مجاز میباشد";
						    break;


						    case -23:
						    $msg = "مبلغ وارد شده بیشتر از حداکثر مبلغ مجاز هست";
						    break;
						    
						      case -24:
						    $msg = "مبلغ وارد شده نامعتبر";
						    break;
						    
						      case -26:
						    $msg = "درگاه غیرفعال است";
						    break;
						    
						      case -27:
						    $msg = "آی پی مسدود شده است";
						    break;
						    
						      case -28:
						    $msg = "آدرس کال بک نامعتبر است ، احتمال مغایرت با آدرس ثبت شده";
						    break;
						    
						      case -29:
						    $msg = "آدرس کال بک خالی یا نامعتبر است";
						    break;
						    
						      case -30:
						    $msg = "چنین تراکنشی یافت نشد";
						    break;
						    
						      case -31:
						    $msg = "تراکنش ناموفق است";
						    break;
						    
						      case -32:
						    $msg = "مغایرت مبالغ اعلام شده با مبلغ تراکنش";
						    break;
						 
						    
						      case -35:
						    $msg = "شناسه فاکتور اعلامی order_id نامعتبر است";
						    break;
						    
						      case -36:
						    $msg = "پارامترهای برگشتی بانک bank_return نامعتبر است";
						    break;
						        case -38:
						    $msg = "تراکنش برای چندمین بار وریفای شده است";
						    break;
						    
						      case -39:
						    $msg = "تراکنش در حال انجام است";
						    break;
						    
                            case 1:
						    $msg = "پرداخت با موفقیت انجام گردید.";
						    break;

						    default:
						       $msg = $json['msg'];
						}

                    if($json['result'] == 1)
					{
	
		$query1 = $db->simple_select("sn_tractions", "*", "trackid='$au'");
		$check1 = $db->fetch_array($query1);
		if ($check1)
			$info = "این تراکنش قبلاً ثبت شده است. بنابراین شما نمی‌توانید به صورت غیر مجاز از این سیستم استفاده کنید.";
		else
		{
			$query2 = $db->simple_select("sn", "*", "`num` = '$num'");
			while($check = $db->fetch_array($query2))
			{
				if ($amount != $check['price'])
					$info = "اطلاعات داده شده اشتباه می باشد . به همین دلیل عضویت انجام نشد.";

				$query1 = $db->simple_select('usergroups', 'title, gid', '1=1');
				while($group = $db->fetch_array($query1))
					$groups[$group['gid']] = $group['title'];
					
				$query5 = $db->simple_select('users', 'username, uid', '');
				while($uname1 = $db->fetch_array($query5, 'username, uid'))
					$usname[$uname1['uid']] = $uname1['username'];
		
				if($time == "1")
					$dateline = strtotime("+{$period} days");
				if($time == "2")
					$dateline = strtotime("+{$period} weeks");
				if($time == "3")
					$dateline = strtotime("+{$period} months");
				if($time == "4")
					$dateline = strtotime("+{$period} years");
		
				$stime = time();
				$add_traction = array(
					'packnum' => $num,
					'uid' => $uid,
					'gid' => $gid ,
					'pgid' => $pgid ,
					'stdateline' => $stime,
					'dateline' => $dateline,
					'trackid' => $au,
					'payed' => $amount,
					'stauts' => "1",
				);
				
				if ($db->table_exists("bank_pey") && $bank != 0)
				{
						$query7 = $db->simple_select("bank_pey", "*", "`uid` = '$uid'");
						$bankadd = $db->fetch_array($query7);
						$bank_traction = array(
						'uid' => $uid,
						'tid' => 0,
						'pid' => 0,
						'pey' => $bank ,
						'type' => '<img src="'.$mybb->settings['bburl'].'/images/inc.gif">',
						'username' => "مدیریت",
						'time' => $stime,
						 'info' => "خرید از درگاه sn",
					);
						
							if(!$bankadd)
							{
					$add_money = array(
					'uid' => $uid,
					'username' => $usname[$uid],
					'pey' => $bank ,
					);
									   $db->insert_query("bank_pey", $add_money);
									   $db->insert_query("bank_buy", $bank_traction);
							}
							if($bankadd)
							{
							$pey = $bankadd['pey'];
							$type='<img src="'.$mybb->settings['bburl'].'/images/inc.gif">';
									   $db->query("update ".TABLE_PREFIX."bank_pey set pey=$pey+$bank where uid=$uid");
									   $db->insert_query("bank_buy", $bank_traction);

							}
							
				}
				else
					$bank = "0";

				$db->insert_query("sn_tractions", $add_traction);
				$db->update_query("users", array("usergroup" => $gid), "`uid` = '$uid'");
				$expdate = my_date($mybb->settings['dateformat'], $dateline).", ".my_date($mybb->settings['timeformat'], $dateline);
				$profile_link = "[url={$mybb->settings['bburl']}/member.php?action=profile&uid={$uid}]{$usname[$uid]}[/url]";
				$profile_link1 = build_profile_link($usname[$uid], $uid, "_blank");
				$info = preg_replace(
							array(
								'#{username}#',
								'#{group}#',
								'#{refid}#',
								'#{expdate}#',
								'#{bank}#',	
							),array(
									$profile_link1,
									$groups[$gid],
									$au,
									$expdate,
									$bank,
									
							),$mybb->settings['sn_note']
						);
				$username = $mybb->user['username'];
				// Notice User By PM
				require_once MYBB_ROOT."inc/datahandlers/pm.php";
				$pmhandler = new PMDataHandler();
				$from_id = intval($mybb->settings['sn_uid']);
				$recipients_bcc = array();
				$recipients_to = array(intval($uid));
				$subject = "گزارش پرداخت";
				$message = preg_replace(
							array(
								'#{username}#',
								'#{group}#',
								'#{refid}#',
								'#{expdate}#',
								'#{bank}#',
								
							),array(
								$profile_link,
								$groups[$gid],
								$au,
								$expdate,
								$bank,
								
							),$mybb->settings['sn_pm']
						);
				$pm = array(
							'subject' => $subject,
							'message' => $message,
							'icon' => -1,
							'fromid' => $from_id,
							'toid' => $recipients_to,
							'bccid' => $recipients_bcc,
							'do' => '',
							'pmid' => ''
						);
						
				$pm['options'] = array(
							"signature" => 1,
							"disablesmilies" => 0,
							"savecopy" => 1,
							"readreceipt" => 1
						);
					
				$pm['saveasdraft'] = 0;
				$pmhandler->admin_override = true;
				$pmhandler->set_data($pm);
				if($pmhandler->validate_pm())
					$pmhandler->insert_pm();

					// Notice Admin By PM
				require_once MYBB_ROOT."inc/datahandlers/pm.php";
				$pmhandler = new PMDataHandler();
				$uidp=$mybb->settings['sn_uid'];
				$from_id = intval($mybb->settings['sn_uid']);
				$recipients_bcc = array();
				$recipients_to = array(intval($uidp));
				$subject = "عضویت کاربر در گروه ویژه";
				$message = preg_replace(
							array(
								'#{username}#',
								'#{group}#',
								'#{refid}#',
								'#{expdate}#',
								'#{bank}#',
								
							),
							array(
								$profile_link,
								$groups[$gid],
								$au,
								$expdate,
								$bank,
								
							),
							"کاربر [B]{username}[/B] با شماره تراکنش [B]{refid}[/B] در گروه [B]{group}[/B] عضو شد.
							تاریخ پایان عضویت:[B]{expdate}[/B]"
							);
					$pm = array(
							'subject' => $subject,
							'message' => $message,
							'icon' => -1,
							'fromid' => $from_id,
							'toid' => $recipients_to,
							'bccid' => $recipients_bcc,
							'do' => '',
							'pmid' => ''
						);
						
					$pm['options'] = array(
							"signature" => 1,
							"disablesmilies" => 0,
							"savecopy" => 1,
							"readreceipt" => 1
						);
					
					$pm['saveasdraft'] = 0;
					$pmhandler->admin_override = true;
					$pmhandler->set_data($pm);
						
					if($pmhandler->validate_pm())
						$pmhandler->insert_pm();
			}
		}			
	}
	else
	{
	$res=$msg;
	
		$info = 'خطا' .$msg .$prompt;		
	}
}else{
	
	$info = "خطا در تایید تراکنش";
}
eval("\$verfypage = \"".$templates->get('sn_payinfo')."\";");
output_page($verfypage);
?>	