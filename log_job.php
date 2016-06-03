<? // 排程執行紀錄
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid=35;
if(!in_array($thorid,$p_array)){header("Location:login.php"); exit();}
$wherea=array();
$where='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$lurl='';
/*選擇日期*/
if(!empty($_GET['d1']) && !empty($_GET['d2'])){
	$wherea[]="ontime >= '".m_esc($_GET['d1'])." 00:00:00'";
	$wherea[]="ontime <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	$logw[]="ontime >= ".m_esc($_GET['d1'])." and ontime <=".m_esc($_GET['d2']);
}elseif(!empty($_GET['d1'])){
	$wherea[]="ontime >='".m_esc($_GET['d1'])." 00:00:00'";
	//$_GET['d2']=gmdate('Y-m-d',strtotime($_GET['d1'])+7*86400+8*3600);
	//$wherea[]="ontime <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d1=".$_GET['d1'];
	$logw[]="ontime >= ".m_esc($_GET['d1']);
}elseif(!empty($_GET['d2'])){
	$wherea[]="ontime <= '".m_esc($_GET['d2'])." 23:59:59'";
	//$_GET['d1']=gmdate('Y-m-d',strtotime($_GET['d2'])-7*86400+8*3600);
	//$wherea[]="atime >='".m_esc($_GET['d1'])." 00:00:00'";
	$lurl.="&d2=".$_GET['d2'];
	$logw[]="and ontime <=".m_esc($_GET['d2']);
}else{
	//$_GET['d1']=gmdate('Y-m-d',$timestamp-30*86400+8*3600);
	//$_GET['d2']=gmdate('Y-m-d',$timestamp-86400+8*3600);
	//$wherea[]="atime >= '".m_esc($_GET['d1'])." 00:00:00'";
	//$wherea[]="atime <= '".m_esc($_GET['d2'])." 23:59:59'";
	//$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	//$logw[]="atime >= ".m_esc($_GET['d1'])."and atime <=".m_esc($_GET['d2']);
}
/*搜尋*/
if(!empty($_GET['keyi'])){
	$keyi=m_esc($_GET['keyi']);
	$wherea[]="name like '%".m_esc($_GET['keyi'])."%'";
	$lurl.="&keyi=".rawurlencode(ds($_GET['keyi']));
	$logw[]="keyi = ".$keyi;
}
/*帶入尋找*/
if(!empty($wherea)){
	$where=" WHERE ".implode(' and ',$wherea);
	$logws=implode(' and ',$logw);
}
/*寫入log*/
$descrip="view log_job.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");
$rownum = 20; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$querya=$db->query("SELECT id,name,url,description,ontime FROM  job_log $where  order by ontime desc"." LIMIT $TP, $rownum");
/*下一頁*/
$query_num = $db->query("SELECT COUNT(*) FROM job_log ".$where);
			$q_num = $db->fetch_row($query_num);
			$product_page_num = $q_num[0];		
			$page_change 	  = ceil($product_page_num/$rownum);
			$_GET=gt($_GET);	
			$myURL 			  = "index.php?pid=".$_GET['pid'].$lurl."&ToPage=";
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
				<td class="tableTitle" colspan="10">排程執行紀錄</td>
			</tr>
			<tr>
				<td width="150" align="center"> 執行項目</td>
				<td width="25%"><input type="text" name="keyi" id="keyi" value="<?=$_GET['keyi']?>" /></td>				
				<td width="150" align="center">執行時間</td>
				<td>從 <input type="text" name="d1" id="d1" class="date_pick" value="<?=$_GET['d1']?>" style="width:70px;" maxlength="10" />~到 <input type="text" name="d2" id="d2" class="date_pick" value="<?=$_GET['d2']?>" style="width:70px;" maxlength="10" /></td>					
			</tr>
				 <td colspan="9"><input type="submit" value="送出" /><input type="hidden" name="pid" value="<?=$_GET['pid']?>" /></td>
		</table>
	</form>
</div>
<!--第二區塊-->	
<div class="right_b" >
	<table cellpadding="0" cellspacing="0" class="menutable" id="log_job">
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
				<td width="20%" align="center">執行項目</td>
				<td width="20%" align="center">執行程式</td>
				<td width="35%" align="center">執行內容</td>
				<td width="15%" align="center">執行時間</td>
			</tr>
			<? $i=0; $black='#FFFFCC'; $bg=($i%2==1)?$black:'';?>
			<?while($al = $db->fetch_array($querya)){?>
			<tr class="chbg" bgcolor="<?=$bg?>">
				<td align="center">
					<?php if($_GET["ToPage"] != "") { echo ((($_GET["ToPage"]-1)*$rownum)+1+$i);} else { echo ($i+1); }?>
				</td>
				<td align="center">
					<?=ht($al['id'])?>
				</td>								
				<td align="center">
					<?=ht($al['name'])?>
				</td>
				<td align="center">
					<?=ht($al['url'])?>
				</td>  
				<td align="center">
					<?=ht($al['description'])?>
				</td>  										
				<td align="center">
					<?=ht($al['ontime'])?>
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