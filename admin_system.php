<? // 排程執行記錄
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid=36;
if(!in_array($thorid,$p_array)){header("Location:login.php"); exit();}
//include('../include/color_array.php');
$wherea=array();
$where='';
$_GET=ck_gp($_GET);
//$wherea[]="type='0'";
//$wherea[]="del='N'";
$logws='';
$logw=array();
if(!empty($_GET['keyw'])){
	$wherea[]="name like '%".m_esc($_GET['keyw'])."%'";
	$logw[]="name like ".m_esc($_GET['keyw']);
}
if (ck_num($_GET['md'])){
	$wherea[]="del='".m_esc($_GET['md'])."'";
	$lurl.="&md=".$_GET['md'];
	$logw[]="del = ".m_esc($_GET['md']);
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
$descrip="view admin_system.php ".$logws;
$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");

$query = $db->query("SELECT * FROM b_s_group $where order by id  asc");
$_GET=ht($_GET);

$act_del=array(0 => '否', 1 => '是');

$querye = $db->query("select id,up_id,name from b_s_group");
$id_n=array();
while($n = $db->fetch_array($querye)){
	$id_n[$n['id']]=$n['name'];
} 

$querye = $db->query("select id,up_id,name from b_s_group where up_id=0");
$id_mi=array();
while($mi = $db->fetch_array($querye)){
	$id_mi[$mi['id']]=$mi['name'];
} 

?>
    <div class="right_b">
        <form name="form1" id="form1" action="" enctype="multipart/form-data" method="get">
            <table cellpadding="0" cellspacing="0" class="menutable" height="100%">
                <tr>
                    <td class="tableTitle" colspan="10">資訊列表</td>
                </tr>
                <tr>
                    <td width="150" align="center">搜尋(系統名稱)</td>
                    <td><input type="text" name="keyw" id="keyw" value="<?=ht(ds($_GET['keyw']))?>" /></td>
					 <td width="150" align="center">刪除</td>
                    <td colspan="5"><input type="radio" name="md" id="md" value="" <?=empty($_GET['md'])? ' checked="checked"': '';?> />不拘
                        <? $x=1;foreach($act_del as $k => $v){?><input type="radio" name="md" id="md"  value="<?=$k?>" <?=($k==$_GET['md'] && ck_num($_GET['md']))? ' checked="checked"': '';?> />
                            <?=$v;?>
                                <?=($x%3==0)?'<br>':'';?>
                                    <? ++$x;}?>
                    </td>
                </tr>
				<tr>
					 <td width="150" align="center">上層系統列表</td>
                    <td colspan="5"><input type="radio" name="mi" id="mi" value="" <?=empty($_GET['mi'])? ' checked="checked"': '';?> />不拘
                        <? $x=1;foreach($id_mi as $k => $v){?><input type="radio" name="mi" id="mi"  value="<?=$k?>" <?=($k==$_GET['mi'] && ck_num($_GET['mi']))? ' checked="checked"': '';?> />
                            <?=$v;?>
                                <?=($x%10==0)?'<br>':'';?>
                                    <? ++$x;}?>
                    </td>	
                </tr>					
                <tr>
                    <td>
                        <? if(in_array('a36',$p_array)){?><input type="button" value="新增" onclick="dialog('新增','iframe:admin_system_add.php','800px','360','iframe');" />
                            <? }?>
                    </td>
                    <td colspan="9"><input type="submit" value="送出" /><input type="hidden" name="pid" value="<?=ht($_GET['pid'])?>" /></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="right_b" id="main_list">
        <table cellpadding="0" cellspacing="0" class="menutable">
            <tr>
                <td align="center" width="5%">id</td>
                <td align="center" width="20%">上層ID</td>
                <td align="center" width="20%">系統名稱</td>
                <td align="center" width="10%">排序</td>
                <td align="center" width="5%">刪除</td>
                <td align="center" width="20%">程式名稱</td>
                <td align="center" width="20%">功能</td>
            </tr>
            <? while($ld = $db->fetch_array($query)){?>
                <tr class="chbg">
                    <td align="center">
                        <?=ht($ld['id'])?>
					 <input type="hidden" name="id" value="<?=$ld['id']?>">						
                    </td>
                    <td align="center">
                        <?=ht($ld['up_id']);?>,<?=$id_n[$ld['up_id']];?>
                    </td>
                    <td align="center">
                        <?=$ld['name']?>
                    </td>
                    <td align="center">
                        <?=$ld['sortn']?>
                    </td>
                    <td align="center">
                        <?=ht($ld['del'])=='1'?'是':'否';?>
                    </td>
                    <td align="center">
                        <?=$ld['programs_p']?>
                    </td>
                    <td align="center">
                    <? if(in_array('e36',$p_array)){?><input type="button" onclick="dialog('修改','iframe:admin_system_edit.php?id=<?=ht($ld['id'])?>','800px','380','iframe');" value="修改" />
                         <? }?>
                    <? if(in_array('d36',$p_array)){?><input type="button" onclick="dialog('刪除','iframe:admin_system_del.php?id=<?=ht($ld['id'])?>','800px','380','iframe');" value="刪除" />
                          <? }?>
                  </td>
                </tr>
                <? }?>
        </table>
    </div>