<? // 使用者列表
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid=41;
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
$wherea=array();
$where='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$lurl='';
/*選擇日期*/
if(!empty($_GET['d1']) && !empty($_GET['d2'])){
	$wherea[]="ctime >= '".m_esc($_GET['d1'])." 00:00:00'";
	$wherea[]="ctime <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d1=".$_GET['d1']."&d2=".$_GET['d2'];
	$logw[]="ctime >= ".m_esc($_GET['d1'])." and ctime <=".m_esc($_GET['d2']);
}elseif(!empty($_GET['d1'])){
	$wherea[]="ctime >='".m_esc($_GET['d1'])." 00:00:00'";
	//$_GET['d2']=gmdate('Y-m-d',strtotime($_GET['d1'])+7*86400+8*3600);
	//$wherea[]="ctime <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d1=".$_GET['d1'];
	$logw[]="ctime >= ".m_esc($_GET['d1']);
}elseif(!empty($_GET['d2'])){
	$wherea[]="ctime <= '".m_esc($_GET['d2'])." 23:59:59'";
	//$_GET['d1']=gmdate('Y-m-d',strtotime($_GET['d2'])-7*86400+8*3600);
	//$wherea[]="ctime >='".m_esc($_GET['d1'])." 00:00:00'";
	$lurl.="&d2=".$_GET['d2'];
	$logw[]="and ctime <=".m_esc($_GET['d2']);
}
if(!empty($_GET['d3']) && !empty($_GET['d4'])){
	$wherea[]="etime >= '".m_esc($_GET['d3'])." 00:00:00'";
	$wherea[]="etime <= '".m_esc($_GET['d4'])." 23:59:59'";
	$lurl.="&d3=".$_GET['d3']."&d4=".$_GET['d4'];
	$logw[]="etime >= ".m_esc($_GET['d3'])." and etime <=".m_esc($_GET['d4']);
}elseif(!empty($_GET['d3'])){
	$wherea[]="etime >='".m_esc($_GET['d3'])." 00:00:00'";
	//$_GET['d2']=gmdate('Y-m-d',strtotime($_GET['d1'])+7*86400+8*3600);
	//$wherea[]="ctime <= '".m_esc($_GET['d2'])." 23:59:59'";
	$lurl.="&d3=".$_GET['d3'];
	$logw[]="etime >= ".m_esc($_GET['d3']);
}elseif(!empty($_GET['d4'])){
	$wherea[]="etime <= '".m_esc($_GET['d4'])." 23:59:59'";
	//$_GET['d1']=gmdate('Y-m-d',strtotime($_GET['d2'])-7*86400+8*3600);
	//$wherea[]="ctime >='".m_esc($_GET['d1'])." 00:00:00'";
	$lurl.="&d4=".$_GET['d4'];
	$logw[]="and etime <=".m_esc($_GET['d4']);
}
if(!empty($_GET['keyid'])){
	$wherea[]="account like '%".m_esc($_GET['keyid'])."%'";
	$logw[]="account like ".m_esc($_GET['keyid']);
}
if(!empty($_GET['keyne'])){
	$wherea[]="name like '%".m_esc($_GET['keyne'])."%'";
	$logw[]="name like ".m_esc($_GET['keyne']);
}
if(!empty($_GET['keye'])){
	$wherea[]="email like '%".m_esc($_GET['keye'])."%'";
	$logw[]="email like ".m_esc($_GET['keye']);
}
if(ck_num($_GET['ms'])){
	$wherea[]="sex='".m_esc($_GET['ms'])."'";
	$lurl.="&ms=".$_GET['ms'];
	$logw[]="sex = ".m_esc($_GET['ms']);
}
if(ck_num($_GET['ml'])){
	$wherea[]="status='".m_esc($_GET['ml'])."'";
	$lurl.="&ml=".$_GET['ml'];
	$logw[]="tatus = ".m_esc($_GET['ml']);
}
if (ck_num($_GET['mi'])){
	$wherea[]="ulevel='".m_esc($_GET['mi'])."'";
	$lurl.="&mi=".$_GET['mi'];
	$logw[]="ulevel = ".m_esc($_GET['mi']);
}
if ($_GET['mw']){
	$wherea[]="fm='".m_esc($_GET['mw'])."'";
	$lurl.="&mw=".$_GET['mw'];
	$logw[]="fm = ".m_esc($_GET['mw']);
}
if(!empty($wherea)){
	$where=" WHERE ".implode(' and ',$wherea);
	$logws=implode(' and ',$logw);
}
$rownum = 20; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$querya=$db->query("SELECT id,account,name,sex,email,status,ulevel,ctime,etime,cip,fm FROM user  $where  order by  id  DESC"." LIMIT $TP, $rownum");
/*寫入log*/
$descrip="view user_manage.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");
/*下一頁*/
$query_num = $db->query("SELECT COUNT(*) FROM user ".$where);
			$q_num = $db->fetch_row($query_num);
			$product_page_num = $q_num[0];		
			$page_change 	  = ceil($product_page_num/$rownum);
			$_GET=gt($_GET);	
			$myURL 			  = "index.php?pid=".$_GET['pid'].$lurl."&ToPage=";
