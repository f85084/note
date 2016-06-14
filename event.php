<? // 活動參加者列表 
mb_internal_encoding("UTF-8");
session_start();
if(isset($_GET['pg'])){
	if(is_file($_GET['pg'].'.php')){include($_GET['pg'].'.php'); exit();}
}
$_SESSION['r_url']=$_SERVER['QUERY_STRING'];
$r_no = trim(rand(0, 2147483647)).trim(rand(0, 2147483647));
include_once "../include/functions.php";
include_once "_exclusive_list.php";
//$conn = db_connect();
$thorid=19;
$cur_time = date("Y-m-d H:i:s");
if(!in_array($thorid,$p_array)){header("Location:login.php"); exit();}
$wherea=array();
$where='';
$logws='';
$logw=array();
$_GET=ck_gp($_GET);
$ev_id= $_GET["ev_id"];

$ev_name = $_GET["ev_name"];
$ta = $_GET["ta"];
$wt = $_GET["wt"];
$d1 = $_GET["d1"];
$d2 = $_GET["d2"];
$where = " where A.ev_status <> '3' ";
if (!empty($ev_id)) $where.= " and  A.ev_id = '".sqlStr($ev_id)."' ";
if (!empty($ev_name)) $where.= " and  A.ev_name like '%".sqlStr($ev_name)."%' ";
if (!empty($d1)) $where.= " and  A.ev_endtime >= '".sqlStr($d1)."' ";
if (!empty($d2)) $where.= " and  A.ev_endtime < date_add('" . sqlStr($d2) . "', interval 1 day) ";
if (!empty($wt)) $where.= " and  A.ev_web_type_uid = '".sqlStr($wt)."' ";
if (!empty($ta)) $where.= " and  A.ev_id in (select ev_id from event_tags where name = '".sqlStr($ta)."' ) ";
$rownum = 20; 
IF($_GET["ToPage"] > "1" && ck_num($_GET["ToPage"]))	{ $TP = ($_GET["ToPage"]-1)*$rownum; }	ELSE	{ $TP = 0; }
$sql = "select A.ev_id,A.ev_tag_code,A.ev_name,A.ev_prog_path,A.ev_is_special_act,A.ev_debug_auth_code,A.ev_web_type_uid from event as A  ";
$sql.= $where;
$count_sql = $sql;
$sql.= " order by A.ev_id desc ";
$output_sql = "select ev_id as '活動編號', ev_name as '活動名稱' ";
$_SESSION["output_sql"] = $output_sql;
$sql.= "LIMIT $TP, $rownum";
$count_sql = "select count(*) from (".$count_sql.") as D";

//$descrip = "view /event/event.php ".sqlStr($where);
//$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('$tid','0','$admin_d[uid]','0','$timeformat','$timestamp','$descrip')");

$query = $db->query($sql);
$query_num = $db->query($count_sql);
			$q_num = $db->fetch_row($query_num);
			$product_page_num = $q_num[0];		
			$page_change 	  = ceil($product_page_num/$rownum);
			$_GET=ht($_GET);	
			$myURL 			  = "index.php?pid=".$_GET['pid'].$lurl."&ev_id=".$ev_id."&ev_name=".$ev_name."&d1=".$d1."&d2=".$d2."&ta=".$ta."&wt=".$wt."&ToPage=";
if (is_null($ev_id)) {
	echo "<script>location.href='" . $myURL . "'</script>";
	exit;
}
$sql = "select A.ev_id, A.ev_name from event as A where A.ev_status <> '3' order by A.ev_id desc ";
$rs = $db->query($sql);

$rs1 = $db->query("SELECT title,uid,code,colort FROM web_type WHERE type='0' and del='N' and disp='1' order by sort,uid desc");//標籤array
$tagArray=array();
while($tagd = $db->fetch_array($rs1)){ $tagArray[]=$tagd; $taga[$tagd['code']]['title']=$tagd['title']; $taga[$tagd['code']]['colort']=empty($tagd['colort'])?'Z1':$tagd['colort']; $taga[$tagd['code']]['id']=$tagd['code'].'_'.$tagd['uid']; }

$webType=$webTypes=array();
$rs2 = $db->query("SELECT title,uid FROM web_type Where type='1' and del='N' and uid!='25' and upid !='0' order by disp desc,sort,uid desc");
while($d = $db->fetch_array($rs2)){$webType[]=$d; $webTypes[$d['uid']]=$d['title'];}

$_GET=ht($_GET);
?>
    <script>
        function output_data(query_type) {
            document.form_output.query_type.value = query_type;
            document.form_output.submit();
        }

        function del_event(ev_id, ev_name) {
            if (confirm("確定要刪除活動＂" + ev_id + "：" + ev_name + "＂嗎？")) {
                document.form_del.action = 'event_del.php';
                document.form_del.act.value = 'del';
                document.form_del.del_id.value = ev_id;
                document.form_del.del_name.value = ev_name;
                document.form_del.submit();
            }
        }
    </script>
    <div class="right_b">
        <style type="text/css">
            @import "include/datepick/jquery.datepick.css";
        </style>
        <script type="text/javascript" src="include/datepick/jquery.datepick.js"></script>
        <script type="text/javascript" src="include/datepick/jquery.datepick-zh-TW.js"></script>
        <form name="form1" id="form1" action="" enctype="multipart/form-data" method="get">
            <table cellpadding="0" cellspacing="0" class="menutable" height="100%">
                <tr>
                    <td class="tableTitle" colspan="2">活動列表</td>
                </tr>
                <tr>
                    <td width="150" align="center">活動名稱</td>
                    <td>
                        <select name="ev_id" id="ev_id" onchange="document.form1.submit();">
