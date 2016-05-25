<? session_start();
include('include/no_login.php');
include('../include/common.php');
include('../include/color_array.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='a39';
 if(!in_array($thorid,$p_array) || empty($_GET['id'])){header("Location:index.php"); exit();} 
$_GET=ck_gp($_GET);
$id=m_esc($_GET['id']);
$apid='a'.$id.',';
$query=$db->query("SELECT * FROM admin_info where uid=$uid");
$da=$db->fetch_array($query);
$error='';

$querya=$db->query("SELECT uid,thor FROM admin_info");

if(!empty($_POST['act']) && $_POST['act']=='add'){
	$_POST=m_esc(ck_gp($_POST));
	$thor=$_POST['thor'];

	if(empty($error)){ 

	 $db->query("UPDATE admin_info SET thor='$thor' WHERE uid='$uid'");

/*  		$id=$db->insert_id();
			$descrip="add manage_add.php uid=$uid user_name=$da[user_name]";
			$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('38','$id','$admin_d[uid]','1','$timeformat','$timestamp','$descrip')");
 */			$error='ok';	
 						}  
	 	}	
$querye = $db->query("select uid,user_name from admin_info");
$id_n=array();
while($n = $db->fetch_array($querye)){
	$id_n[$n['uid']]=$n['user_name'];
}
$queryv = $db->query("select uid,user_name from admin_info");
$id_v=array();
while($v = $db->fetch_array($queryv)){
	$id_v[$v['user_name']]=$v['uid'];
} 


while($sa = $db->fetch_array($querya)){
						$id_t=$apid;
						$t=$sa[thor];
						if (strpos("$t","$id_t")!=0){ 
							echo $id_n[$sa[uid]].',' ;

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
			
/* 			var ctRegExp = /^[0-9]$/;
            	ct=strim(document.form1.thor.value);
            if(form1.thor.value != "" && !ctRegExp.test(ct)){
            	message+='必須為數字\r\n';
            	ierror=1;
            			}	 */		
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

				<textarea  name="ii" id="ii" style="width:700px; height:100px;" value=""><?while($sa = $db->fetch_array($querya)){
						$id_t=$apid;
						$t=$sa[thor];
						if (strpos("$t","$id_t")!=0){ 
							echo $id_n[$sa[uid]].',' ;

						} 
					 	}  ?>
		</textarea></br>
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