$user_sex=array(0=>'女',1=>'男');
$user_status=array(0=>'關閉',1=>'開啟');
$user_ulevel=array(0=>'未認證',1=>'已認證');
$querywe = $db->query("select distinct  fm from user");
?>
<style type="text/css">
	@import "include/datepick/jquery.datepick.css";
</style>
<script type="text/javascript" src="include/datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="include/datepick/jquery.datepick-zh-TW.js"></script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
    <div class="right_b">
        <form name="form1" id="form1" action="" enctype="multipart/form-data" method="get">
            <table cellpadding="0" cellspacing="0" class="menutable" height="100%">
                <tr>
                    <td class="tableTitle" colspan="10">使用者列表</td>
                </tr>
                <tr>
                    <td width="150" align="center">搜尋(帳號)</td>
                    <td><input type="text" name="keyid" id="keyid" value="<?=ht(ds($_GET['keyid']))?>" /></td>
                    <td width="150" align="center">搜尋(姓名)</td>
                    <td><input type="text" name="keyne" id="keyne" value="<?=ht(ds($_GET['keyne']))?>" /></td>					
                    <td width="150" align="center">搜尋(信箱)</td>
                    <td><input type="text" name="keye" id="keye" value="<?=ht(ds($_GET['keye']))?>" /></td>					
				</tr>
				<tr>
				    <td align="center">性別</td>
					<td ><input type="radio" name="ms" id="ms" value="" <?=empty($_GET['ms'])? ' checked="checked"': '';?> />不拘
						<? $x=1;foreach($user_sex as $k => $v){?><input type="radio" name="ms" id="ms"  value="<?=$k?>" <?=($k==$_GET['ms'] && ck_num($_GET['ms']))? ' checked="checked"': '';?> />
							<?=$v;?>
								<?=($x%3==0)?'<br>':'';?>
									<? ++$x;}?>
					</td> 
                    <td width="150" align="center">狀態</td>
                    <td ><input type="radio" name="ml" id="ml" value="" <?=empty($_GET['ml'])? ' checked="checked"': '';?> />不拘
                        <? $x=1;foreach($user_status as $k => $v){?><input type="radio" name="ml" id="ml"  value="<?=$k?>" <?=($k==$_GET['ml'] && ck_num($_GET['ml']))? ' checked="checked"': '';?> />
                            <?=$v;?>
                                <?=($x%3==0)?'<br>':'';?>
                                    <? ++$x;}?>
                    </td>                    
					<td width="150" align="center">認證</td>
					<td><input type="radio" name="mi" id="mi" value="" <?=empty($_GET['mi'])? ' checked="checked"': '';?> />不拘
						<? $x=1;foreach($user_ulevel as $k => $v){?><input type="radio" name="mi" id="mi"  value="<?=$k?>" <?=($k==$_GET['mi'] && ck_num($_GET['mi']))? ' checked="checked"': '';?> />
							<?=$v;?>
								<?=($x%3==0)?'<br>':'';?>
									<? ++$x;}?>
					</td> 
				</tr>					
				<tr>
				    <td align="center">何處註冊</td>
					<td>					
						<select name="mw" id="mw">
							<option value="" >請選擇</option>
							<?while($wl = $db->fetch_array($querywe)){?>	
							<option value="<?=$wl['fm']?>" <?if ($_GET['mw'] == $wl["fm"]) { echo 'selected="selected"'; } ?> ><?=$wl['fm']?></option>					
							<?}?>
						</select>
					</td>						
						<td width="150" align="center">建立時間</td>
					<td >從 <input type="text" name="d1" id="d1" class="date_pick" value="<?=$_GET['d1']?>" style="width:70px;" maxlength="10" />~到 <input type="text" name="d2" id="d2" class="date_pick" value="<?=$_GET['d2']?>" style="width:70px;" maxlength="10" /></td>					
					<td width="150" align="center">修改時間</td>
					<td >從 <input type="text" name="d3" id="d3" class="date_pick" value="<?=$_GET['d3']?>" style="width:70px;" maxlength="10" />~到 <input type="text" name="d4" id="d4" class="date_pick" value="<?=$_GET['d4']?>" style="width:70px;" maxlength="10" /></td>					
				<tr>
					<td >
					     <? if(in_array('a41',$p_array)){?><input type="button" value="新增人員" onclick="dialog('新增','iframe:user_manage_add.php','580px','660px','iframe');" />
                          <? }?>
                    </td>
                    <td colspan="9"><input type="submit" value="送出" /><input type="hidden" name="pid" value="<?=ht($_GET['pid'])?>" /></td>					
                </tr>
            </table>
        </form>
    </div>
 <div class="right_b">
	<table cellpadding="0" cellspacing="0" class="menutable" height="100%">
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
				<td width="3%" align="center">ID</td>		
				<td width="10%" align="center">帳號</td>						
				<td width="9%" align="center">姓名</td>
				<td width="3%" align="center">性別</td>
				<td width="15%" align="center">電子信箱</td>
				<td width="10%" align="center">從何處註冊</td>
				<td width="5%" align="center">狀態</td>
				<td width="5%" align="center">認證</td>
				<td width="15%" align="center">建立時間</td>			
				<td width="15%" align="center">修改時間</td>									
				<td width="10%" align="center">功能</td>									
			</tr>				
		<? $i=0; $black='#FFFFCC'; $bg=($i%2==1)?$black:'';?>
		<? while($al = $db->fetch_array($querya)){ ?>
			<tr class="chbg" bgcolor="<?=$bg?>">
				<td align="center">
					<?=ht($al['id'])?>
					<input type="hidden" name="uid" value="<?=$al['id']?>">										
				</td>								
				<td align="center">
					<?=ht($al['account']) ?>
					<input type="hidden" name="user_name" value="<?=$al['account']?>">													
				</td>
				<td align="center">										
					<?=ht($al['name']) ?>
				<input type="hidden" name="name" value="<?=$al['name']?>">										
				</td>  								 		
				<td align="center">
					<?=' '.$user_sex[$al['sex']]?>											
				</td>  		
				<td align="center">
					<?=ht($al['email']) ?>
				</td> 		
				<td align="center">
					<?=ht($al['fm']) ?>
				</td> 					

				<td align="center">
					<?=' '.$user_status[$al['status']]?>
				</td>
				<td align="center">
					<?=' '.$user_ulevel[$al['ulevel']]?>											
				</td>				
				<td align="center">
					<?=ht($al['ctime']) ?>
				</td> 				
				<td align="center">
					<?=ht($al['etime']) ?>
				</td> 				
				<td align="center">
				<? if(in_array('e41',$p_array)){?><input type="button" onclick="dialog('修改','iframe:user_manage_edit.php?id=<?=ht($al['id'])?>','580px','770','iframe');" value="修改" />
					 <? }?>
				<? if(in_array('d41',$p_array)){?><input type="button" onclick="dialog('刪除','iframe:user_manage_del.php?id=<?=ht($al['id'])?>','580px','670','iframe');" value="刪除" />
					  <? }?>
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
</body>
</html>