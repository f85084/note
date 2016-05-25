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
$file='../logs/admin_all_thor.txt';
if(is_file($file)){
$allthor=file_get_contents($file);//讀取檔案內容
$allthors=explode(',',$allthor);  
}else{//如果依開始檔案不存在時 自己預設依個內容存進檔案
$allthor='1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,a3,e3,d3,a2,e2,d2,a5,e5,d5,a6,e6,d6,a8,e8,d8,a9,e9,d9,a11,e11,d11,a12,e12,d12,a13,e13,d13,a14,d14,a16,d16,17,18,19,20,a20,d20,21,22,23,a23,e23,d23,24,a24,e24,d24,25,26,26a,27,28,a28,e28,d28,all28l,29,a29,e29,d29,30,31,32,33,34,35,36,a36,e36,d36,37,38,a38,e38,d38,p38,39';//舉例
file_put_contents($file,$allthor);//存依個預設的
}
if(!empty($_POST['act']) && $_POST['act']=='add'){
	$_POST=m_esc(ck_gp($_POST));
	$pid=implode(',',$_POST['pid']);
	$group_uid=ck_num($_POST['group_uid'])?$_POST['group_uid']:0;

	if(empty($error)){ 
	 $db->query("UPDATE admin_info SET thor='$pid',group_uid='$group_uid' WHERE uid='$uid'");
 			$id=$db->insert_id();
			$descrip="add manage_purview.php uid=$uid user_name=$da[user_name]";
			$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('38','$id','$admin_d[uid]','1','$timeformat','$timestamp','$descrip')");
 		$error='ok';	
	 	}	
	}	
    $querya = $db->query("SELECT id,name FROM b_s_group order by id asc");
	$query_all = $db->query("SELECT uid,group_uid,user_name,thor FROM admin_info WHERE uid='35'");
	$all_id = $db->fetch_array($query_all);
	$all_array=explode(',',$all_id['thor']);

$thors=explode(',',$da['thor']);  
$admin_group=array(0=>'管理者',1=>'開發人員',2=>'編輯人員',3=>'網頁設計師')	;
$ad_g=$admin_group[$da['group_uid']];
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
				<td align="center">姓名</td>
                <td align="center"><?=$da['name']?></td>
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
        </table>
        <table cellpadding="0" cellspacing="0" class="menutable">
            <tr>
                <td align="center" width="10%">id</td>
                <td align="center" width="20%">系統名稱</td>
				<td align="center" width="70%">權限</td> 				
            </tr>
            <? while($ld = $db->fetch_array($querya)){?>
                <tr class="chbg">
				<td align="center"><?=ht($ld['id'])?><input type="hidden" name="id" value="<?=$ld['id']?>"></td>
				<td align="center"><?=$ld['name']?></td>
			   <td>
			 <? $pid=ht($ld['id']); 
					if(in_array($pid,$allthors)){?><input type="checkbox" name="pid[]" id="pid[]" value="<?=$pid?>"<?=in_array($pid,$thors)? ' checked="checked"': '';?> >
					<?='預覽'?>&nbsp;&nbsp;		
						<? }?>	
			 <? $apid='a'.ht($ld['id']); 
					if(in_array($apid,$allthors)){?><input type="checkbox" name="pid[]" id="pid[]" value="<?=$apid?>"<?=in_array($apid,$thors)? ' checked="checked"': '';?>  >
					<?='新增'?>&nbsp;&nbsp;		
						<? }?>	
			 <? $epid='e'.ht($ld['id']); 
					if(in_array($epid,$allthors)){?><input type="checkbox" name="pid[]" id="pid[]" value="<?=$epid?>"<?=in_array($epid,$thors)? ' checked="checked"': '';?>  >
					<?='編輯'?>&nbsp;&nbsp;		
						<? }?>	
			 <? $dpid='d'.ht($ld['id']); 
					if(in_array($dpid,$allthors)){?><input type="checkbox" name="pid[]" id="pid[]" value="<?=$dpid?>"<?=in_array($dpid,$thors)? ' checked="checked"': '';?>  >
					<?='刪除'?>&nbsp;&nbsp;		
						<? }?>	
			 <? $ppid='p'.ht($ld['id']); 
					if(in_array($ppid,$allthors)){?><input type="checkbox" name="pid[]" id="pid[]" value="<?=$ppid?>"<?=in_array($ppid,$thors)? ' checked="checked"': '';?>  >
					<?='權限'?>&nbsp;&nbsp;		
						<? }?>	
			 <? $allpid='all'.ht($ld['id']).'l'; 
					if(in_array($allpid,$allthors)){?><input type="checkbox" name="pid[]" id="pid[]" value="<?=$allpid?>"<?=in_array($allpid,$thors)? ' checked="checked"': '';?>  >
					<?='全部'?>&nbsp;&nbsp;		
						<? }?>	
			</td> 
                </tr>
                <? }?>

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