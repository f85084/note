筆記本
<?
include('include/no_login.php');
include('../include/common.php');//global.func.php 裡面放很多檢查的fun



/*驗證空值*/
/*帳號密碼*/

//from <input type="hidden" name="action" value="update" /> 會帶到下面來檢查
if(isset($_POST['action']) && $_POST['action']=='update'){
	$_POST=m_esc(ck_gp($_POST));
	$error='';
	$set='';
	$uid=$_POST[uid];
	$name=$_POST[name];
	$birthday=$_POST[birthday];
	$phone=$_POST[phone];
	$cellphone=$_POST[cellphone];
	if(!empty($_POST['pass_word']) && strlen($_POST['pass_word']) <6){$error.='密碼輸入太短\r\n';}	//empty空  strlen 計算字元長度。//如果不等於空 和 字串長度 小於8 就會出現錯誤訊息
	if(strlen($birthday)!=10 && !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$birthday)){$error.='日期輸入錯誤 !\r\n';}
	if(strlen($_POST['phone']) <4){$error.='分機號碼太短 長度為 4\r\n';}
	if(!preg_match('/^[0-9]{4}$/',$phone)){$error.='分機號碼必須為4位 數字\r\n';}	
    if(strlen($_POST['cellphone']) <10){$error.='手機輸入太短\r\n';}
	if(!preg_match('/^[09]{2}[0-9]{8}$/',$cellphone)){$error.='請輸入手機號碼，開頭為09 長度為10位數\r\n';}
	if(!empty($_POST['pass_word'])){ 	// 如空為空 如果空值不等於$_POST['pass_word']  $set就會帶空的 如果有值就會帶"pass_word='$pass_word',"
		$pass_word=md5($_POST['pass_word']);
		$set="pass_word='$pass_word',";			
	if(!preg_match('/^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/', $_POST['pass_word'])){$error.="6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號";}
	} 
	if(empty($error)){
		$db->query("update admin_info set $set name='$name',birthday='$birthday' ,phone='$phone' ,cellphone='$cellphone' where uid='$uid'");
		$descrip="update admin_edit_upload.php uid=$_POST[uid] user_name=$_POST[user_name] name=$_POST[name]";
		$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('31','$_POST[uid]','$admin_d[uid]','2','$timeformat','$timestamp','$descrip')");
		$error='ok';

	}			
}


/*驗證欄位 小寫英文a-z 或 數字0-9 以,半形逗號隔開*/
	if(!empty($allthor)&&!preg_match('/^[a-z0-9,]{1,}$/',$allthor)){$error.='小寫英文a-z 或 數字0-9 以,半形逗號隔開 \r\n';} 
?>
<script language="JavaScript">
        function strim(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
            	}
        function check_empty() {
            ierror = 0;
            message = '';	
			
		var catRegExp = /^[a-z0-9,]{1,}$/;
			cat=strim(document.form1.allthor.value);
		if(form1.allthor.value != "" && !catRegExp.test(cat)){
			message+='小寫英文a-z 或 數字0-9 以,半形逗號隔開 \r\n';
			ierror=1;
					}	  
																
					if(ierror ==1){ 
						alert(message);
					}else{
					document.form1.submit(); 
					}
                }        
            </script> 