<option value="" >請選擇活動</option>
<?php while($r = mysql_fetch_assoc($rs)){ ?>
<option value="<?php echo $r["ev_id"]; ?>" <?php if ((int)$ev_id == (int)$r["ev_id"]) { echo 'selected="selected"'; } ?> ><?php echo $r["ev_id"]."：".$r["ev_name"]; ?></option>
<?php } ?>
</select><br/><br/><input type="text" name="ev_name" id="ev_name" value="<?=tohtmlspecialchars($_GET['ev_name'])?>" /></td>
                </tr>
                <tr>
                    <td align="center">活動結束日</td>
                    <td>從 <input type="text" name="d1" id="d1" class="date_pick" value="<?=tohtmlspecialchars($d1)?>" style="width:70px;" maxlength="10" />~到 <input type="text" name="d2" id="d2" class="date_pick" value="<?=tohtmlspecialchars($d2)?>" style="width:70px;" maxlength="10" /></td>
                </tr>
                <tr>
                    <td align="center">標籤</td>
                    <td><input type="radio" name="ta" id="ta" value="" <?=empty($_GET[ 'ta'])? ' checked="checked"': '';?> />不拘&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <? foreach($tagArray as $v){?><input type="radio" name="ta" id="ta" value="<?=$v['code']?>" <?=($v[ 'code']==$_GET[ 'ta'])? ' checked="checked"': '';?> />
                            <?=$v['title'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <? }?>
                    </td>
                </tr>
                <tr>
                    <td align="center">館別</td>
                    <td><input type="radio" name="wt" id="wt" value="" <?=empty($_GET[ 'wt'])? ' checked="checked"': '';?> />不拘&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <? $x=1; foreach($webType as $v){?><input type="radio" name="wt" id="wt" value="<?=$v['uid']?>" <?=($v[ 'uid']==$_GET[ 'wt'])? ' checked="checked"': '';?> />
                            <?=$v['title'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <?=($x%5==0)?'<br>':'';?>
                                    <? ++$x;}?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="送出" /><input type="hidden" name="pid" value="<?=ht($_GET['pid'])?>" />　<input type="button" value="新增活動" onclick="dialog('活動新增','iframe:event_add.php?pid=19','1020px','810','iframe');"></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="right_b" id="main_list">
        <table cellpadding="0" cellspacing="0" class="menutable">
            <? if($product_page_num > 0){?>
                <tr>
                    <td colspan="15" class="trd">
                        <table width="100%">
                            <tr>
                                <td width="10%" style="border:none;" nowrap="nowrap">總數：
                                    <?=$product_page_num?>
                                </td>
                                <td width="10%" style="border:none;" nowrap="nowrap"></td>
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
                        <td align="center">活動編號</td>
                        <td align="center">標籤</td>
                        <td align="center">活動名稱</td>
                        <td align="center" nowrap="nowrap">廠商測試連結</td>
                        <td align="center" nowrap="nowrap">版頭</td>
                        <td align="center" nowrap="nowrap">簡介</td>
                        <td align="center" nowrap="nowrap">內容</td>
                        <td align="center" nowrap="nowrap">說明</td>
                        <td align="center" nowrap="nowrap">影片</td>
                        <td align="center" nowrap="nowrap">下載</td>
                        <td align="center" nowrap="nowrap">商品</td>
                        <td align="center" nowrap="nowrap">贈獎</td>
                        <td align="center" nowrap="nowrap">連結</td>
                        <td align="center" nowrap="nowrap">灌票</td>
                        <td align="center" nowrap="nowrap">功能</td>
                    </tr>
                    <? while($ld = $db->fetch_array($query)){
$ev_debug_auth_code = $ld['ev_debug_auth_code'];
$tt=explode(',',$ld['ev_tag_code']);
$preview_debug_link = "";
if (!empty($ld['ev_prog_path']) && $ld['ev_is_special_act'] == "Y") { 
	$preview_link = $ld['ev_prog_path'];
	if (!empty($ev_debug_auth_code)) {
		//在活動目錄下
		if (strrpos($preview_link, "/event/") !== FALSE) {
			if (strrpos($preview_link, "?") !== FALSE) {
				$preview_debug_link = $preview_link."&debug_auth_code=".$ev_debug_auth_code;
			} else {
				$preview_debug_link = $preview_link."?debug_auth_code=".$ev_debug_auth_code;
			}
		}
	}
} else {
	$preview_link = "/event/sp/index.php?ev_id=".$ld['ev_id'];
	if (!empty($ev_debug_auth_code)) {
		$preview_debug_link = $preview_link."&debug_auth_code=".$ev_debug_auth_code;
	}
}

?>
                        <tr class="chbg">
                            <td align="center">
                                <?=$ld['ev_id']?>
                            </td>
                            <td align="center">
                                <? foreach($tt as $value){ $tid=explode('_',$value);?>
                                    <div class="tag <?=$taga[$tid[0]]['colort'];?>">
                                        <?=ht($taga[$tid[0]]['title']);?>
                                    </div>
                                    <? }?>
                                        <div style="clear:both"></div>主館別：<br />
                                        <?=$webTypes[$ld['ev_web_type_uid']]?>
                            </td>
                            <td align="center">
                                <a href="<?php echo $preview_link; ?>" target="_blank">
                                    <?=$ld['ev_name']?>
                                </a>
                            </td>
                            <td align="center">
                                <?php if (!empty($preview_debug_link)) { ?><a href="<?php echo $preview_debug_link; ?>" target="_blank" title="<?=$ld['ev_name']?>">廠商測試連結</a>
                                <?php } ?>
                            </td>
                            <td align="center"><input type="button" title="版頭編輯：<?=$ld['ev_name']?>" value="編輯" onclick="dialog('版頭編輯','iframe:event_title_add.php?ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>','920px','640','iframe','1');"></td>
                            <td align="center"><input type="button" title="簡介編輯：<?=$ld['ev_name']?>" value="編輯" onclick="dialog('簡介編輯','iframe:event_introduction_add.php?ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>','920px','610','iframe','1');"></td>
                            <td align="center"><input type="button" title="內容編輯：<?=$ld['ev_name']?>" value="編輯" onclick="location.href='index.php?pid=19&ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&pg=event_content_block_add&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>'"></td>
                            <td align="center"><input type="button" title="說明編輯：<?=$ld['ev_name']?>" value="編輯" onclick="dialog('說明編輯','iframe:event_explanation_block_add.php?ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>','920px','610','iframe','1');"></td>
                            <td align="center"><input type="button" title="影片編輯：<?=$ld['ev_name']?>" value="編輯" onclick="dialog('影片編輯','iframe:event_video_block_add.php?ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>','920px','610','iframe','1');"></td>
                            <td align="center"><input type="button" title="下載編輯：<?=$ld['ev_name']?>" value="編輯" onclick="location.href='index.php?pid=19&ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&pg=event_download_block_add&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>'"></td>
                            <td align="center"><input type="button" title="商品編輯：<?=$ld['ev_name']?>" value="編輯" onclick="location.href='index.php?pid=19&ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&pg=event_product_block_add&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>'"></td>
                            <td align="center"><input type="button" title="贈獎編輯：<?=$ld['ev_name']?>" value="編輯" onclick="dialog('贈獎編輯','iframe:event_prize_block_add.php?ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>','920px','810','iframe','1');"></td>
                            <td align="center"><input type="button" title="連結編輯：<?=$ld['ev_name']?>" value="編輯" onclick="location.href='index.php?pid=19&ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&pg=event_link_block_add&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>'"></td>
                            <td align="center"><input type="button" title="灌票：<?=$ld['ev_name']?>" value="編輯" onclick="dialog('灌票','iframe:event_prize_vote_edit.php?ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>','400px','800','iframe','1');"></td>
                            <td align="center">
                                <input type="button" title="活動編輯：<?=$ld['ev_name']?>" value="編輯" onclick="dialog('活動編輯','iframe:event_edit.php?pid=19&ev_id=<?=$ld['ev_id']?>&r_no=<?=$r_no?>&ev_name=<?=str_replace(" '", "\' ", $ld['ev_name'])?>','1020px','810','iframe','1');">
                                <br/><br/><input type="button" title="活動刪除：<?=$ld['ev_name']?>" value="刪除" onclick="del_event('<?=$ld['ev_id']?>', '<?=$ld['ev_name']?>');">
                            </td>
                        </tr>
                        <? }?>
                            <? if($product_page_num > 0){?>
                                <tr>
                                    <td colspan="15" class="trd">
                                        <table width="100%">
                                            <tr>
                                                <td width="10%" style="border:none;" nowrap="nowrap">總數：
                                                    <?=$product_page_num?>
                                                </td>
                                                <td width="10%" style="border:none;" nowrap="nowrap"></td>
                                                <td align="center" style="border:none;">
                                                    <a href="<?= $myURL." 1 " ?>" class="bodytext_10_block">
                                                        <font color="#2680BA">第一頁</font>
                                                    </a>&nbsp;&nbsp;&nbsp;<span class="bodytext_10_block"><?  echo $pg;?></span>&nbsp;&nbsp;&nbsp;
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
    <form action="event/output_excel.php" id="form_output" name="form_output" target="_blank" method="post">
        <input type="hidden" name="query_type" id="query_type" value="">
    </form>
    <form action="event_del.php" id="form_del" name="form_del" method="post">
        <input type="hidden" name="act" id="act" value="">
        <input type="hidden" name="del_id" id="del_id" value="">
        <input type="hidden" name="del_name" id="del_name" value="">
    </form>
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