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
if(!empty($_GET['d1'])){
	$d1=$_GET['d1']." 00:00:00";
	$d1=strtotime($d1);
	$wherea[]="stimef >= '".m_esc($d1)."'";
	$lurl.="&d1=".$_GET['d1'];
	$logw[]="stimef >= ".m_esc($_GET['d1']);
}elseif(!empty($_GET['d1'])){
	$d1=$_GET['d1']." 00:00:00";
	$d1=strtotime($d1);
	$wherea[]="stimef >='".m_esc($d1)."'";
	$lurl.="&d1=".$_GET['d1'];
	$logw[]="stimef >= ".m_esc($d1);
}
/*選擇日期結束*/
if(!empty($_GET['d3'])){
	$d3=$_GET['d3']." 00:00:00";
	$d3=strtotime($d3);
	$wherea[]="etimef >= '".m_esc($d3)."'";
	$lurl.="&d3=".$_GET['d3'];
	$logw[]="etimef >= ".m_esc($_GET['d3']);
}elseif(!empty($_GET['d3'])){
	$d3=$_GET['d3']." 00:00:00";
	$d3=strtotime($d3);
	$wherea[]="etimef >='".m_esc($d3)."'";
	$lurl.="&d3=".$_GET['d3'];
	$logw[]="etimef >= ".m_esc($d3);
}
/*搜尋*/
if(ck_num($_GET['keyi'])){
	$keyw=m_esc($_GET['keyi']);
	$wherea[]="uid like '%".m_esc($_GET['keyi'])."%'";
	$lurl.="&keyi=".rawurlencode(ds($_GET['keyi']));
	$logw[]="keyi = ".$keyi;
}

/*單選*/
if (ck_num($_GET['int'])){
	$wherea[]="in_t='".m_esc($_GET['int'])."'";
	$lurl.="&int=".$_GET['int'];
	$logw[]="in_t = ".m_esc($_GET['int']);
}
if(!empty($_GET['mi'])){
	$wherea[]="uid='".m_esc($_GET['mi'])."'";
	$lurl.="&mi=".$_GET['mi'];
	$logw[]="uid = ".m_esc($_GET['mi']);
}
/*帶入尋找*/
if(!empty($wherea)){
	$where=" WHERE ".implode(' and ',$wherea);
	$logws=implode(' and ',$logw);
}
/*寫入log*/
/* $descrip="view user_login.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");
 */$rownum = 20; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$querya=$db->query("SELECT uid,ip,stimef,etimef FROM  session  $where  order by etimef desc"." LIMIT $TP, $rownum");

/*下一頁*/
$query_num = $db->query("SELECT COUNT(*) FROM session ".$where);
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
$queryer = $db->query("SELECT id,account from user");
$erAdmin=array();
while($er = $db->fetch_array($queryer)){
	$erAdmin[$e['account']]=$e['id'];
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
				<?=$_GET['keyi']?>	                
				<td width="150" align="center">開始時間</td>
				<td><input type="text" name="d1" id="d1" class="date_pick" value="<?=$_GET['d1']?>" style="width:90px;" maxlength="10" /></td>					
				<td width="150" align="center">最後時間</td>
				<td><input type="text" name="d3" id="d3" class="date_pick" value="<?=$_GET['d3']?>" style="width:90px;" maxlength="10" /></td>					
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
				<td width="25%" align="center">最候時間</td>
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