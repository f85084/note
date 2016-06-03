<? // 個人資料修改
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid='31';
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
$where='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$lurl='';
if(!empty($_SESSION['admin_uid'])){
	$query = $db->query("SELECT uid,group_uid,user_name,pass_word,name,birthday,phone,cellphone,thor,email FROM admin_info WHERE uid='$_SESSION[admin_uid]' and del='N'");
	$admin_d = $db->fetch_array($query);
}
$rownum = 5; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$querya=$db->query("SELECT id,uid,name,ip,times,in_t FROM  admin_login_log WHERE  uid='$_SESSION[admin_uid]' order by times desc"." LIMIT $TP, $rownum");
/*寫入log*/
$descrip="view admin_edit.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");
/*編輯者*/
$querye = $db->query("SELECT uid,user_name from admin_info");
$eAdmin=array();
while($e = $db->fetch_array($querye)){
	$eAdmin[$e['uid']]=$e['user_name'];
}
$use_in=array(0=>'失敗', 1=>'成功');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">	
<head>
<script language="JavaScript">
function strim(str){
	return str.replace(/(^\s*)|(\s*$)/g, "");
		}
function check_empty() {
	ierror = 0;
	message = '';	
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
	if (form1.name.value.length < 2 ) {
		message+='請輸入姓名 至少兩個字元\r\n';
		ierror=1;
		}				
var celRegExp =  /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
		cel=strim(document.form1.email.value );
	if(!celRegExp.test(cel)){
		message+='請輸入信箱格是錯誤 \r\n';
		ierror=1;
		}				
var cbdRegExp =  /^([0-9]{4})[\-]{1}([0-9]{2})[\-]{1}([0-9]{2})$/;
		cbd=strim(document.form1.birthday.value );
	if(!cbdRegExp.test(cbd)){
		message+='生日輸入錯誤喔 \r\n';
		ierror=1;
		}	
var cpeRegExp =  /^\d{4}$/;
		cpe=strim(document.form1.phone.value);
	if(!cpeRegExp.test(cpe)){
		message+='請輸入分機號碼入長度為 4\r\n';
		ierror=1;
		}
var cceRegExp =  /^[09]{2}[0-9]{8}$/;
		cce=strim(document.form1.cellphone.value );
	if(!cceRegExp.test(cce)){
		message+='請輸入手機號碼，開頭為09 長度為10位數\r\n';
		ierror=1;
				}							 				
	if(ierror ==1){ 
		alert(message);
	}else{
		//以下為ajax部分
		if(form1.pass_word.value == form1.pass_word2.value && form1.pass_word.value!='' && form1.pass_word2.value!=''){
			if(form1.old_pass_word.value == ''){
				alert('請輸入舊密碼！');
			}else{
				var old_pass_word = $('#old_pass_word').val();
				$.ajax({
					 url : "admin_edit_ck.php", 
					 data : { old_pass_word : old_pass_word}, 
					 type : "POST", 
					 dataType : "text", 
					 error : function(xhr){ 
						   //is_auth_passed = false;
						 },
					 success : function(response){ 
						if  (response == '1') {
							//is_auth_passed = true;
							//alert(is_auth_passed);	
							//alert('1');
							document.form1.submit(); 						
						} else {
							//is_auth_passed = false;
							//alert(is_auth_passed);
							alert('舊密碼錯誤！');
						}
						//alert(response);
						}
						});		
				}
		}else{//如果不改密碼就不檢查
			document.form1.submit(); 
		}									
	 }
  }         
</script> 	
<style type="text/css">
	@import "include/datepick/jquery.datepick.css";
