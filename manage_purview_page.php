<? session_start();
include('include/no_login.php');
include('../include/common.php');
include('../include/color_array.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='p38';
 if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();} 
$_GET=ck_gp($_GET);

$query = $db->query("SELECT uid,user_name FROM admin_info WHERE uid='$_SESSION[admin_uid]' and del='N'");
$da = $db->fetch_array($query);

$error='';
$file='../logs/admin_all_thor.txt';
if(is_file($file)){
$allthor=file_get_contents($file);//讀取檔案內容
$allthors=explode(',',$allthor);  
/* echo $allthor; */
}else{//如果依開始檔案不存在時 自己預設依個內容存進檔案
$allthor='1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,a3,e3,d3,a2,e2,d2,a5,e5,d5,a6,e6,d6,a8,e8,d8,a9,e9,d9,a11,e11,d11,a12,e12,d12,a13,e13,d13,a14,d14,a16,d16,17,18,19,20,a20,d20,21,22,23,a23,e23,d23,24,a24,e24,d24,25,26,26a,27,28,a28,e28,d28,all28l,29,a29,e29,d29,30,31,32,33,34,35,36,a36,e36,d36,37,38,a38,e38,d38,p38,39,40';//舉例
file_put_contents($file,$allthor);//存依個預設的
}//編輯全部權限檔案內容時 在 把內容 file_put_contents($file,$allthor);
if(!empty($_POST['act']) && $_POST['act']=='add'){
	$_POST=m_esc(ck_gp($_POST));
 	$allthor=$_POST['allthor'];
	if(!empty($allthor)&&!preg_match('/^[a-z0-9,]{1,}$/',$allthor)){$error.='小寫英文a-z 或 數字0-9 以,半形逗號隔開 \r\n';} 
    file_put_contents($file,$allthor); 
	if(empty($error)){ 
  			$id=$db->insert_id();
			$descrip="add manage_purview_page.php uid=$da[uid] user_name=$da[user_name]";
			$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('38','$id','$admin_d[uid]','1','$timeformat','$timestamp','$descrip')");
 			$error='ok';	
	 	}	
	}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include('include/css_js.php');?>
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
			
</head>
    <body>
    <form name="form1" id="form1" action="" enctype="multipart/form-data" method="post" onSubmit="">	
        <table cellpadding="0" cellspacing="0" class="menutable">
            <tr>
				<td align="center" width="100%">權限頁面</td> 				
            </tr>
				<td align="center"><textarea align="center" name="allthor" id="allthor" style="height:150px;width:95%;" value="<?=$allthor?>"><?=$allthor?></textarea>
				</br><font color="#FF0000">請以　,　半形逗號隔開</font>	
				</td>

		    <tr>
                <td colspan="10" align="center">
                <div id="subm_1" style="height:20px;"><input type="button" value="送出" onclick="check_empty(this.form)" /><input type="hidden" name="act" value="add" /></div>
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