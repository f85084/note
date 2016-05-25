<? session_start();
include('include/no_login.php');
include('../include/common.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='a38';
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
$query = $db->query("SELECT * FROM admin_info ");
$error='';
$today=date("Y,m,d");
$close=0000-00-00;

if(!empty($_POST['act']) && $_POST['act']=='add'){
	$_POST=m_esc(ck_gp($_POST));
	$group_uid=ck_num($_POST['group_uid'])?$_POST['group_uid']:0;
	$user_name=$_POST['user_name'];
	$pass_word=md5($_POST['pass_word']);	
	$name=$_POST['name'];
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day =$_POST['day'];
	$birthday = date("$year,$month,$day");
	$sex=ck_num($_POST['sex'])?$_POST['sex']:0;
	$blood=ck_num($_POST['blood'])?$_POST['blood']:0;
	$marry=ck_num($_POST['marry'])?$_POST['marry']:0;
	$remark=$_POST['remark'];
	$user_level=$_POST['user_level'];
	$settime=$_POST['settime'];
	$del=$_POST['del'];
	$is_lock=$_POST['is_lock'];
		
	if(is_null($group_uid)){$error.='請輸入群組!\r\n';}
	if(!preg_match('/^(?=.*[a-zA-Z])(?!.*[^\x21-\x7e])(?!.*[\@#$%^&+=!]).{1,}$/',$user_name)){$error.='帳號 必須符合 大小寫英文數字\r\n';}	
	$query_rid = $db->query("SELECT COUNT(*) FROM admin_info where user_name='$user_name'");
	$q_rid = $db->fetch_row($query_rid);
	$res_rid = $q_rid[0];	
	if($res_rid!=0){$error.='帳號已有人使用!\r\n';}
	if(!preg_match('/^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/', $_POST['pass_word'])){$error.='6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號\r\n';} 
	if(empty($name)){$error.='請輸入姓名!\r\n';}
	if(empty($year)){$error.='請輸入生日年分!\r\n';}
	if(empty($month)){$error.='請輸入生日月份!\r\n';}
	if(empty($day)){$error.='請輸入生日日期!\r\n';}
 	if(!empty($year)&&!empty($month)&&!empty($day)){if(!checkdate($month,$day,$year)){$error.='生日沒有這一天喔!\r\n';}  } 
	if(is_null($sex)){$error.='請輸入性別!\r\n';}
	if(is_null($blood)){$error.='請輸入血型\r\n';}
	if(is_null($marry)){$error.='請輸入婚姻!\r\n';}
	if(empty($error)){ 
			$querya=$db->query("INSERT INTO admin_info (group_uid,user_name,pass_word,name,birthday,sex,blood,marry,remark,start_date,close_date,user_level,settime,del,is_lock) VALUES ('$group_uid','$user_name','$pass_word','$name','$birthday','$sex','$blood','$marry','$remark','$today','$close','1','$timeformat','N','0')");
 			$id=$db->insert_id();
			$descrip="add manage_add.php user_name=$user_name name=$name";
			$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('38','$user_name','$admin_d[uid]','1','$timeformat','$timestamp','$descrip')");
			$error='ok';	 
	 	}	
	}	

$admin_group=array(0=>'管理者',1=>'開發人員',2=>'編輯人員',3=>'網頁設計師')	;
$admin_sex=array(0=>'女',1=>'男');
$admin_blood=array(0=>'A',1=>'B',2=>'AB',3=>'O');
$admin_marry=array(0=>'未婚',1=>'已婚');
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        @import "include/datepick/jquery.datepick.css";
    </style>
    <script type="text/javascript" src="include/datepick/jquery.datepick.js"></script>
    <script type="text/javascript" src="include/datepick/jquery.datepick-zh-TW.js"></script>

<? include('include/css_js.php');?>
<script language="JavaScript">
        function strim(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
            	}
        function check_empty() {
            ierror = 0;
            message = '';
			
            if (form1.group_uid.value == "") {
                message+='請輸入群組\r\n';
				ierror=1;
						}				
            var cunRegExp = /^(?=.*[a-zA-Z])(?!.*[^\x21-\x7e])(?!.*[\@#$%^&+=!]).{1,}$/;				
            	cun=strim(document.form1.user_name.value);
            if (!cunRegExp.test(cun)) {
                message+='帳號 必須符合 大小寫英文數字\r\n';
            	ierror=1;
						}									
		var cpwRegExp = /^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/;
            	cpw=strim(document.form1.pass_word.value);
            if(!cpwRegExp.test(cpw)){
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
            if (form1.name.value == "") {
                message+='請輸入姓名\r\n';
				ierror=1;
						}				
            if (form1.year.value == "") {
                message+='請輸入生日年分\r\n';
				ierror=1;
						}				
            if (form1.month.value == "") {
                message+='請輸入生日月份\r\n';
				ierror=1;
						}				
            if (form1.day.value == "") {
                message+='請輸入生日日期\r\n';
				ierror=1;
						}				
            if (form1.sex.value == "") {
                message+='請輸入性別\r\n';
				ierror=1;
						}						
            if (form1.blood.value == "") {
                message+='請輸入血型\r\n';
				ierror=1;
						}						
            if (form1.marry.value == "") {
                message+='請輸入婚姻\r\n';
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
                <td class="tableTitle" colspan="10">新增人員</td>
            </tr>

            <tr>
                <td align="center">群組</td>
				<td>
					<select name="group_uid" id="group_uid">
					<option value="" >請選擇群組</option>
					<?foreach($admin_group as $key => $value){?>
					<option value="<?=$key?>"><?= $value; ?></option>					
					<?}?>
					</select>
					<font color="#FF0000">*</font>	
				   </td> 
				 </tr>
            <tr>
				<td align="center">帳號</td>
				<td><input type="text" name="user_name" id="user_name" style="width:200px;" /><font color="#FF0000">*</font>	</td>
            </tr>
            <tr>
                <td align="center">密碼</td>
                <td><input type="password"  name="pass_word" id="pass_word"  style="width:200px;" /><font color="#FF0000">*</font>	</td>
			</tr>
			<tr>					 
				<td width="150" align="center">再重複一次密碼</td>
				<td><input type="password" name="pass_word2" id="pass_word2" style="width:200px;"> <font color="#FF0000">*</font>	</td>
			</tr>					
            <tr>				
				<td align="center">姓名</td>
				<td><input type="text" name="name" id="name" style="width:200px;" /> <font color="#FF0000">*</font>	</td>				
			</tr>
            <tr>
                <td align="center">生日</td>
                    <td>
					<select name="year" id="year">
					<option value=""></option>
					<? for ($i=1960; $i<=2016; $i++) {?>
					<option value="<?=$i?>"><?= $i; ?></option>
					<? } ?>
					</select> 年

                     <select name="month" id="month">
					<option value=""></option>
					<? for ($i=1; $i<=12; $i++) {?>
					<option value="<?=$i?>" ><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select>月
                    <select name="day" id="day">
					<option value=""></option>
					<? for ($i=1; $i<=31; $i++) {?>
					<option value="<?=$i?>" ><?=  str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
					<? } ?>
					</select>日	
				<font color="#FF0000">*</font>						
                    </td>	  
            </tr>
            <tr>
                <td align="center">性別</td>
				<td>
					<select name="sex" id="sex">
					<option value="" >請選擇性別</option>
					<?foreach($admin_sex as $key => $value){?>
					<option value="<?=$key?>"><?= $value; ?></option>
					<?}?>
					</select>					
				<font color="#FF0000">*</font>					
				</td>
            </tr>
            <tr>
                <td align="center">血型</td>
				<td>
					<select name="blood" id="blood">
					<option value="" >請選擇血型</option>
					<?foreach($admin_blood as $key => $value){?>
					<option value="<?=$key?>"><?= $value; ?></option>
					<?}?>
					</select>					
				<font color="#FF0000">*</font>					
				</td>
            </tr>
            <tr>
                <td align="center">婚姻</td>
				<td>
					<select name="marry" id="marry">
					<option value="" >請選擇婚姻</option>
					<?foreach($admin_marry as $key => $value){?>
					<option value="<?=$key?>"><?= $value; ?></option>
					<?}?>
					</select>
				<font color="#FF0000">*</font>					
				</td>
            </tr>
            <tr>
                <td align="center">備註</td>
				<td><input type="text" name="remark" id="remark" style="width:200px;" /></td>
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
alert('新增成功');
parent.referu('index.php?pid=38');
</script>
<? }elseif(!empty($error)){?>
<script>
alert('<?=$error?>');
history.go(-1)
</script>
<? }?>

</body>
</html>
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
