<? session_start();
include('include/no_login.php');
include('../include/common.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='d41';
 if(!in_array($thorid,$p_array) || empty($_GET['id'])){header("Location:index.php"); exit();} 
$_GET=ck_gp($_GET);
$id=m_esc($_GET['id']);
$query=$db->query("SELECT * FROM user where id=$id");
$da=$db->fetch_array($query);
if(empty($da['id'])){header("Location:index.php"); exit();} 
$error='';
$today=date("Y,m,d H:i:s");
if(!empty($_POST['act']) && $_POST['act']=='add'){
		$db->query("UPDATE user SET status='0',etime='$today' WHERE id='$id'");
		$error='ok';
 		$descrip="del user_manage_del.php id=$da[id] name=$da[name]";
		$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('41','$id','$admin_d[uid]','3','$timeformat','$timestamp','$descrip')");
}
$user_sex=array(0=>'女',1=>'男');
$user_status=array(0=>'關閉',1=>'開啟');
$user_ulevel=array(0=>'未認證',1=>'已認證');
?>
<script language="JavaScript">
function change_btn(st) {
	if (st == 'c') {
		document.getElementById("subm_1").innerHTML = '資料傳輸中';
	} else {
		document.getElementById("subm_1").innerHTML = '<input value="送  出" type="button" onclick="change_btn(' + "'c'" + ');check_empty(this,' + "'check'" + ',true);" /><input type="hidden" name="act" value="add" />';
	}
}	
function check_empty() {
	ierror = 0;
	message = '';
						
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
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <? include('include/css_js.php');?>
            <style>
                #pre_pic img {
                    max-width: 700px;
                    max-height: 100px;
                    width: expression(this.width >700 && this.height < this.width ? 700: true);
                    height: expression(this.height > 100 ? 100: true)
                }
            </style>
</head>
<body>
<form name="form1" id="form1" action="" enctype="multipart/form-data" method="post" onSubmit="">
	<table cellpadding="0" cellspacing="0" class="menutable">
		<tr>
			<td class="tableTitle" colspan="10">人員刪除</td>
		</tr>         
		<tr>
			<td width="150" align="center">帳號</td>
			<td><?=$da['account']?></td>
		</tr>           
		<tr>
			<td width="150" align="center">姓名</td>
			<td><?=$da['name']?></td>
		</tr>           
		<tr>
			<td width="150" align="center">暱稱</td>
			<td><?=$da['nickname']?></td>
		</tr>           
		<tr>
			<td width="150" align="center">性別</td>
			<td><?=$user_sex[$da['sex']]?></td>
		</tr>           
		<tr>
			<td width="150" align="center">生日</td>
			<td><?=$da['birthday'] ?></td>
		</tr>               
		<tr>
			<td align="center">信箱</td>
			<td><?=$da['email']?></td>
		</tr>
		<tr>
			<td align="center">帳號狀態</td>
			<td><?=$user_status[$da['status']] ?>
			</td>
		</tr>		
		<tr>
			<td align="center">認證狀態</td>
			<td><?=$user_ulevel[$da['ulevel']]?>					
			</td>
		</tr>		
		<tr>
			<td align="center">手機國碼 / 手機 </td>
			<td><?=$da['mobile_country_code']?> / <?=$da['mobile']?></td>
		</tr>
		<tr>
			<td align="center">國家代碼</td>
			<td><?=$da['country']?></td>
		</tr>		
		<tr>
			<td align="center">郵遞區號 / 地址</td>
			<td><?=$da['zip']?> / <?=$da['zip']?></td>
		</tr>				
		<tr>
			<td align="center">外部註冊ID</td>
			<td><?=$da['fmid']?></td>
		</tr>			
		<tr>
			<td align="center">備註</td>
			<td><?=$da['intro']?></td>			
		</tr>		
		<tr>				
			<td align="center">註冊IP</td>
			<td><?=$da['cip']?></td>	
		</tr>				
		<tr>				
			<td align="center">建立時間</td>
			<td><?=$da['ctime']?></td>			
		</tr>				
		<tr>				
			<td align="center">編輯時間</td>
			<td><?=$da['etime']?></td>				
		</tr>						
			<tr>
				<td colspan="10" align="center">
					<div id="subm_1" style="height:20px; text-align:center"><input type="submit" value="刪　　　　除" onclick="check_empty(this,'check',true);" /></div><input type="hidden" name="act" value="add" />
				</td>
			</tr>
	</table>
</form>
<? if($error=='ok'){?>
<script>
	alert('刪除成功');
	parent.referu('');
</script>
<? }?>
</body>
</html>