<? session_start();
if(!empty($_SESSION['admin_uid'])){
	header("Location:index.php");
	exit;
}
$error='';
include('../include/common.php');
if(!empty($_POST['acc']) && !empty($_POST['passw']) && strlen($_POST['prove'])==6){
	if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}//20141113
	$acc=trim($_POST['acc']);
	$pw=trim($_POST['passw']);
	$prove=trim($_POST['prove']);
  if(empty($_SESSION['check_num']) || $_SESSION['check_num'] != $prove){ 
		$error='驗證碼輸入錯誤';
  }else{	
	$acc=m_esc(check_gp($acc));
	$pw=m_esc(check_gp($pw));
	$error='';
  	$query = $db->query("SELECT uid,pass_word,is_lock FROM admin_info WHERE user_name='$acc' and del='N' and thor !=''");
  	$m_us= $db->fetch_array($query);
  	if(empty($m_us['uid'])){ 
  		$error='帳號或密碼錯誤';
		$db->query("INSERT INTO admin_login_log (uid,name,ip,day,time,times,in_t) VALUES ('0','$acc','$onlineip','$tday','$ttime','$timestamp','0')");
  	}else{
		if($m_us['is_lock']==0){
			if($m_us['pass_word']==md5($pw)){
					$_SESSION['admin_uid']=$m_us['uid'];
					$_SESSION['check_num']='';
					$db->query("INSERT INTO admin_login_log (uid,name,ip,day,time,times,in_t) VALUES ('$m_us[uid]','$acc','$onlineip','$tday','$ttime','$timestamp','1')");
					header("Location:index.php");
			}else{
				$error='帳號或密碼錯誤';
				$db->query("INSERT INTO admin_login_log (uid,name,ip,day,time,times,in_t) VALUES ('$m_us[uid]','$acc','$onlineip','$tday','$ttime','$timestamp','0')");
				$oTime=$timestamp - 86400;
				$query=$db->query("SELECT in_t FROM admin_login_log WHERE uid='$m_us[uid]' and times > $oTime ORDER BY id DESC limit 3");
				$i=0;
				while($ld = $db->fetch_array($query)){if($ld['in_t']=='0'){++$i;}}
				if($i > 2){					
					$db->query("INSERT INTO admin_login_log (uid,name,ip,day,time,times,in_t) VALUES ('$m_us[uid]','$acc','$onlineip','$tday','$ttime','$timestamp','2')");
					$db->query("UPDATE admin_info SET is_lock='1' WHERE uid='$m_us[uid]'");
					$error='您的帳號已輸入三次錯誤密碼將被鎖定';
				}
			}
		}else{
			$error='此帳號已被鎖定請洽詢網站管理員';
		}
    }
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include('include/css_js.php');?>
<script>
function trim(str){
     return str.replace(/(^\s*)|(\s*$)/g, "");
}
function cv(obj){
	lobj='#'+obj;
	if($(lobj).val()==''){
		$(lobj).css("background",'url(images/'+obj+'.png) no-repeat center left');
		$(lobj).css("background-color",'#FFFFFF');
	}else{		
		$(lobj).css("background",'');
		$(lobj).css("background-color",'#FFFFFF');
	}
}
$(document).ready(function(){
		$('#acc').focus();
		cv('acc'); cv('passw');
})
function login(){
	acc=trim($('#acc').val());
	pww=trim($('#passw').val());
	prove=trim($('#prove').val());
	ecount=0;
	error='';
	if(acc==''){error+='帳號不可為空白喔!<br>'; ecount++;	} 
	if(pww==''){error+='密碼不可為空白喔!<br>'; ecount++;	}
	if(prove.length < 6){ error+='請輸入六位數驗證碼'; ecount++;}
	if(error==''){
		ori_html=$('#lobutton').html();
		$('#lobutton').html('傳送中..');
		return true;
	}
	return false;
}
</script>
</head>

<body>
<div class="top_log"></div>
<div class="idol"><img src=../images/idollogo1.png><div class="btitle">後台管理系統</div></div>
<div class="tmenu"></div>
<form name="form1" id="form1" enctype="multipart/form-data" action="" method="post" onsubmit="return login();">
<div class="login">
<ul class="login_lo">
<li class="newmem_lo">管理者登入</li>
<li><input type="text" id="acc" name="acc" value="<?=(!empty($_POST['acc']))?ht($_POST['acc']):'';?>" class="reg2 reg2b" maxlength="25" onkeydown="cv('acc')" onblur="cv('acc')" onchange="cv('acc')" onkeyup="cv('acc')" onfocus="cv('acc')" style="width:77%" /></li>
<li><input type="password" id="passw" name="passw" value="<?=(!empty($_POST['passw']))?ht($_POST['passw']):'';?>" class="reg2 reg2b" onkeydown="cv('passw')" onblur="cv('passw')" onchange="cv('passw')" onkeyup="cv('passw')" onfocus="cv('passw')" maxlength="20" style="width:77%" /></li>
<li><table style="width:85%; margin:0 auto;"><tr><td align="center"><img src="../include/pic.php" onclick="this.src='../include/pic.php?t=' + Math.random()" id="isecode" width="140" height="40"></td><td><input type="text" id="prove" name="prove" value="<?=(!empty($_POST['prove']))?ht($_POST['prove']):'';?>" class="reg2 reg2b" maxlength="6" style="width:80px;" /></td></tr></table></li>
<li id="lobutton"><a href="javascript:void(0);" onclick="$('#form1').submit();" class="newsub" >登　　　　入</a></li>
</ul>
</div>
</form><? if(!empty($error)){?>
<script>
dialog('錯誤訊息','text:<div style="text-align:center; padding-top:20px; font-size:17px; line-height:40px;"><?=$error?></div>','200px',150,'iframe');
</script><? }?>
<?php echo "<!--".$_SESSION['check_num']."-->" ?>
</body>
</html>