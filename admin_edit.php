<? // 基本資料修改
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid='31';
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}

$where='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$lurl='';
if(!empty($_SESSION['admin_uid'])){
	$query = $db->query("SELECT uid,group_uid,user_name,pass_word,name,birthday,phone,cellphone,thor FROM admin_info WHERE uid='$_SESSION[admin_uid]' and del='N'");
	$admin_d = $db->fetch_array($query);
}
$rownum = 5; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$querya=$db->query("SELECT id,uid,name,ip,times,in_t FROM  admin_login_log WHERE  uid='$_SESSION[admin_uid]' order by times desc"." LIMIT $TP, $rownum");


/*寫入log*/
$descrip="view admin_edit.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");


/*編輯者*/
$querye = $db->query("SELECT uid,user_name from admin_info");
$eAdmin=array();
while($e = $db->fetch_array($querye)){
	$eAdmin[$e['uid']]=$e['user_name'];
}

$use_in=array(0=>'失敗', 1=>'成功');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <script>
 
 function check_pw(pw) {
 	var re = /^(?!.*[^\x21-\x7e])(?=.*[a-z])(?=.*[A-Z])(?!.*[^\x00-\xff])(?!.*[\W]).{6,20}$/;
    if(Regist.pass_word.value != "" && !re.test(pw.value)){
        alert("密碼須6-20位數，並且至少包含 大寫字母、小寫字母，但不包含其他特殊符號");
        return false;
    }
    return true;    
 }
 function ck_pe(pe) {
 	var re = /^\d{4}$/;	
 	if (!re.test(pe.value)){
 		alert("請輸入分機號碼入長度為 4");		
 	}
 	else 
 		return true;	
 }
  function ck_bd(bd) {
	var re = /^([0-9]{4})[\-]{1}([0-9]{2})[\-]{1}([0-9]{2})$/
    if(Regist.birthday.value != "" && !re.test(bd.value)){
		message += '日期輸入錯誤喔 \r\n';
        alert('日期輸入錯誤喔 \r\n');
        return false;
    }
    return true;    
 }
 function ck_ce(ce) {
 	var re = /^[09]{2}[0-9]{8}$/;
 	if (!re.test(ce.value)){
 		alert('請輸入手機號碼，開頭為09 長度為10位數');		
 	}
 	else 
 		return true;		
 }
 function ck_pw(){
 /* 	if(Regist.pass_word.value==""){
 		alert("請輸入密碼");
 		Regist.pass_word.focus();
 		return false;
 				}
 	if(Regist.pass_word2.value==""){
 		alert("請再次輸入密碼");
 		Regist.pass_word2.focus();
 		return false;			
 				}   */
 	if(Regist.pass_word.value != Regist.pass_word2.value){
 		alert("兩次輸入密碼不同");
 		Regist.pass_word.value=="";
 		Regist.pass_word2.value=="";
 		Regist.pass_word.focus();
 		return false;			
 					}
 		return true;			
 			}
 	
 </script>
<script>
function check_fm(form) {
 	if (!ck_pw(form.pass_word)) return;
 	if (!ck_bd(form.birthday)) return;
	if (!ck_pe(form.phone)) return;
	if (!ck_ce(form.cellphone)) return;	
	if (!check_pw(form.pass_word)) return;
	//alert ("成功！\n表單即將送出！！！");
	document.Regist.submit();	// Submit form
}
</script>

    <style type="text/css">
        @import "include/datepick/jquery.datepick.css";
    </style>
    <script type="text/javascript" src="include/datepick/jquery.datepick.js"></script>
    <script type="text/javascript" src="include/datepick/jquery.datepick-zh-TW.js"></script>
