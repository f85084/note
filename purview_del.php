<? session_start();
include('include/no_login.php');
include('../include/common.php');
include('../include/color_array.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='p38';
 if(!in_array($thorid,$p_array) || empty($_GET['uid'])){header("Location:index.php"); exit();} 
$_GET=ck_gp($_GET);
$uid=m_esc($_GET['uid']);
$query=$db->query("SELECT * FROM admin_info where uid=$uid");
$da=$db->fetch_array($query);
if(empty($da['uid'])){header("Location:index.php"); exit();} 
$error='';

if(!empty($_POST['act']) && $_POST['act']=='add'){
	$_POST=m_esc(ck_gp($_POST));
	$thor=$_POST['thor'];

	if(empty($error)){ 
	 $db->query("UPDATE admin_info SET thor='$thor' WHERE uid='$uid'");

 			$id=$db->insert_id();
			$descrip="add manage_purview.php uid=$uid user_name=$da[user_name]";
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
			
            if (form1.thor.value == "") {
                message+='請輸入群組\r\n';
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
                <td class="tableTitle" colspan="10">權限</td>
            </tr>
	
            <tr>
                <td align="center">權限</td>
				<td>
				<textarea  name="thor" id="thor" style="width:700px; height:100px;" value="<?=$da['thor']?>"><?=$da['thor']?></textarea></br>
				<font color="#FF0000">請以　,　半形逗號隔開</font>
				</td>
            </tr>								
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