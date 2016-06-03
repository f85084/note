<? // 後台操作紀錄
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid=33;
if(!in_array($thorid,$p_array)){header("Location:login.php"); exit();}
$wherea=array();
$where='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$lurl='';
/*選擇日期*/
if(!empty($_GET['d1']) && !empty($_GET['d2'])){
	$wherea[]="atime >= '".m_esc($_GET['d1'])." 00:00:00'";
	$wherea[]="atime <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	$logw[]="atime >= ".m_esc($_GET['d1'])." and atime <=".m_esc($_GET['d2']);
}elseif(!empty($_GET['d1'])){
	$wherea[]="atime >='".m_esc($_GET['d1'])." 00:00:00'";
	//$_GET['d2']=gmdate('Y-m-d',strtotime($_GET['d1'])+7*86400+8*3600);
	//$wherea[]="atime <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d1=".$_GET['d1'];
	$logw[]="atime >= ".m_esc($_GET['d1']);
}elseif(!empty($_GET['d2'])){
	$wherea[]="atime <= '".m_esc($_GET['d2'])." 23:59:59'";
	//$_GET['d1']=gmdate('Y-m-d',strtotime($_GET['d2'])-7*86400+8*3600);
	//$wherea[]="atime >='".m_esc($_GET['d1'])." 00:00:00'";
	$lurl.="&d2=".$_GET['d2'];
	$logw[]="and atime <=".m_esc($_GET['d2']);
}
if(!empty($_GET['keyi'])){
	$keyi=m_esc($_GET['keyi']);
	$wherea[]="description like '%".m_esc($_GET['keyi'])."%'";
	$lurl.="&keyi=".rawurlencode(ds($_GET['keyi']));
	$logw[]="keyi = ".$keyi;
}
/*單選*/
if (ck_num($_GET['act'])){
	$wherea[]="aid='".m_esc($_GET['act'])."'";
	$lurl.="&act=".$_GET['act'];
	$logw[]="aid = ".m_esc($_GET['act']);
}
if(!empty($_GET['mt'])){
	$wherea[]="uid='".m_esc($_GET['mt'])."'";
	$lurl.="&mt=".$_GET['mt'];
	$logw[]="uid = ".m_esc($_GET['mt']);
}
if(!empty($_GET['ml'])){
	$wherea[]="tid='".m_esc($_GET['ml'])."'";
	$lurl.="&ml=".$_GET['ml'];
	$logw[]="tid = ".m_esc($_GET['ml']);
}
if(!empty($wherea)){
	$where=" WHERE ".implode(' and ',$wherea);
	$logws=implode(' and ',$logw);
}
/*寫入log*/
$descrip="view log_act.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");
$rownum = 50; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$querya=$db->query("SELECT id,tid,pid,uid,aid,atime,ftime,description FROM  admin_act_log $where  order by atime desc"." LIMIT $TP, $rownum");
/*下一頁*/
$query_num = $db->query("SELECT COUNT(*) FROM admin_act_log ".$where);
			$q_num = $db->fetch_row($query_num);
			$product_page_num = $q_num[0];		
			$page_change 	  = ceil($product_page_num/$rownum);
			$_GET=gt($_GET);	
			$myURL 			  = "index.php?pid=".$_GET['pid'].$lurl."&ToPage=";			
/*編輯者*/
$querye = $db->query("SELECT uid,user_name from admin_info");
$eAdmin=array();
while($e = $db->fetch_array($querye)){
	$eAdmin[$e['uid']]=$e['user_name'];
}
$queryse = $db->query("SELECT uid,user_name from admin_info  where del='N'");
$seAdmin=array();
while($se = $db->fetch_array($queryse)){
	$seAdmin[$se['uid']]=$se['user_name'];
}
/*編輯項目*/
$queryl = $db->query("SELECT id,name,up_id from b_s_group");
$lAdmin=array();
while($l = $db->fetch_array($queryl)){
	$lAdmin[$l['id']]=$l['name'];
	}
$querylk = $db->query("SELECT id,name,up_id from b_s_group");
$lkAdmin=array();
while($l = $db->fetch_array($querylk)){
	if($l['up_id']!=0){
	$lkAdmin[$l['id']]=$l['name'];
	}
}
$use_act=array(0 => '查詢', 1 => '新增' , 2 => '修改' , 3 => '刪除');
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
				<td class="tableTitle" colspan="10">後台操作紀錄</td>
			</tr>
			<tr>
				<td width="150" align="center">關鍵字(描述)</td>
				<td width="25%"><input type="text" name="keyi" id="keyi" value="<?=$_GET['keyi']?>" /></td>	
				<td width="150" align="center">動作</td>
				<td width="25%"><input type="radio" name="act" id="act" value="" <?=empty($_GET['act'])? ' checked="checked"': '';?> />不拘
					<? $x=1;foreach($use_act as $k => $v){?><input type="radio" name="act" id="act"  value="<?=$k?>" <?=($k==$_GET['act'] && ck_num($_GET['act']))? ' checked="checked"': '';?> />
						<?=$v;?>
							<?=($x%11==0)?'<br>':'';?>
								<? ++$x;}?>
				</td>
				<td width="150" align="center">動作日期</td>
				<td>從 <input type="text" name="d1" id="d1" class="date_pick" value="<?=$_GET['d1']?>" style="width:70px;" maxlength="10" />~到 <input type="text" name="d2" id="d2" class="date_pick" value="<?=$_GET['d2']?>" style="width:70px;" maxlength="10" /></td>					
			</tr>
			<tr>
			</tr>				
			<tr>
				<td align="center">編輯者</td>
				<td colspan="5"><input type="radio" name="mt" id="mt" value="" <?=empty($_GET['mt'])? ' checked="checked"': '';?> />不拘
					<? $x=1;foreach($seAdmin as $k => $v){?><input type="radio" name="mt" id="mt"  value="<?=$k?>" <?=($k==$_GET['mt'])? ' checked="checked"': '';?> />
						<?=$v;?>
							<?=($x%12==0)?'<br>':'';?>
								<? ++$x;}?>
				</td>
			</tr>
			<tr>
				<td align="center">編輯項目</td>
				<td colspan="5"><input type="radio" name="ml" id="ml" value="" <?=empty($_GET['ml'])? ' checked="checked"': '';?> />不拘
					<? $x=1;foreach($lkAdmin as $k => $v){?><input type="radio" name="ml" id="ml"  value="<?=$k?>" <?=($k==$_GET['ml'])? ' checked="checked"': '';?> />
						<?=$v;?>
							<?=($x%10==0)?'<br>':'';?>
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
	<table cellpadding="0" cellspacing="0" class="menutable" id="log_act">
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
			<td width="10%" align="center">編輯項目</td>
			<td width="5%" align="center">編輯ID</td>
			<td width="10%" align="center">編輯者</td>
			<td width="10%" align="center">動作</td>
			<td width="15%" align="center">動作時間</td>
			<td width="40%" align="center">描述</td>
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
				<?=$lAdmin[$al['tid']]?>
			</td>
			<td align="center">
				<?=ht($al['pid']) ?>
			</td>
			<td align="center">
				<?=$eAdmin[$al['uid']]?>    
			</td>  
			<td align="center">
				<?=' '.$use_act[$al['aid']]?>
			</td>  								
			<td align="center">
				<?=ht($al['atime'])?>
			</td>  		
			<td align="center">
				<?=ht($al['description'])?>
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