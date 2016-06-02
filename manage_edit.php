<? session_start();
include('include/no_login.php');
include('../include/common.php');
include('../include/color_array.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='e38';
 if(!in_array($thorid,$p_array) || empty($_GET['uid'])){header("Location:index.php"); exit();} 
$_GET=ck_gp($_GET);
$uid=m_esc($_GET['uid']);
$query=$db->query("SELECT * FROM admin_info where uid=$uid");
$da=$db->fetch_array($query);
if(empty($da['uid'])){header("Location:index.php"); exit();} 
$error='';
$key=ck_num($key);

if(!empty($_POST['act']) && $_POST['act']=='add'){
	$_POST=m_esc(ck_gp($_POST));
	$uid=$_POST['uid'];
	$group_uid=ck_num($_POST['group_uid'])?$_POST['group_uid']:0;
	$user_name=$_POST['user_name'];
	$pass_word=md5($_POST['pass_word']);	
	$name=$_POST['name'];
	$byear = $_POST['byear'];
	$bmonth = $_POST['bmonth'];
	$bday =$_POST['bday'];
	$birthday = date("$byear,$bmonth,$bday");
	$syear = $_POST['syear'];
	$smonth = $_POST['smonth'];
	$sday =$_POST['sday'];
	$start_date = date("$syear,$smonth,$sday");
	$cyear = $_POST['cyear'];
	$cmonth = $_POST['cmonth'];
	$cday =$_POST['cday'];
	$close_date = date("$cyear,$cmonth,$cday");	
	$sex=ck_num($_POST['sex'])?$_POST['sex']:0;
	$blood=ck_num($_POST['blood'])?$_POST['blood']:0;
	$marry=ck_num($_POST['marry'])?$_POST['marry']:0;
	$remark=$_POST['remark'];
	$user_level=$_POST['user_level'];
	$settime=$_POST['settime'];
	$del=$_POST['del'];
	$is_lock=$_POST['is_lock'];
	$phone=$_POST['phone'];
	$cellphone=$_POST['cellphone'];
	if(is_null($group_uid)){$error.='請輸入群組!\r\n';}
	if(!empty($_POST['pass_word'])&&!preg_match('/^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/', $_POST['pass_word'])){$error.='6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號\r\n';}  
	if(empty($name)){$error.='請輸入姓名!\r\n';}
	if(empty($byear)){$error.='請輸入生日年分!\r\n';}
	if(empty($bmonth)){$error.='請輸入生日月份!\r\n';}
	if(empty($bday)){$error.='請輸入生日日期!\r\n';}
 	if(!empty($byear)&&!empty($bmonth)&&!empty($bday)){if(!checkdate($bmonth,$bday,$byear)){$error.='生日沒有這一天喔!\r\n';}  } 
	if(!empty($phone)&&!preg_match('/^[0-9]{4}$/',$phone)){$error.='分機號碼必須為4位 數字\r\n';}	
	if(!empty($cellphone)&&!preg_match('/^[09]{2}[0-9]{8}$/',$cellphone)){$error.='手機號碼開頭必須為09 長度為10位數\r\n';}
	if(is_null($sex)){$error.='請輸入性別!\r\n';}
	if(is_null($blood)){$error.='請輸入血型\r\n';}
	if(is_null($marry)){$error.='請輸入婚姻!\r\n';}
	if(empty($error)){ 
	 $db->query("UPDATE admin_info SET group_uid='$group_uid',user_name='$user_name',pass_word='$pass_word',name='$name',birthday='$birthday',sex='$sex',blood='$blood',marry='$marry',remark='$remark',start_date='$start_date',close_date='$close_date',user_level='$user_level',settime='$settime',del='$del',is_lock='$is_lock',phone='$phone',cellphone='$cellphone' WHERE uid='$_POST[uid]'");

 			$id=$db->insert_id();
			$descrip="add admin_system_add.php uid=$uid name=$name";
			$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('38','$id','$admin_d[uid]','1','$timeformat','$timestamp','$descrip')");
 			$error='ok';	
 
	 	}	
	}	
$admin_del=array('N' => '否', 'Y' => '是');
$admin_group=array(0=>'管理者',1=>'開發人員',2=>'編輯人員',3=>'網頁設計師')	;
$admin_sex=array(0=>'女',1=>'男');
$admin_blood=array(0=>'A',1=>'B',2=>'AB',3=>'O');
$admin_marry=array(0=>'未婚',1=>'已婚');
$admin_is_lock=array(0=>'未鎖定',1=>'鎖定');

$ad_g=$admin_group[$da['group_uid']];


/*生日*/
$sql = "SELECT * FROM admin_info where uid=$uid";
$rs = $db->query($sql);
$r = mysql_fetch_assoc($rs);
$byear = (int)substr($r['birthday'], 0, 4);
$by=str_pad($byear, 4, '0', STR_PAD_LEFT);
$bmonth = (int)substr($r['birthday'], 5, 2);
$bm=str_pad($bmonth, 2, '0', STR_PAD_LEFT);
$bday = (int)substr($r['birthday'], 8, 2);
$bd=str_pad($bday, 2, '0', STR_PAD_LEFT);
/*開始日期*/
$syear = (int)substr($r['start_date'], 0, 4);
$sy=str_pad($syear, 4, '0', STR_PAD_LEFT);
$smonth = (int)substr($r['start_date'], 5, 2);
$sm=str_pad($smonth, 2, '0', STR_PAD_LEFT);
$sday = (int)substr($r['start_date'], 8, 2);
$sd=str_pad($sday, 2, '0', STR_PAD_LEFT);
/*結束日期*/
$cyear = (int)substr($r['close_date'], 0, 4);
$cy=str_pad($cyear, 4, '0', STR_PAD_LEFT);
$cmonth = (int)substr($r['close_date'], 5, 2);
$cm=str_pad($cmonth, 2, '0', STR_PAD_LEFT);
$cday = (int)substr($r['close_date'], 8, 2);
$cd=str_pad($cday, 2, '0', STR_PAD_LEFT);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include('include/css_js.php');?>
<script language="JavaScript">
function change_btn(st) {
	if (st == 'c') {
		document.getElementById("subm_1").innerHTML = '資料傳輸中';
	} else {
		document.getElementById("subm_1").innerHTML = '<input value="送  出" type="button" onclick="change_btn(' + "'c'" + ');check_empty(this,' + "'check'" + ',true);" /><input type="hidden" name="act" value="add" />';
	}
}		
function strim(str){
	return str.replace(/(^\s*)|(\s*$)/g, "");
		}
function check_empty() {
	ierror = 0;
	message = '';
	
	 if (form1.group_uid.value == "") {
		message+='請輸入群組\r\n';
		ierror=1;
				}		 									
	var cpwRegExp = /^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/;
		cpw=strim(document.form1.pass_word.value);
	if(form1.pass_word.value != "" && !cpwRegExp.test(cpw)){
		message+='密碼需6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號\r\n';
		ierror=1;
				}	               
	if(form1.pass_word.value != form1.pass_word2.value){
		form1.pass_word.value=="";
		form1.pass_word2.value=="";
		form1.pass_word.focus();
		message+='兩次輸入密碼不同\r\n';
		ierror=1;	 
				}	               
	if (form1.name.value == "") {
		message+='請輸入姓名\r\n';
		ierror=1;
				}				
	if (form1.byear.value == "") {
		message+='請輸入生日年分\r\n';
		ierror=1;
				}				
	if (form1.bmonth.value == "") {
		message+='請輸入生日月份\r\n';
		ierror=1;
				}				
	if (form1.bday.value == "") {
		message+='請輸入生日日期\r\n';
		ierror=1;
				}		 			
	var cphRegExp = /^[0-9]{4}$/;
		cph=strim(document.form1.phone.value);
	if(form1.phone.value != "" && !cphRegExp.test(cph)){
		message+='分機號碼必須為4位 數字\r\n';
		ierror=1;
				}		
	var ccphRegExp = /^[09]{2}[0-9]{8}$/;
		ccph=strim(document.form1.cellphone.value);
	if(form1.cellphone.value != "" && !ccphRegExp.test(ccph)){
		message+='手機號碼開頭必須為09 長度為10位數\r\n';
		ierror=1;
				}							
	if (ierror == 1) {
		change_btn('c');
		alert(message);
		change_btn('');
	} else {
		document.form1.submit();
		setTimeout("change_btn('c')", 500);
	}
}      
	</script> 
	
</head>
    <body>

    <form name="form1" id="form1" action="" enctype="multipart/form-data" method="post" onSubmit="">
        <table cellpadding="0" cellspacing="0" class="menutable">
            <tr>
                <td class="tableTitle" colspan="10">修改人員</td>
            </tr>

            <tr>
                <td align="center">群組</td>
				<td>
					<select name="group_uid" id="group_uid">
					<option value="<?= $da['group_uid']; ?>" ><?= $admin_group[$da['group_uid']]; ?></option>
					<?foreach($admin_group as $key => $value){?>
					<option value="<?= $key?>"><?= $value; ?></option>					
					<?}?>
					</select>
                </td>
			</tr>
            <tr>
				<td align="center">帳號</td>
                <td><?=$da['user_name']?></td>
				<input type="hidden" name="uid" value="<?=$da['uid']?>">	
				<input type="hidden" name="user_name" value="<?=$da['user_name']?>">			
            </tr>
            <tr>
                <td align="center">密碼</td>
                <td><input type="password"  name="pass_word" id="pass_word"  style="width:200px;" /></td>
			</tr>
			<tr>					 
				<td width="150" align="center">再重複一次密碼</td>
				<td><input type="password" name="pass_word2" id="pass_word2" style="width:200px;"></td>
			</tr>					
            <tr>				
				<td align="center">姓名</td>
                    <td><input type="text" value="<?=$da['name']?>" name="name" id="name" style="width:60px" /></td>
			</tr>
            <tr>
                <td align="center">生日</td>
                    <td>
					<select name="byear" id="byear">
 					<option value="<?= $by; ?>" ><?= $by; ?></option>
					<? for ($i=1960; $i<=2016; $i++) {?>
					<option value="<?=$i?>" ><?= str_pad($i, 4, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select> 年

                     <select name="bmonth" id="bmonth">
 					<option value="<?= $bm; ?>" ><?= $bm; ?></option>
					<? for ($i=1; $i<=12; $i++) {?>
					<option value="<?=$i?>" ><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select>月
                    <select name="bday" id="bday">
 					<option value="<?= $bd; ?>" ><?= $bd; ?></option>
					<? for ($i=1; $i<=31; $i++) {?>
					<option value="<?=$i?>" ><?=  str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select>日	
                    </td>	  
            </tr>
                <td width="150" align="center">分機</td> 
				 <td><input type="text" name="phone"  value="<?=$da['phone']?>"></td>
				 </tr>
				<tr>				 
                <td width="150" align="center">手機</td> 
				 <td><input type="text" name="cellphone"  value="<?=$da['cellphone']?>"></td>	
				 </tr>
				<tr>			
            <tr>
                <td align="center">性別</td>
				<td>
					<select name="sex" id="sex">
					<option value="<?= $da['sex']; ?>" ><?= $admin_sex[$da['sex']]; ?></option>
					<?foreach($admin_sex as $key => $value){?>
					<option value="<?= $key?>"><?= $value; ?></option>					
					<?echo $key ;}?>
					</select>						
				</td>
            </tr>
            <tr>
                <td align="center">血型</td>
				<td>
					<select name="blood" id="blood">
					<option value="<?= $da['blood']; ?>" ><?= $admin_blood[$da['blood']]; ?></option>
					<?foreach($admin_blood as $key => $value){?>
					<option value="<?= $key?>"><?= $value; ?></option>					
					<?}?>
					</select>					
				</td>
            </tr>
            <tr>
                <td align="center">婚姻</td>
					 <td>					 
					<select name="marry" id="marry">
					<option value="<?= $da['marry']; ?>" ><?= $admin_marry[$da['marry']]; ?></option>
					<?foreach($admin_marry as $key => $value){?>
					<option value="<?= $key?>"><?= $value; ?></option>					
					<?}?>
					</select>	
				</td>				
            </tr>
			<tr>				
				<td align="center">層級</td>
                    <td><input type="text" value="<?=$da['user_level']?>" name="user_level" id="user_level" style="width:60px" /></td>
			</tr>				
			<tr>				
				<td align="center">刪除</td>
				<td>					 
					<select name="del" id="del">
					<option value="<?= $da['del']; ?>" ><?= $admin_del[$da['del']]; ?></option>
					<?foreach($admin_del as $key => $value){?>
					<option value="<?= $key?>"><?= $value; ?></option>					
					<?}?>
					</select>	
				</td>
			</tr>			
			<tr>				
				<td align="center">鎖定</td>
					 <td>					 
					<select name="is_lock" id="is_lock">
					<option value="<?= $da['is_lock']; ?>" ><?= $admin_is_lock[$da['is_lock']]; ?></option>
					<?foreach($admin_is_lock as $key => $value){?>
					<option value="<?= $key?>"><?= $value; ?></option>					
					<?}?>
					</select>	
				</td>
			</tr>
            <tr>
                <td align="center">備註</td>
				<td>
				<textarea  name="remark" id="remark" style="width:200px;" value="<?=$da['remark']?>"><?=$da['remark']?> </textarea>
				</td>
            </tr>
            <td align="center">開始時間</td>
                    <td>
					<select name="syear" id="syear">
 					<option value="<?= $sy; ?>" ><?= $sy; ?></option>
					<? for ($i=1960; $i<=2016; $i++) {?>
					<option value="<?=$i?>" ><?= str_pad($i, 4, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select> 年

                     <select name="smonth" id="smonth">
 					<option value="<?= $sm; ?>" ><?= $sm; ?></option>
					<? for ($i=1; $i<=12; $i++) {?>
					<option value="<?=$i?>" ><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select>月
                    <select name="sday" id="sday">
 					<option value="<?= $sd; ?>" ><?= $sd; ?></option>
					<? for ($i=1; $i<=31; $i++) {?>
					<option value="<?=$i?>" ><?=  str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select>日	
                    </td>	  
            </tr>			
                <td align="center">結束時間</td>
<!-- 				<td><input type="text" name="birthday" id="birthday" class="date_pick" style="width:200px;" /></td> -->
                    <td>
					<select name="cyear" id="cyear">
 					<option value="<?= $cy; ?>" ><?= $cy; ?></option>
					<? for ($i=1960; $i<=2016; $i++) {?>
					<option value="<?=$i?>" ><?= str_pad($i, 4, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select> 年

                     <select name="cmonth" id="cmonth">
 					<option value="<?= $cm; ?>" ><?= $cm; ?></option>
					<? for ($i=1; $i<=12; $i++) {?>
					<option value="<?=$i?>" ><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select>月
                    <select name="cday" id="cday">
 					<option value="<?= $cd; ?>" ><?= $cd; ?></option>
					<? for ($i=1; $i<=31; $i++) {?>
					<option value="<?=$i?>" ><?=  str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select>日	
                    </td>	  
            </tr>			
			<tr>				
				<td align="center">建立時間</td>
                <td><?=$da['settime']?></td>
				<input type="hidden" name="settime" value="<?=$da['settime']?>">					
			</tr>				
            <tr>
                <td colspan="10" align="center">
                <div id="subm_1" style="height:20px;"><input type="button" value="送出" onclick="check_empty(this,'check',true);" /></div><input type="hidden" name="act" value="add" />
                 </td>
            </tr>
        </table>
    </form>
<? if($error=='ok'){?>
<script>
alert('更新成功');
parent.referu('');
</script>
<? }elseif(!empty($error)){?>
<script>
alert('<?=$error?>');
history.go(-1)
</script>
<? }?>
    </body>

    </html>