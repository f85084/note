<? session_start();
include('include/no_login.php');
include('../include/common.php');
include('../include/color_array.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='e41';
 if(!in_array($thorid,$p_array) || empty($_GET['id'])){header("Location:index.php"); exit();} 
$_GET=ck_gp($_GET);
$id=m_esc($_GET['id']);
$query=$db->query("SELECT * FROM user where id='$id'");
$da=$db->fetch_array($query);
if(empty($da['id'])){header("Location:index.php"); exit();} 
$error='';
$today=date("Y,m,d H:i:s");
if(!empty($_POST['act']) && $_POST['act']=='add'){
	$_POST=m_esc(ck_gp($_POST));
	$account=$_POST['account'];
	$pw=md5($_POST['pw']);	
	$mobile_country_code=$_POST['mobile_country_code'];
	$mobile=$_POST['mobile'];
	$name=$_POST['name'];
	$nickname=$_POST['nickname'];
	$byear = $_POST['byear'];
	$bmonth = $_POST['bmonth'];
	$bday =$_POST['bday'];
	$birthday = date("$byear,$bmonth,$bday");
	$sex=ck_num($_POST['sex'])?$_POST['sex']:0;
	$email =$_POST['email'];	
	$country =$_POST['country'];	
	$zip =$_POST['zip'];	
	$address =$_POST['address'];	
	$status=ck_num($_POST['status'])?$_POST['status']:0;
	$ulevel=ck_num($_POST['ulevel'])?$_POST['ulevel']:0;
	$etime=$_POST['etime'];
	$fmid=$_POST['fmid'];
	$intro=$_POST['intro'];
	if(!empty($_POST['pw']) && !preg_match('/^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/', $_POST['pw'])){$error.='6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號\r\n';} 
	if(empty($name)){$error.='請輸入姓名!\r\n';}
	if(empty($byear)){$error.='請輸入生日年分!\r\n';}
	if(empty($bmonth)){$error.='請輸入生日月份!\r\n';}
	if(empty($bday)){$error.='請輸入生日日期!\r\n';}
 	if(!empty($byear)&&!empty($bmonth) && !empty($bday)){if(!checkdate($bmonth,$bday,$byear)){$error.='生日沒有這一天喔!\r\n';}  } 
	if(is_null($sex)){$error.='請輸入性別!\r\n';}
	if(!empty($mobile_country_code) && !preg_match('/^\d{1,}$/', $mobile_country_code)){$error.='手機國碼 必須為數字\r\n';} 
	if(!empty($mobile) && !preg_match('/^\d{1,}$/', $mobile)){$error.='手機號碼 必須為數字\r\n';} 
	if(!empty($country) && !preg_match('/^\d{1,}$/', $country)){$error.='國家代碼 必須為數字\r\n';} 
	if(!empty($zip) && !preg_match('/^\d{1,}$/', $zip)){$error.='郵遞區號 必須為數字\r\n';} 
	if(empty($error)){ 
	 $db->query("UPDATE user SET account='$account',pw='$pw',mobile_country_code='$mobile_country_code',mobile='$mobile',nickname='$nickname',birthday='$birthday',sex='$sex',email='$email',country='$country',zip='$zip',address='$address',status='$status',ulevel='$ulevel',etime='$today',fmid='$fmid',intro='$intro' WHERE id='$_POST[id]'");
			$descrip="edit user_manage_edit.php id=$da[id] name=$name";
			$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('38','$id','$admin_d[uid]','2','$timeformat','$timestamp','$descrip')");
 			$error='ok';	
	 	}	
	}	
$user_sex=array(0=>'女',1=>'男');
$user_status=array(0=>'關閉',1=>'開啟');
$user_ulevel=array(0=>'未認證',1=>'已認證');
/*生日*/
$sql = "SELECT * FROM user where id=$id";
$rs = $db->query($sql);
$r = mysql_fetch_assoc($rs);
$byear = (int)substr($r['birthday'], 0, 4);
$by=str_pad($byear, 4, '0', STR_PAD_LEFT);
$bmonth = (int)substr($r['birthday'], 5, 2);
$bm=str_pad($bmonth, 2, '0', STR_PAD_LEFT);
$bday = (int)substr($r['birthday'], 8, 2);
$bd=str_pad($bday, 2, '0', STR_PAD_LEFT);
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
				
var cpwRegExp = /^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/;
		cpw=strim(document.form1.pw.value);
	if(form1.pw.value != "" && !cpwRegExp.test(cpw)){
		message+='密碼需6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號\r\n';
		ierror=1;
				}	               
	if(form1.pw.value != form1.pw2.value){
		form1.pw.value=="";
		form1.pw2.value=="";
		form1.pw.focus();
		message+='兩次輸入密碼不同\r\n';
		ierror=1;	  
				}	              
 	if (form1.name.value == "") {
		message+='請輸入姓名\r\n';
		ierror=1;
				}
	if (form1.sex.value == "") {
		message+='請選擇性別\r\n';
		ierror=1;
				}			 	
	if (form1.byear.value == "") {
		message+='請選擇生日年分\r\n';
		ierror=1;
				}				
	if (form1.bmonth.value == "") {
		message+='請選擇生日月份\r\n';
		ierror=1;
				}				
	if (form1.bday.value == "") {
		message+='請選擇生日日期\r\n';
		ierror=1;
				}	
var celRegExp =  /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
		cel=strim(document.form1.email.value );
	if(!celRegExp.test(cel)){
		message+='請輸入信箱格是錯誤 \r\n';
		ierror=1;
		}			
	if (form1.status.value == "") {
		message+='請選擇帳號狀態\r\n';
		ierror=1;
				}							
	if (form1.ulevel.value == "") {
		message+='請選擇認證狀態\r\n';
		ierror=1;
				}					
var cmccRegExp =  /^\d{1,}$/;
		cmcc=strim(document.form1.mobile_country_code.value );				
	if (!form1.mobile_country_code.value == "" && !cmccRegExp.test(cmcc)) {
		message+='手機國碼 必須為數字\r\n';
		ierror=1;
				}							
var cmRegExp =  /^\d{1,}$/;
		cm=strim(document.form1.mobile.value );				
	if (!form1.mobile.value == "" && !cmRegExp.test(cm)) {
		message+='手機 必須為數字\r\n';
		ierror=1;
				}							
var ccRegExp =  /^\d{1,}$/;
		cc=strim(document.form1.country.value );				
	if (!form1.country.value == "" && !ccRegExp.test(cc)) {
		message+='國家代碼 必須為數字\r\n';
		ierror=1;
				}							
var czRegExp =  /^\d{1,}$/;
		cz=strim(document.form1.zip.value );				
	if (!form1.zip.value == "" && !czRegExp.test(cz)) {
		message+='郵遞區號 必須為數字\r\n';
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
			<td class="tableTitle" colspan="10">人員修改</td>
		</tr>
		<tr>
			<td align="center">帳號</td>
			<td><?=$da['account']?></td>
			<input type="hidden" name="id" value="<?=$da['id']?>">	
			<input type="hidden" name="account" value="<?=$da['account']?>">			
		</tr>
		<tr>
			<td align="center">密碼</td>
			<td><input type="password"  name="pw" id="pw"  style="width:200px;" /></td>
		</tr>
		<tr>					 
			<td width="150" align="center">再重複一次密碼</td>
			<td><input type="password" name="pw2" id="pw2" style="width:200px;"></td>
		</tr>					
		<tr>				
			<td align="center">姓名</td>
			<td><input type="text" name="name"  value="<?=$da['name']?>"/><font color="#FF0000">*</font></td>
		</tr>
		<tr>				
			<td align="center">暱稱</td>
			<td><input type="text" name="nickname" value="<?=$da['nickname']?>" /><font color="#FF0000">*</font></td>				
		</tr>
		<tr>
			<td align="center">性別</td>
			<td>
				<select name="sex" id="sex">
					<option value="<?= $da['sex']; ?>" ><?= $user_sex[$da['sex']]; ?></option>
					<?foreach($user_sex as $key => $value){?>
					<option value="<?=$key?>"><?= $value; ?></option>
					<?}?>
				</select>					
				<font color="#FF0000">*</font>					
			</td>
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
		<tr>
			<td align="center">信箱</td>
			<td><input type="text" name="email" style="width:300px;" value="<?=$da['email']?>" /><font color="#FF0000">*</font>	</td>
		</tr>
		<tr>
			<td align="center">帳號狀態</td>
			<td><input name="status" id="status" type="radio" value="0" <? if ($da[ 'status']=='0' ) { ?> checked="checked"
				<? } ?> /> 關閉&nbsp;&nbsp;
				<input type="radio" name="status" id="status" value="1" <? if ($da[ 'status']=='1' ) { ?> checked="checked"
				<? } ?> /> 開啟
			<font color="#FF0000">*</font>	
			</td>
		</tr>		
		<tr>
			<td align="center">認證狀態</td>
			<td><input name="ulevel" id="ulevel" type="radio" value="0" <? if ($da[ 'ulevel']=='0' ) { ?> checked="checked"
				<? } ?> /> 未認證&nbsp;&nbsp;
				<input type="radio" name="ulevel" id="ulevel" value="1" <? if ($da[ 'ulevel']=='1' ) { ?> checked="checked"
				<? } ?> /> 已認證				
				<font color="#FF0000">*</font>					
			</td>
		</tr>		
		<tr>
			<td align="center">手機國碼 / 手機 </td>
			<td><input type="text" name="mobile_country_code" style="width:50px;" value="<?=$da['mobile_country_code']?>" /> / <input type="text" name="mobile" style="width:200px;" value="<?=$da['mobile']?>" /></td>
		</tr>
		<tr>
			<td align="center">國家代碼</td>
			<td><input type="text" name="country" value="<?=$da['country']?>" /></td>
		</tr>		
		<tr>
			<td align="center">郵遞區號 / 地址</td>
			<td><input type="text" name="zip" style="width:50px;" value="<?=$da['zip']?>" /> / <input type="text" name="zip" style="width:300px;" value="<?=$da['zip']?>" /></td>
		</tr>				
		<tr>
			<td align="center">外部註冊ID</td>
			<td><input type="text" name="fmid" value="<?=$da['fmid']?>" /></td>
		</tr>			
		<tr>
			<td align="center">備註</td>
			<td>
				<textarea  name="intro" id="intro" style="width:200px;" value="<?=$da['intro']?>"><?=$da['intro']?> </textarea>
			</td>			
		</tr>		
		<tr>				
			<td align="center">註冊IP</td>
			<td><?=$da['cip']?></td>	
		</tr>				
		<tr>				
			<td align="center">建立時間</td>
			<td><?=$da['ctime']?></td>
			<input type="hidden" name="ctime" value="<?=$da['ctime']?>">					
		</tr>				
		<tr>				
			<td align="center">編輯時間</td>
			<td><?=$da['etime']?></td>
			<input type="hidden" name="etime" value="<?=$da['etime']?>">					
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