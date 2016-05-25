<? session_start();
include('include/no_login.php');
include('../include/common.php');
if(empty($_SERVER['HTTP_REFERER']) || !substr_count($_SERVER['HTTP_REFERER'],$webadmin_config['http_website'])){ exit();}
$thorid='d38';
 if(!in_array($thorid,$p_array) || empty($_GET['uid'])){header("Location:index.php"); exit();} 
$_GET=ck_gp($_GET);
$uid=m_esc($_GET['uid']);
$query=$db->query("SELECT * FROM admin_info where uid=$uid");
$da=$db->fetch_array($query);
if(empty($da['uid'])){header("Location:index.php"); exit();} 
$error='';
$today=date("Y,m,d");
if(!empty($_POST['act']) && $_POST['act']=='add'){
		$db->query("UPDATE admin_info SET del='Y',close_date='$today' WHERE uid='$uid'");
		$error='ok';
 		$descrip="del manage_del.php.php uid=$da[uid] name=$da[name]";
		$db->query("INSERT INTO admin_act_log (tid,pid,uid,aid,atime,ftime,description) VALUES ('38','$id','$admin_d[uid]','3','$timeformat','$timestamp','$descrip')");
		
}
$admin_del=array('N' => '否', 'Y' => '是');
$admin_group=array(0=>'管理者',1=>'開發人員',2=>'編輯人員',3=>'網頁設計師')	;
$admin_sex=array(0=>'女',1=>'男');
$admin_blood=array(0=>'A',1=>'B',2=>'AB',3=>'O');
$admin_marry=array(0=>'未婚',1=>'已婚');
$admin_is_lock=array(0=>'未鎖定',1=>'鎖定');


?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <? include('include/css_js.php');?>
            <style>
                #pre_pic img {
                    max-width: 700px;
                    max-height: 100px;
                    width: expression(this.width >700 && this.height < this.width ? 700: true);
                    height: expression(this.height > 100 ? 100: true)
                }
            </style>
    </head>

    <body>
    <form name="form1" id="form1" action="" enctype="multipart/form-data" method="post" onSubmit="">
        <table cellpadding="0" cellspacing="0" class="menutable">
            <tr>
                <td class="tableTitle" colspan="10">刪除人員</td>
            </tr>
            <tr>
                <td width="150" align="center">群組</td>
                <td><?= $admin_group[$da['group_uid']]; ?></td>
            </tr>            
			<tr>
                <td width="150" align="center">帳號</td>
                <td><?=$da['uid']?></td>
            </tr>           
			<tr>
                <td width="150" align="center">姓名</td>
                <td><?=$da['name']?></td>
            </tr>           
			<tr>
                <td width="150" align="center">生日</td>
                <td><?=$da['birthday'] ?></td>
            </tr>           
			<tr>
                <td width="150" align="center">分機</td>
                <td><?=$da['phone'] ?></td>
            </tr>           
			<tr>
                <td width="150" align="center">手機</td>
                <td><?=$da['cellphone'] ?></td>
            </tr>           
			<tr>
                <td width="150" align="center">性別</td>
                <td><?= $admin_sex[$da['sex']]; ?></td>
            </tr>           
			<tr>
                <td width="150" align="center">血型</td>
                <td><?= $admin_blood[$da['blood']]; ?></td>
            </tr>            <tr>
                <td width="150" align="center">婚姻</td>
                <td><?= $admin_marry[$da['marry']]; ?></td>
            </tr>      
			<tr>
                <td width="150" align="center">層級</td>
                <td><?=$da['user_level']?></td>
            </tr>		
			<tr>
                <td width="150" align="center">鎖定</td>
                <td><?= $admin_is_lock[$da['is_lock']]; ?></td>
            </tr>
			<tr>
                <td width="150" align="center">備註</td>
                <td><?=$da['remark']?></td>
            </tr>
			<tr>
                <td width="150" align="center">開始時間</td>
                <td><?=$da['start_date'] ?></td>
            </tr>
			<tr>
                <td width="150" align="center">結束時間</td>
                <td><?=$da['close_date'] ?></td>
            </tr>
                <tr>
                    <td colspan="10" align="center">
                                <div id="subm_1" style="height:20px; text-align:center"><input type="submit" value="刪　　　　除" /><input type="hidden" name="act" value="add" /></div>
                            </td>
                        </tr>
            </table>
        </form>
        <? if($error=='ok'){?>
            <script>
                alert('刪除成功');
                parent.referu('');
            </script>
            <? }?>
    </body>

    </html>