</style>
<script type="text/javascript" src="include/datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="include/datepick/jquery.datepick-zh-TW.js"></script>
</head>	
<body>
<div class="right_b">
	<form name="form1" id="form1" action="admin_edit_upload.php" enctype="multipart/form-data" method="post" onSubmit="" >
		<table cellpadding="0" cellspacing="0" class="menutable" height="100%">
			<tr>
				<td class="tableTitle" colspan="10">個人資料修改</td>
			</tr>
			<tr>
				<td width="150" align="center">會員編號</td>
				<td><?=$admin_d['uid']?></td>
				<input type="hidden" name="uid" value="<?=$admin_d['uid']?>">
			</tr>
			<tr>
				<td width="150" align="center">登入帳號</td>
				<td><?=$admin_d['user_name']?></td>
				<input type="hidden" name="user_name" value="<?=$admin_d['user_name']?>">
			</tr>
			<tr>					 
				<td width="150" align="center">舊密碼</td>
				<td><input type="password" name="old_pass_word" id="old_pass_word"  placeholder="舊密碼"><font color="#FF0000">*變更密碼時 須填寫</font></td>
			</tr>				
			<tr>					 
				<td width="150" align="center">修改密碼</td>
				<td><input type="password" name="pass_word" id="pass_word"  placeholder="修改密碼">  </td>
			</tr>				
			<tr>					 
				 <td width="150" align="center">再重複一次密碼</td>
				 <td><input type="password" name="pass_word2"   placeholder="再重複一次密碼" >  </td>
			</tr>
			<tr>
				<td width="150" align="center">姓名</td> 
				<td><input type="text" name="name"  id="name" placeholder="姓名" value="<?=$admin_d['name']?>"> </td>
			</tr>
			<tr>
				<td width="150" align="center">信箱</td> 
				<td><input type="text" name="email" id="email"  placeholder="信箱" value="<?=$admin_d['email']?>"> </td>
			</tr>
			<tr>
				<td width="150" align="center">生日</td>
				<td><input type="text" name="birthday" id="birthday"  class="date_pick"  placeholder="生日" value="<?=$admin_d['birthday']?>"> </td>
			</tr>
			<tr>				
				<td width="150" align="center">分機</td> 
				<td><input type="text" name="phone" id="phone"   placeholder="分機" value="<?=$admin_d['phone']?>"> </td>
			</tr>
			<tr>				 
				<td width="150" align="center">手機</td> 
				<td><input type="text" name="cellphone" id="cellphone"   placeholder="手機" value="<?=$admin_d['cellphone']?>"> </td>	
			</tr>
			<tr>
				<td colspan="9"><input type=button value="送出" onClick="check_empty(this.form)" /><input type="hidden" name="pid" value="<?=ht($_GET['pid'])?>" /><input type="hidden" name="action" value="update" /></td>
			</tr> 
		</table>
	</form>
</div>
 <div class="right_b">
	<form name="form2" id="form2" action="" enctype="multipart/form-data" method="post" onsubmit="">
		<table cellpadding="0" cellspacing="0" class="menutable" height="100%">
			<tr>
				<td class="tableTitle" colspan="10">最近五次登入紀錄</td>
			</tr>
				<tr>
					<td width="15%" align="center">序</td>					
					<td width="15%" align="center">使用者ID</td>						
					<td width="15%" align="center">帳號</td>
					<td width="20%" align="center">IP</td>
					<td width="20%" align="center">登入時間</td>
					<td width="15%" align="center">登入狀態</td>
				</tr>				
			<? $i=0; $black='#FFFFCC'; $bg=($i%2==1)?$black:'';?>
			<? while($al = $db->fetch_array($querya)){?>
				<tr class="chbg" bgcolor="<?=$bg?>">
					<td align="center">
						<?php if($_GET["ToPage"] != "") { echo ((($_GET["ToPage"]-1)*$rownum)+1+$i);} else { echo ($i+1); }?>
					</td>								
					<td align="center">
						<?=$eAdmin[$al['uid']]?>
					</td>
					<td align="center">
						<?=ht($al['name']) ?>
					</td>
					<td align="center">
						<?=$al['ip']?>
					</td>  								
					<td align="center">
					   <!--  <?=$al['times']?> -->
						<?=gmdate('Y-m-d H:i:s',$al['times']+8*3600);?>											
					</td>  		
					<td align="center">
						<?=' '.$use_in[$al['in_t']]?>
					</td>  									
				</tr>
			<? ++$i; }?>
		</table>
	</form>
</div> 
<script type="text/javascript">
	$(".date_pick").datepick({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		showCurrentAtPos: 0,
		showOn: 'both',
		buttonImageOnly: true,
		buttonImage: 'include/datepick/calendar.gif'
	});
</script>
</body>