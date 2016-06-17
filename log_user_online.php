<? // 使用者線上紀錄
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid=40;
if(!in_array($thorid,$p_array)){header("Location:login.php"); exit();}
$wherea=array();
$where='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$lurl='';
/*選擇日期開始*/
if(!empty($_GET['d1']) && !empty($_GET['d2'])){
	$d1=$_GET['d1']." 00:00:00";
	$d2=$_GET['d2']." 23:59:59";
	$d1=strtotime($d1);
	$d2=strtotime($d2);
	$wherea[]="stime >= '".m_esc($d1)."'";
	$wherea[]="stime <= '".m_esc($d2)."'";
	$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	$logw[]="stime >= ".m_esc($_GET['d1'])." and stime <=".m_esc($_GET['d2']);
}elseif(!empty($_GET['d1'])){
	$d1=$_GET['d1']." 00:00:00";
	$d1=strtotime($d1);
	$wherea[]="stime >='".m_esc($d1)."'";
	//$_GET['d2']=gmdate('Y-m-d',strtotime($_GET['d1'])+7*86400+8*3600);
	//$wherea[]="stimef <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d1=".$_GET['d1'];
	$logw[]="stime >= ".m_esc($d1);
}elseif(!empty($_GET['d2'])){
	$d2=$_GET['d2']." 23:59:59";
	$d2=strtotime($d2);
	$wherea[]="stime <= '".m_esc($d2)."'";
	//$_GET['d1']=gmdate('Y-m-d',strtotime($_GET['d2'])-7*86400+8*3600);
	//$wherea[]="stimef >='".m_esc($_GET['d1'])." 00:00:00'";
	$lurl.="&d2=".$_GET['d2'];
	$logw[]="and stime <=".m_esc($d2);
}else{
	//$_GET['d1']=gmdate('Y-m-d',$stimeftamp-30*86400+8*3600);
	//$_GET['d2']=gmdate('Y-m-d',$stimeftamp-86400+8*3600);
	//$wherea[]="stimef >= '".m_esc($_GET['d1'])." 00:00:00'";
	//$wherea[]="stimef <= '".m_esc($_GET['d2'])." 23:59:59'";
	//$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	//$logw[]="stimef >= ".m_esc($_GET['d1'])."and stimef <=".m_esc($_GET['d2']);
}
/*選擇日期結束*/
if(!empty($_GET['d3']) && !empty($_GET['d4'])){
	$d3=$_GET['d3']." 00:00:00";
	$d4=$_GET['d4']." 23:59:59";
	$d3=strtotime($d3);
	$d4=strtotime($d4);
	$wherea[]="A.etime >= '".m_esc($d3)."'";
	$wherea[]="A.etime <= '".m_esc($d4)."'";
	$lurl.="&d3=".$_GET['d3']."&d4=".$_GET['d4'];
	$logw[]="A.etime >= ".m_esc($_GET['d3'])." and A.etime <=".m_esc($_GET['d4']);
}elseif(!empty($_GET['d3'])){
	$d3=$_GET['d3']." 00:00:00";
	$d3=strtotime($d3);
	$wherea[]="A.etime >='".m_esc($d3)."'";
	//$_GET['d2']=gmdate('Y-m-d',strtotime($_GET['d1'])+7*86400+8*3600);
	//$wherea[]="etimef <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d3=".$_GET['d3'];
	$logw[]="A.etime >= ".m_esc($d3);
}elseif(!empty($_GET['d4'])){
	$d4=$_GET['d4']." 23:59:59";
	$d4=strtotime($d4);
	$wherea[]="A.etime <= '".m_esc($d4)."'";
	//$_GET['d1']=gmdate('Y-m-d',strtotime($_GET['d2'])-7*86400+8*3600);
	//$wherea[]="etimef >='".m_esc($_GET['d1'])." 00:00:00'";
	$lurl.="&d4=".$_GET['d4'];
	$logw[]="and A.etime <=".m_esc($d4);
}else{
	//$_GET['d1']=gmdate('Y-m-d',$etimeftamp-30*86400+8*3600);
	//$_GET['d2']=gmdate('Y-m-d',$etimeftamp-86400+8*3600);
	//$wherea[]="etimef >= '".m_esc($_GET['d1'])." 00:00:00'";
	//$wherea[]="etimef <= '".m_esc($_GET['d2'])." 23:59:59'";
	//$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	//$logw[]="etimef >= ".m_esc($_GET['d1'])."and etimef <=".m_esc($_GET['d2']);
}
/*搜尋*/
if($_GET['keyi']){
	$keyi=m_esc($_GET['keyi']);
	$wherea[]="B.account like '%".m_esc($_GET['keyi'])."%'";
	$lurl.="&keyi=".rawurlencode(ds($_GET['keyi']));
	$logw[]="account like ".$keyi;
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
$descrip="view log_user_online.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$stimeftamp','$descrip')");
$rownum = 20; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$querya=$db->query("SELECT A.uid,A.ip,A.stimef,A.stime,A.etimef,A.etime,B.account FROM  session as A LEFT join user as B on A.uid= B.id   $where  order by etimef desc"." LIMIT $TP, $rownum");

/*下一頁*/
$query_num = $db->query("SELECT COUNT(*) FROM session as A LEFT join user as B on A.uid= B.id".$where);
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
				<td class="tableTitle" colspan="10">使用者線上紀錄</td>
			</tr>
			<tr>                   
				<td width="150" align="center">ID查詢</td>
				<td width="25%"><input type="text" name="keyi" id="keyi" value="<?=$_GET['keyi']?>" /></td>	           
				<td width="150" align="center">開始時間</td>
				<td>從 <input type="text" name="d1" id="d1" class="date_pick" value="<?=$_GET['d1']?>" style="width:70px;" maxlength="10" />~到 <input type="text" name="d2" id="d2" class="date_pick" value="<?=$_GET['d2']?>" style="width:70px;" maxlength="10" /></td>					
				<td width="150" align="center">最後時間</td>
				<td>從 <input type="text" name="d3" id="d3" class="date_pick" value="<?=$_GET['d3']?>" style="width:70px;" maxlength="10" />~到 <input type="text" name="d4" id="d4" class="date_pick" value="<?=$_GET['d4']?>" style="width:70px;" maxlength="10" /></td>					
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
				<td width="10%" align="center">序</td>						
				<td width="20%" align="center">使用者ID</td>						
				<td width="20%" align="center">IP</td>
				<td width="25%" align="center">開始時間</td>
				<td width="25%" align="center">最後時間</td>
			</tr>				
		<? $i=0; $black='#FFFFCC'; $bg=($i%2==1)?$black:'';?>
		<?while($al = $db->fetch_array($querya)){ ?>
			<tr class="chbg" bgcolor="<?=$bg?>">
				<td align="center">
					<?php if($_GET["ToPage"] != "") { echo ((($_GET["ToPage"]-1)*$rownum)+1+$i);} else { echo ($i+1); }?>
				</td>							
				<td align="center">
					<?=$eAdmin[$al['uid']]?>
				</td>
				<td align="center">
					<?=$al['ip']?>
				</td>  								
				<td align="center">
					<?=$al['stimef']?>									
				</td>  		
				<td align="center">
					<?=$al['etimef']?>									
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