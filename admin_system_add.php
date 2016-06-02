<? session_start();
include('include/no_login.php');
include('../include/common.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='a36';
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
$query = $db->query("SELECT * FROM b_s_group ");
$error='';
$queryn = $db->query("select id,name from b_s_group  where up_id=0");
if(!empty($_POST['act']) && $_POST['act']=='add'){
	$_POST=m_esc(ck_gp($_POST));
	$up_id=$_POST['up_id'];
	$name=$_POST['name'];
	$sortn=$_POST['sortn'];
	$del=$_POST['del'];
	$programs_p=$_POST['programs_p'];
	if(!preg_match("/^\d{1,}$/",$up_id)){$error.='請輸入上層ID!\r\n';}	
	if(empty($name)){$error.='請輸入系統名稱!\r\n';}
	if(!preg_match('/^\d{1,}$/',$sortn)){$error.='請輸入排序!\r\n';} 
	if($up_id!=0 && !preg_match("/^(?=.*[a-zA-Z])(?=.*[.-_])(?!.*[^\x21-\x7e])(?!.*[\@#$%^&+=!]).{1,}$/", $programs_p)){
	$error.='程式名稱 必須符合 大小寫英文數字 .-_ \r\n';}
	if(empty($error)){ 
			$querya=$db->query("INSERT INTO b_s_group (up_id, name, sortn, del, programs_p) VALUES ('$up_id','$name', '$sortn','$del', '$programs_p')");
			$id=$db->insert_id();
			$descrip="add admin_system_add.php id=$id name=$name";
			$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('36','$id','$admin_d[uid]','1','$timeformat','$timestamp','$descrip')");
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
	var cidRegExp = /^\d{1,}$/;
	cid=strim(document.form1.up_id.value);
	if(!cidRegExp.test(cid)){
		message+='請輸入上層ID!\r\n';
		ierror=1;
	}		
    if (form1.name.value == "") {
		message+='請輸入系統名稱\r\n';
		ierror=1;
	}
	var csRegExp = /^\d{1,}$/;
	cs=strim(document.form1.sortn.value);
	if(!csRegExp.test(cs)){
		message+='請輸入排序! 只能輸入數字\r\n';
		ierror=1;
	}				
	var cpRegExp = /^(?=.*[a-zA-Z])(?=.*[.-_])(?!.*[^\x21-\x7e])(?!.*[\@#$%^&+=!]).{1,}$/;				
	cp=strim(document.form1.programs_p.value);
	if (form1.up_id.value !=0 && !cpRegExp.test(cp)) {
		message+='程式名稱 必須符合 大小寫英文數字 .-_ \r\n';
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
                <td class="tableTitle" colspan="10">後台子系統</td>
            </tr>

            <tr>
                <td align="center">上層ID</td>
				<td>
					<select name="up_id" id="up_id">
					<option value="" >請選擇上層ID</option>
					<option value="0">0：為主要階層</option>
					<? while($r =mysql_fetch_assoc($queryn)){ ?>
					<option value="<?= $r["id"]; ?>"><?= $r["id"]; ?>：<?= $r["name"]; ?></option>
					<? } ?>
					</select>
                </td>
			</tr>
                <tr>
                <td align="center">系統名稱</td>
                <td><input type="text" name="name" id="name" style="width:300px;" /></td>
                </tr>
                <tr>
                    <td align="center">排序</td>
                    <td><input type="text" value="0" name="sortn" id="sortn"  style="width:60px" /></td>
					</tr>
                <tr>
                    <td align="center">刪除</td>
                    <td><input type="radio" value="0" name="del" checked="checked" /> 否&nbsp;&nbsp;<input type="radio" value="1" name="del" />是</td>
					</tr>
                <tr>
                    <td align="center">程式名稱</td>
                <td><input type="text" name="programs_p" id="programs_p" style="width:300px;" /></td>
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
parent.referu('index.php?pid=36');
</script>
<? }elseif(!empty($error)){?>
<script>
alert('<?=$error?>');
history.go(-1)
</script>
<? }?>

</body>
</html>