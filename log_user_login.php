<? // 使用者登入紀錄
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid=39;
if(!in_array($thorid,$p_array)){header("Location:login.php"); exit();}
$wherea=array();
$whereai=array();
$where='';
$wherei='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$lurl='';
/*選擇日期*/
if(!empty($_GET['d1']) && !empty($_GET['d2'])){
	$d1=$_GET['d1']." 00:00:00";
	$d2=$_GET['d2']." 23:59:59";
	$d1=strtotime($d1);
	$d2=strtotime($d2);
	$wherea[]="times >= '".m_esc($d1)."'";
	$wherea[]="times <= '".m_esc($d2)."'";
	$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	$logw[]="times >= ".m_esc($_GET['d1'])." and times <=".m_esc($_GET['d2']);
}elseif(!empty($_GET['d1'])){
	$d1=$_GET['d1']." 00:00:00";
	$d1=strtotime($d1);
	$wherea[]="times >='".m_esc($d1)."'";
	//$_GET['d2']=gmdate('Y-m-d',strtotime($_GET['d1'])+7*86400+8*3600);
	//$wherea[]="times <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d1=".$_GET['d1'];
	$logw[]="times >= ".m_esc($d1);
}elseif(!empty($_GET['d2'])){
	$d2=$_GET['d2']." 23:59:59";
	$d2=strtotime($d2);
	$wherea[]="times <= '".m_esc($d2)."'";
	//$_GET['d1']=gmdate('Y-m-d',strtotime($_GET['d2'])-7*86400+8*3600);
	//$wherea[]="times >='".m_esc($_GET['d1'])." 00:00:00'";
	$lurl.="&d2=".$_GET['d2'];
	$logw[]="and times <=".m_esc($d2);
}else{
	//$_GET['d1']=gmdate('Y-m-d',$timestamp-30*86400+8*3600);
	//$_GET['d2']=gmdate('Y-m-d',$timestamp-86400+8*3600);
	//$wherea[]="times >= '".m_esc($_GET['d1'])." 00:00:00'";
	//$wherea[]="times <= '".m_esc($_GET['d2'])." 23:59:59'";
	//$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	//$logw[]="times >= ".m_esc($_GET['d1'])."and times <=".m_esc($_GET['d2']);
}
/*搜尋*/
if($_GET['key_id']){
	$key_id=m_esc($_GET['key_id']);
	$wherea[]="B.account like '%".m_esc($_GET['key_id'])."%'";
	$lurl.="&key_id=".rawurlencode(ds($_GET['key_id']));
	$logw[]="account like".$key_id;
}
if(!empty($_GET['key_name'])){
	$key_name=m_esc($_GET['key_name']);
	$wherea[]="A.name like '%".m_esc($_GET['key_name'])."%'";
	$lurl.="&key_name=".rawurlencode(ds($_GET['key_name']));
	$logw[]="name like".$key_name;
}
if ($_GET['fm']){
	$wherea[]="A.fm='".m_esc($_GET['fm'])."'";
	$lurl.="&fm=".$_GET['fm'];
	$logw[]="select_from = ".m_esc($_GET['fm']);
}
/*單選*/
if (ck_num($_GET['int'])){
	$wherea[]="in_t='".m_esc($_GET['int'])."'";
	$lurl.="&int=".$_GET['int'];
	$logw[]="in_t = ".m_esc($_GET['int']);
}
/*帶入尋找*/
if(!empty($wherea)){
	$where=" WHERE ".implode(' and ',$wherea);
	$logws=implode(' and ',$logw);
}
/*寫入log*/
$descrip="view log_user_login.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");
$rownum = 20; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
/* $querya=$db->query("SELECT * FROM user_login_log   $where  order by times desc"." LIMIT $TP, $rownum"); */
$querya=$db->query("SELECT A.id,A.uid,A.name,A.ip,A.day,A.time,A.times,A.in_t,A.fm,B.account FROM user_login_log as A LEFT join user as B on A.uid = B.id   $where  order by times desc"." LIMIT $TP, $rownum");
/*下一頁*/
$query_num = $db->query("SELECT COUNT(*) FROM user_login_log as A LEFT join user as B on A.uid = B.id ".$where);
			$q_num = $db->fetch_row($query_num);
			$product_page_num = $q_num[0];		
			$page_change 	  = ceil($product_page_num/$rownum);
			$_GET=gt($_GET);	
			$myURL 			  = "index.php?pid=".$_GET['pid'].$lurl."&ToPage=";
/*編輯者*/
$querye = $db->query("SELECT id,account from user");
$eAdmin=array();
while($e = $db->fetch_array($querye)){
	$eAdmin[$e['id']]=$e['account'];
}
$use_in=array(0=>'失敗', 1=>'成功',2=>'cookie登入',3=>'登出');
$querywe = $db->query("select distinct  fm from user_login_log");
?>
<style type="text/css">
	@import "include/datepick/jquery.datepick.css";
</style>
<script type="text/javascript" src="include/datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="include/datepick/jquery.datepick-zh-TW.js"></script>
<style>
	.pre_pic img {
		max-width: 100px;
		max-height: 100px;
		width: expression(this.width >100 && this.height < this.width ? 100: true);
		height: expression(this.height > 100 ? 100: true)
	}
