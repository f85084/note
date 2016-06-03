<? session_start();
include('include/no_login.php');
include('../include/common.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='d36';
if(!in_array($thorid,$p_array) || empty($_GET['id'])){header("Location:index.php"); exit();}
$_GET=ck_gp($_GET);
$id=m_esc($_GET['id']);
$query=$db->query("SELECT * FROM b_s_group where id=$id");
$da=$db->fetch_array($query);
if(empty($da['id'])){header("Location:index.php"); exit();}
$error='';
if(!empty($_POST['act']) && $_POST['act']=='add'){
		$db->query("UPDATE b_s_group SET del='1' WHERE id='$id'");
		$error='ok';
		$descrip="del admin_system_del.php id=$da[id] name=$da[name]";
		$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('36','$id','$admin_d[uid]','3','$timeformat','$timestamp','$descrip')");
}
$f_del=array(0=>'否', 1=>'是');
$querye = $db->query("select id,up_id,name from b_s_group");
$id_n=array();
while($n = $db->fetch_array($querye)){
	$id_n[$n['id']]=$n['name'];
} 
$id_in=$id_n[$da['up_id']];
?>
<script language="JavaScript">
function change_btn(st) {
	if (st == 'c') {
		document.getElementById("subm_1").innerHTML = '資料傳輸中';
	} else {
		document.getElementById("subm_1").innerHTML = '<input value="送  出" type="button" onclick="change_btn(' + "'c'" + ');check_empty(this,' + "'check'" + ',true);" /><input type="hidden" name="act" value="add" />';
	}
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
                <td class="tableTitle" colspan="10">後台子系統</td>
            </tr>
            <tr>
                <td width="150" align="center">id</td>
                <td><?=$da['id']?></td>
            </tr>
            <tr>
                <td align="center">上層ID</td>
                <td><?=$da['up_id']?>：<? if($da['up_id']=='0') { echo '為主要階層';}else{ echo"$id_in";}?></td>
			<tr>
                <td align="center">系統名稱</td>
                <td><?=$da['name']?></td>
			</tr>
			<tr>
                <td align="center">排序</td>
                <td><?=$da['sortn']?></td>
			</tr>
                <td align="center">刪除</td>
                <td><?=' '.$f_del[$da['del']]?></td>
			</tr>	
			<tr>
                <td align="center">程式名稱</td>
                <td><?=$da['programs_p']?></td>
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