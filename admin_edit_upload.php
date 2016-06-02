<? session_start();
include('include/no_login.php');
include('../include/common.php');
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid='31';
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
?>
<!DOCTYPE html>
<html lang=en><head><meta charset=utf-8>
<head>
<title>修改</title>
</head>

<?
if(isset($_POST['action']) && $_POST['action']=='update'){
	$_POST=m_esc(ck_gp($_POST));
	$error='';
	$set='';
	$uid=$_POST[uid];
	$name=$_POST[name];
	$email=$_POST[email];
	$birthday=$_POST[birthday];
	$phone=$_POST[phone];
	$cellphone=$_POST[cellphone];
	if(!empty($_POST['pass_word']) && strlen($_POST['pass_word']) <6){$error.='密碼輸入太短\r\n';}
	if(!preg_match('/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/',$email)){$error.='請輸入信箱 必須有@ \r\n';}	
	if(strlen($birthday)!=10 && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$birthday)){$error.='日期輸入錯誤 !\r\n';}
	if(strlen($_POST['phone']) <4){$error.='分機號碼太短 長度為 4\r\n';}
	if(strlen($name) <2){$error.='請輸入姓名 至少兩個字元\r\n';}
	if(!preg_match('/^[0-9]{4}$/',$phone)){$error.='分機號碼必須為4位 數字\r\n';}	
    if(strlen($_POST['cellphone']) <10){$error.='手機輸入太短\r\n';}
	if(!preg_match('/^[09]{2}[0-9]{8}$/',$cellphone)){$error.='請輸入手機號碼，開頭為09 長度為10位數\r\n';}
	if(!empty($_POST['pass_word'])){
		$pass_word=md5($_POST['pass_word']);
		$set="pass_word='$pass_word',";			
	if(!preg_match('/^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/', $_POST['pass_word'])){$error.='6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號\r\n';}
	} 
	if(empty($error)){
		$db->query("update admin_info set $set name='$name',birthday='$birthday' ,phone='$phone' ,cellphone='$cellphone',email='$email' where uid='$uid'");
		$descrip="update admin_edit_upload.php uid=$_POST[uid] user_name=$_POST[user_name] name=$_POST[name]";
		$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('31','$_POST[uid]','$admin_d[uid]','2','$timeformat','$timestamp','$descrip')");
		$error='ok';

	}			
}

?>
<? if($error=='ok'){?>
<script>
alert('更新成功');
location.href = 'index.php?pid=31';
</script>
<? }elseif(!empty($error)){?>
<script>
alert('<?=$error?>');
history.go(-1)
</script>
<? }?>
