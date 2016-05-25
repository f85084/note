<? // 人員資料列表
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid=38;
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
$wherea=array();
$where='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$lurl='';
$query=$db->query("SELECT * FROM admin_info");
$da=$db->fetch_array($query);
if(!empty($_GET['keyid'])){
	$wherea[]="user_name like '%".m_esc($_GET['keyid'])."%'";
	$logw[]="user_name like ".m_esc($_GET['keyid']);
}
if(!empty($_GET['keyne'])){
	$wherea[]="name like '%".m_esc($_GET['keyne'])."%'";
	$logw[]="name like ".m_esc($_GET['keyne']);
}
if(!empty($_GET['md'])){
	$wherea[]="del='".m_esc($_GET['md'])."'";
	$lurl.="&md=".$_GET['md'];
	$logw[]="del = ".m_esc($_GET['md']);
}
if(ck_num($_GET['mg'])){
	$wherea[]="group_uid='".m_esc($_GET['mg'])."'";
	$lurl.="&mg=".$_GET['mg'];
	$logw[]="group_uid = ".m_esc($_GET['mg']);
}
if(ck_num($_GET['ml'])){
	$wherea[]="is_lock='".m_esc($_GET['ml'])."'";
	$lurl.="&ml=".$_GET['ml'];
	$logw[]="is_lock = ".m_esc($_GET['ml']);
}
if (ck_num($_GET['mi'])){
	$wherea[]="up_id='".m_esc($_GET['mi'])."' || id='".m_esc($_GET['mi'])."' ";
	$lurl.="&mi=".$_GET['mi'];
	$logw[]="up_id = ".m_esc($_GET['mi']);
}
if(!empty($wherea)){
	$where=" WHERE ".implode(' and ',$wherea);
	$logws=implode(' and ',$logw);
}

$rownum = 20; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	
{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$querya=$db->query("SELECT uid,group_uid,user_name,pass_word,name,birthday,phone,cellphone,thor,remark,del,is_lock FROM admin_info  $where  order by uid asc"." LIMIT $TP, $rownum");


/*寫入log*/
$descrip="view manage.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");

/*下一頁*/
$query_num = $db->query("SELECT COUNT(*) FROM admin_info ".$where);
			$q_num = $db->fetch_row($query_num);
			$product_page_num = $q_num[0];		
			$page_change 	  = ceil($product_page_num/$rownum);
			$_GET=gt($_GET);	
			$myURL 			  = "index.php?pid=".$_GET['pid'].$lurl."&ToPage=";


$admin_del=array("N" => "否", "Y" => "是");
$admin_group=array(0=>'管理者',1=>'開發人員',2=>'編輯人員',3=>'網頁設計師')	;
$admin_is_lock=array(0=>'未鎖定',1=>'鎖定');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>