</head>	
<body>
	    <div class="right_b">
        <form name="Regist" id="form1" action="admin_edit_upload.php" enctype="multipart/form-data" method="post" onsubmit="return ck_pw();">
            <table cellpadding="0" cellspacing="0" class="menutable" height="100%">
			
                <tr>
                    <td class="tableTitle" colspan="10">基本資料修改</td>
                </tr>
				<tr>
                    <td width="150" align="center">會員編號</td>
					 <td><?=$admin_d['uid']?></td>.
					 <input type="hidden" name="uid" value="<?=$admin_d['uid']?>">
				</tr>
                <tr>
                    <td width="150" align="center">登入帳號</td>
					 <td><?=$admin_d['user_name']?></td>
					 <input type="hidden" name="user_name" value="<?=$admin_d['user_name']?>">
				</tr>
				<tr>					 
				  <td width="150" align="center">修改密碼</td>
					 <td><input type="password" name="pass_word" id="pass_word"  placeholder="修改密碼">  </td>
				</tr>				
				<tr>					 
				  <td width="150" align="center">再重複一次密碼</td>
					 <td><input type="password" name="pass_word2"   placeholder="再重複一次密碼" >  </td>
				</tr>
				<tr>
                    <td width="150" align="center">姓名</td> 
				 <td><input type="text" name="name"   placeholder="姓名" value="<?=$admin_d['name']?>"> </td>
				 </tr>
				<tr>
                    <td width="150" align="center">生日</td>
					 <td><input type="text" name="birthday"  class="date_pick"  placeholder="生日" value="<?=$admin_d['birthday']?>"> </td>
				</tr>
				<tr>				
                <td width="150" align="center">分機</td> 
				 <td><input type="text" name="phone"   placeholder="分機" value="<?=$admin_d['phone']?>"> </td>
				 </tr>
				<tr>				 
                <td width="150" align="center">手機</td> 
				 <td><input type="text" name="cellphone"   placeholder="手機" value="<?=$admin_d['cellphone']?>"> </td>	
				 </tr>
				<tr>
				 <td colspan="9"><input type=button value="送出" onClick="check_fm(this.form)" /><input type="hidden" name="pid" value="<?=ht($_GET['pid'])?>" /><input type="hidden" name="action" value="update" /></td>
                </tr> 
            </table>
        </form>
		    </div>
 <div class="right_b">
	            <form name="form2" id="form2" action="" enctype="multipart/form-data" method="post" onsubmit="">
	                <table cellpadding="0" cellspacing="0" class="menutable" height="100%">
	                    <tr>
	                        <td class="tableTitle" colspan="10">最近五次登入紀錄</td>
	                    </tr>
	                        <tr>
	                            <td width="15%" align="center">序</td>					
	                            <td width="15%" align="center">使用者ID</td>						
	                            <td width="15%" align="center">帳號</td>
	                            <td width="20%" align="center">IP</td>
	                            <td width="20%" align="center">登入時間</td>
	                            <td width="15%" align="center">登入狀態</td>
	                        </tr>				
	    				<? $i=0; $black='#FFFFCC'; $bg=($i%2==1)?$black:'';?>
	                            <? 
	    						while($al = $db->fetch_array($querya)){  
	    						?>
	                                <tr class="chbg" bgcolor="<?=$bg?>">
	                                    <td align="center">
	                                        <?php if($_GET["ToPage"] != "") { echo ((($_GET["ToPage"]-1)*$rownum)+1+$i);} else { echo ($i+1); }?>
	                                    </td>								
	                                    <td align="center">
	                                        <?=$eAdmin[$al['uid']]?>
	    							    </td>
	                                    <td align="center">
	                                        <?=ht($al['name']) ?>
	                                    </td>
	                                    <td align="center">
	                                        <?=$al['ip']?>
	    							    </td>  								
	                                    <td align="center">
	                                       <!--  <?=$al['times']?> -->
	                                        <?=gmdate('Y-m-d H:i:s',$al['times']+8*3600);?>											
	    							    </td>  		
	                                    <td align="center">
	                                        <?=' '.$use_in[$al['in_t']]?>
	    							    </td>  									
	                                </tr>
	                                <? ++$i; }?>
	                </table>
	            </form>
	    		    </div> 
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
</body>