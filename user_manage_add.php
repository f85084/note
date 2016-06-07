<? session_start();
include('include/no_login.php');
include('../include/common.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='a41';
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
$query = $db->query("SELECT * FROM admin_info ");
$error='';
$today=date("Y,m,d H:i:s");
$etime=0000-00-00;
$fm='後台管理者';
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
	$ctime=$_POST['ctime'];
	$cip=$_POST['cip'];
	$fmid=$_POST['fmid'];
	$intro=$_POST['intro'];
	$ip = $_SERVER["REMOTE_ADDR"];
echo $ip ;	
	if(!preg_match('/^[a-z0-9]{1,}$/',$account)){$error.='帳號 必須符合 大小寫英文數字\r\n';}	
	$query_rid = $db->query("SELECT COUNT(*) FROM user where account='$account'");
	$q_rid = $db->fetch_row($query_rid);
	$res_rid = $q_rid[0];	
	if($res_rid!=0){$error.='帳號已有人使用!\r\n';}
	if(!preg_match('/^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/', $_POST['pw'])){$error.='6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號\r\n';} 
	if(empty($name)){$error.='請輸入姓名!\r\n';}
	if(empty($byear)){$error.='請輸入生日年分!\r\n';}
	if(empty($bmonth)){$error.='請輸入生日月份!\r\n';}
	if(empty($bday)){$error.='請輸入生日日期!\r\n';}
 	if(!empty($byear)&&!empty($bmonth)&&!empty($bday)){if(!checkdate($bmonth,$bday,$byear)){$error.='生日沒有這一天喔!\r\n';}  } 
	if(is_null($sex)){$error.='請輸入性別!\r\n';}
	if(empty($error)){ 
			$querya=$db->query("INSERT INTO user (id,account,mobile_country_code,mobile,name,nickname,sex,birthday,email,zip,address,status,ulevel,ctime,etime,cip,fm,fmid,intro)
			VALUES ('$id','$account','$mobile_country_code','$mobile','$name','$nickname','$sex','$birthday','$email','$zip','$address','$status','$ulevel','$today','$etime','$ip','$fm','$fmid','$intro')");
 			$id=$db->insert_id();
			$descrip="add manage_add.php account=$account name=$name";
			$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('41','$user_name','$admin_d[uid]','1','$timeformat','$timestamp','$descrip')");
			$error='ok';	 
	 	}	
	}		
$user_sex=array(0=>'女',1=>'男');
$user_status=array(0=>'關閉',1=>'開啟');
$user_ulevel=array(0=>'未認證',1=>'已認證');
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
				
	if (form1.account.value == "") {
		message+='請輸入帳號\r\n';
		ierror=1;
				}	
var caRegExp =  /^(?=.*[a-zA-Z])(?=.*[.-_])(?!.*[^\x21-\x7e])(?!.*[\@#$%^&+=!]).{1,}$/;				
		ca=strim(document.form1.account.value );
	if(!caRegExp.test(ca)){
		message+='帳號格是錯誤  必須符合 大小寫英文數字 .-_\r\n';
		ierror=1;
		}	 
var cpwRegExp = /^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/;
		cpw=strim(document.form1.pw.value);
	if(!cpwRegExp.test(cpw)){
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
var celRegExp =  /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
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
	if (form1.ulevel.value == "") {
		message+='請選擇認證狀態\r\n';
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
			<td class="tableTitle" colspan="10">新增人員</td>
		</tr>
		<tr>
			<td align="center">帳號</td>
			<td><input type="text" name="account" id="account" style="width:200px;" /><font color="#FF0000">*</font>	</td>
		</tr>
		<tr>
			<td align="center">密碼</td>
			<td><input type="password"  name="pw" id="pw"  style="width:200px;" /><font color="#FF0000">*</font>	</td>
		</tr>
		<tr>					 
			<td width="150" align="center">再重複一次密碼</td>
			<td><input type="password" name="pw2" id="pw2" style="width:200px;"> <font color="#FF0000">*</font>	</td>
		</tr>					
		<tr>				
			<td align="center">姓名</td>
			<td><input type="text" name="name" id="name" style="width:200px;" /> <font color="#FF0000">*</font>	</td>				
		</tr>
		<tr>				
			<td align="center">暱稱</td>
			<td><input type="text" name="nickname" id="nickname" style="width:200px;" /> <font color="#FF0000">*</font>	</td>				
		</tr>
		<tr>
			<td align="center">性別</td>
			<td>
				<select name="sex" id="sex">
					<option value="" >請選擇性別</option>
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
					<option value=""></option>
					<? for ($i=1960; $i<=2016; $i++) {?>
					<option value="<?=$i?>"><?= $i; ?></option>
					<? } ?>
				</select> 年
				<select name="bmonth" id="bmonth">
					<option value=""></option>
					<? for ($i=1; $i<=12; $i++) {?>
					<option value="<?=$i?>" ><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
				</select>月
				<select name="bday" id="bday">
					<option value=""></option>
					<? for ($i=1; $i<=31; $i++) {?>
					<option value="<?=$i?>" ><?=  str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
				</select>日	
				<font color="#FF0000">*</font>						
			</td>	  
		</tr>
		<tr>
			<td align="center">信箱</td>
			<td><input type="text" name="email" id="email" style="width:200px;" /><font color="#FF0000">*</font>	</td>
		</tr>
		<tr>
			<td align="center">帳號狀態</td>
			<td><input type="radio" value="0" name="status" checked="checked" /> 關閉&nbsp;&nbsp;<input type="radio" value="1" name="status" />開啟
			<font color="#FF0000">*</font>	
			</td>
		</tr>		
		<tr>
			<td align="center">認證狀態</td>
				<td><input type="radio" value="0" name="ulevel" checked="checked" /> 未認證&nbsp;&nbsp;<input type="radio" value="1" name="ulevel" />已認證
				<font color="#FF0000">*</font>					
			</td>
		</tr>		
		<tr>
			<td align="center">手機國碼</td>
			<td><input type="text" name="mobile_country_code" id="mobile_country_code" style="width:200px;" /></td>
		</tr>
		<tr>
			<td align="center">手機</td>
			<td><input type="text" name="mobile" id="mobile" style="width:200px;" /></td>
		</tr>
		<tr>
			<td align="center">國家代碼</td>
			<td><input type="text" name="country" id="country" style="width:200px;" /></td>
		</tr>		
		<tr>
			<td align="center">郵遞區號</td>
			<td><input type="text" name="zip" id="zip" style="width:200px;" /></td>
		</tr>		
		<tr>
			<td align="center">地址</td>
			<td><input type="text" name="address" id="address" style="width:200px;" /></td>
		</tr>			
		<tr>
			<td align="center">外部註冊ID</td>
			<td><input type="text" name="fmid" id="fmid" style="width:200px;" /></td>
		</tr>			
		<tr>
			<td align="center">備註</td>
			<td><input type="text" name="intro" id="intro" style="width:200px;" /></td>
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
alert('新增成功');
parent.referu('index.php?pid=41');
</script>
<? }elseif(!empty($error)){?>
<script>
alert('<?=$error?>');
history.go(-1)
</script>
<? }?>

</body>
</html>