</style>

<div class="right_b">
	<form name="form1" id="form1" action="" enctype="multipart/form-data" method="get">
		<table cellpadding="0" cellspacing="0" class="menutable" height="100%">
			<tr>
				<td class="tableTitle" colspan="10">使用者登入紀錄</td>
			</tr>
			<tr>                   
				<td width="150" align="center">ID查詢</td>
				<td width="25%"><input type="text" name="key_id" id="key_id" value="<?=$_GET['key_id']?>" /></td>	
				<td width="150" align="center">姓名</td>
				<td width="25%"><input type="text" name="key_name" id="key_name" value="<?=$_GET['key_name']?>" /></td>		
				<td width="150" align="center">登入網站</td>
				<td width="25%">					
					<select name="fm" id="fm" >
						<option value="" >請選擇</option>
						<?while($wl = $db->fetch_array($querywe)){?>
						<option value="<?=$wl['fm']?>" <?if ($_GET['fm'] == $wl["fm"]) { echo 'selected="selected"'; } ?> ><?=$wl['fm']?></option>					
						<?}?>
					</select>
				</td>	
				</tr>	                
			<tr>	                
				<td width="150" align="center">登入日期</td>
				<td>從 <input type="text" name="d1" id="d1" class="date_pick" value="<?=$_GET['d1']?>" style="width:70px;" maxlength="10" />~到 <input type="text" name="d2" id="d2" class="date_pick" value="<?=$_GET['d2']?>" style="width:70px;" maxlength="10" /></td>					
			    <td align="center">登入狀態</td>
				<td colspan="5"><input type="radio" name="int" id="int" value="" <?=empty($_GET['int'])? ' checked="checked"': '';?> />不拘
					<? $x=1;foreach($use_in as $k => $v){?><input type="radio" name="int" id="int"  value="<?=$k?>" <?=($k==$_GET['int'] && ck_num($_GET['int']))? ' checked="checked"': '';?> />
						<?=$v;?>
							<?=($x%11==0)?'<br>':'';?>
								<? ++$x;}?>
				</td>
				</tr>	                							
			<tr>
				<td colspan="9"><input type="submit" value="送出" /><input type="hidden" name="pid" value="<?=$_GET['pid']?>" /></td>
			</tr>
		</table>
	</form>
</div>
<!--第二區塊-->	
<div class="right_b" >
	<table cellpadding="0" cellspacing="0" class="menutable" id="log_login">
		<? if($product_page_num > 0){?>
			<tr>
				<td colspan="11" class="trd">
					<table width="100%">
						<tr>
							<td width="10%" style="border:none;">總數：
								<?=$product_page_num?>
							</td>
							<td align="center" style="border:none;">
								<a href="<?= $myURL." 1 " ?>" class="bodytext_10_block">
									<font color="#2680BA">第一頁</font>
								</a>&nbsp;&nbsp;&nbsp;<span class="bodytext_10_block"><? include("../include/function_pagechange.php"); $pg=pagechange($product_page_num,$page_change,$ToPage,$myURL); echo $pg;?></span>&nbsp;&nbsp;&nbsp;
								<a href="<?= $myURL.$page_change; ?>" class="bodytext_10_block">
									<font color="#2680BA">最後一頁</font>
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<? }?>
			<tr>
				<td width="5%" align="center">序</td>
				<td width="5%" align="center">流水碼</td>						
				<td width="15%" align="center">使用者ID</td>						
				<td width="15%" align="center">姓名</td>
				<td width="20%" align="center">IP</td>
				<td width="20%" align="center">登入時間</td>
				<td width="10%" align="center">登入狀態</td>
				<td width="10%" align="center">登入網站</td>
			</tr>				
		<? $i=0; $black='#FFFFCC'; $bg=($i%2==1)?$black:'';?>
		<?while($al = $db->fetch_array($querya)){ ?>
			<tr class="chbg" bgcolor="<?=$bg?>">
				<td align="center">
					<?php if($_GET["ToPage"] != "") { echo ((($_GET["ToPage"]-1)*$rownum)+1+$i);} else { echo ($i+1); }?>
				</td>
				<td align="center">
					<?=ht($al['id'])?>
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
					<?=gmdate('Y-m-d H:i:s',$al['times']+8*3600);?>											
				</td>  		
				<td align="center">
					<?=' '.$use_in[$al['in_t']]?>
				</td>  									
				<td align="center">
					<?=$al['fm']?>
				</td>  	 									
			</tr>
		<? ++$i; }?>
		<? if($product_page_num > 0){?>
			<tr>
				<td colspan="11" class="trd">
					<table width="100%">
						<tr>
							<td width="10%" style="border:none;">總數：
								<?=$product_page_num?>
							</td>
							<td align="center" style="border:none;">
								<a href="<?= $myURL." 1 " ?>" class="bodytext_10_block">
									<font color="#2680BA">第一頁</font>
								</a>&nbsp;&nbsp;&nbsp;<span class="bodytext_10_block"><? echo $pg;?></span>&nbsp;&nbsp;&nbsp;
								<a href="<?= $myURL.$page_change; ?>" class="bodytext_10_block">
									<font color="#2680BA">最後一頁</font>
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<? }?>
	</table>
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