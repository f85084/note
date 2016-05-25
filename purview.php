<? // 排程執行記錄
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$thorid=39;
if(!in_array($thorid,$p_array)){header("Location:login.php"); exit();}
//include('../include/color_array.php');
$wherea=array();
$where='';
$_GET=ck_gp($_GET);

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

$querya = $db->query("SELECT * FROM b_s_group $where order by id  asc");

 	$query_t = $db->query("SELECT uid,thor FROM admin_info");
	$id_t=array();
   while($t = $db->fetch_array(query_t)){
	$id_t[$t['uid']]=$t['thor'];
	echo $id_t[$t['uid']];
	} 	

/*  $th=explode(',',$t); */ 
/* echo implode(',',$th); */
/* 
$k=array_search($id_t,$th);
echo $k;
echo implode(',',$k); */


/* 	
	if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}

	*/
$querye = $db->query("select id,up_id,name from b_s_group");
$id_n=array();
while($n = $db->fetch_array($querye)){
	$id_n[$n['id']]=$n['name'];

	} 	


/* $query_idck=$db->query("SELECT uid,thor FROM admin_info where uid=$uid");	
$id_ck=array();
while($m_id = $db->fetch_array($query_idck)){
	$id_ck[$m_id['uid']]=$m_id['thor'];
} 

 */
 	$query_all = $db->query("SELECT uid,group_uid,user_name,thor FROM admin_info WHERE uid='35'");
	$all_id = $db->fetch_array($query_all);
	$all_array=explode(',',$all_id['thor']);	
/* echo implode(',',$all_array);  */
?>

    <div class="right_b" id="main_list">
        <table cellpadding="0" cellspacing="0" class="menutable">
            <tr>
                <td align="center" width="5%">id</td>
                <td align="center" width="15%">上層ID</td>
                <td align="center" width="15%">系統名稱</td>
                <td align="center" width="10%">排序</td>
                <td align="center" width="5%">刪除</td>
                <td align="center" width="15%">程式名稱</td>					
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
					<? $apid='a'.ht($ld['id']); 
						if(in_array($apid,$all_array)){?><input type="button" onclick="dialog('新增權限','iframe:purview_add.php?id=<?=ht($ld['id'])?>','800px','380','iframe');" value="新增權限" />		
                         <? }?>					
                    <? $epid='e'.ht($ld['id']); 
						if(in_array($epid,$all_array)){?><input type="button" onclick="dialog('修改權限','iframe:purview_edit.php?id=<?=ht($ld['id'])?>','800px','380','iframe');" value="修改權限" />
                         <? }?>
                    <? $dpid='d'.ht($ld['id']); 
						if(in_array($dpid,$all_array)){?><input type="button" onclick="dialog('刪除權限','iframe:purview_del.php?id=<?=ht($ld['id'])?>','800px','380','iframe');" value="刪除權限" />	
                          <? }?>
                  </td>
                </tr>
                <? }?>
        </table>
    </div>