<body>

    <style type="text/css">
        @import "include/datepick/jquery.datepick.css";
		    <script type="text/javascript" src="include/datepick/jquery.datepick.js"></script>
    <script type="text/javascript" src="include/datepick/jquery.datepick-zh-TW.js"></script>
    </style>
	
    <div class="right_b">
        <form name="form1" id="form1" action="" enctype="multipart/form-data" method="get">
            <table cellpadding="0" cellspacing="0" class="menutable" height="100%">
                <tr>
                    <td class="tableTitle" colspan="10">資訊列表</td>
                </tr>
                <tr>
                    <td width="150" align="center">搜尋(帳號)</td>
                    <td><input type="text" name="keyid" id="keyid" value="<?=ht(ds($_GET['keyid']))?>" /></td>
                    <td width="150" align="center">搜尋(姓名)</td>
                    <td><input type="text" name="keyne" id="keyne" value="<?=ht(ds($_GET['keyne']))?>" /></td>					
                    <td align="center">刪除</td>
                    <td colspan="5"><input type="radio" name="md" id="md" value="" <?=empty($_GET['md'])? ' checked="checked"': '';?> />不拘
                        <? $x=1;foreach($admin_del as $k => $v){?><input type="radio" name="md" id="md"  value="<?=$k?>" <?=($k==$_GET['md'] && ($_GET['md']))? ' checked="checked"': '';?> />
                            <?=$v;?>
                                <?=($x%3==0)?'<br>':'';?>
                                    <? ++$x;}?>
                    </td>
				</tr>
				<tr>
					<td width="150" align="center">群組</td>
					<td>
					<select name="mg" id="mg">
					<option value="" >請選擇群組</option>
					<?foreach($admin_group as $key => $value){?>
					<option value="<?=$key?>"><?= $value; ?></option>					
					<?}?>
					</select>		
                    <td width="150" align="center">鎖定</td>
                    <td colspan="5"><input type="radio" name="ml" id="ml" value="" <?=empty($_GET['ml'])? ' checked="checked"': '';?> />不拘
                        <? $x=1;foreach($admin_is_lock as $k => $v){?><input type="radio" name="ml" id="ml"  value="<?=$k?>" <?=($k==$_GET['ml'] && ($_GET['ml']))? ' checked="checked"': '';?> />
                            <?=$v;?>
                                <?=($x%3==0)?'<br>':'';?>
                                    <? ++$x;}?>
                    </td>
				</tr>					
                <tr>
                    <td>
					</td>
					<td>
					     <? if(in_array('a38',$p_array)){?><input type="button" value="新增人員" onclick="dialog('新增','iframe:manage_add.php','800px','500px','iframe');" />
                          <? }?>
						 <? if(in_array('p38',$p_array)){?><input type="button" value="新增權限頁面" onclick="dialog('頁面權限','iframe:manage_purview_page.php','800px','350px','iframe');" />
						    <input type="hidden" name="uid" value="<?=$da['uid']?>">
                            <? }?>	
                    </td>
                    <td colspan="9"><input type="submit" value="送出" /><input type="hidden" name="pid" value="<?=ht($_GET['pid'])?>" /></td>
                </tr>
            </table>
        </form>
    </div>
 <div class="right_b">
  
	            <form name="form2" id="form2" action="" enctype="multipart/form-data" method="post" onsubmit="">
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
	                            <td width="5%" align="center">編號</td>		
	                            <td width="9%" align="center">群組</td>									
	                            <td width="10%" align="center">帳號</td>						
	                            <td width="10%" align="center">姓名</td>
	                            <td width="10%" align="center">生日</td>
	                            <td width="8%" align="center">電話</td>
	                            <td width="8%" align="center">手機</td>
	                            <td width="15%" align="center">提醒</td>
	                            <td width="5%" align="center">刪除</td>
	                            <td width="5%" align="center">鎖定</td>			
	                            <td width="15%" align="center">功能</td>									
	                        </tr>				
	    				<? $i=0; $black='#FFFFCC'; $bg=($i%2==1)?$black:'';?>
	                            <? 
	    						while($al = $db->fetch_array($querya)){  
	    						?>
	                                <tr class="chbg" bgcolor="<?=$bg?>">
	                                    <td align="center">
	                                        <?=ht($al['uid'])?>
											<input type="hidden" name="uid" value="<?=$al['uid']?>">										
	                                    </td>								
	                                    <td align="center">
											<?=' '.$admin_group[$al['group_uid']]?>											
	    							    </td>
	                                    <td align="center">
	                                        <?=ht($al['user_name']) ?>
											<input type="hidden" name="user_name" value="<?=$al['user_name']?>">													
	                                    </td>
	                                    <td align="center">										
	                                        <?=ht($al['name']) ?>
										<input type="hidden" name="name" value="<?=$al['name']?>">										
	    							    </td>  								
	                                    <td align="center">
	                                        <?=ht($al['birthday']) ?>									
	    							    </td>  		
	                                    <td align="center">
	                                        <?=ht($al['phone']) ?>
	    							    </td>  		
	                                    <td align="center">
	                                        <?=ht($al['cellphone']) ?>
	    							    </td> 
	                                    <td align="center">
	                                        <?=ht($al['remark']) ?>
	    							    </td> 
	                                    <td align="center">
											<?=' '.$admin_del[$al['del']]?>
	    							    </td>
	                                    <td align="center">
											<?=' '.$admin_is_lock[$al['is_lock']]?>											
											
	    							    </td>								
                    <td align="center">
                    <? if(in_array('p38',$p_array)){?><input type="button" onclick="dialog('權限','iframe:manage_purview.php?uid=<?=ht($al['uid'])?>','800px','380','iframe');" value="權限" />
                         <? }?>										
                    <? if(in_array('e38',$p_array)){?><input type="button" onclick="dialog('修改','iframe:manage_edit.php?uid=<?=ht($al['uid'])?>','800px','470px','iframe');" value="修改" />
                         <? }?>
                    <? if(in_array('d38',$p_array)){?><input type="button" onclick="dialog('刪除','iframe:manage_del.php?uid=<?=ht($al['uid'])?>','800px','380','iframe');" value="刪除" />
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
</html>