<? session_start();
include('include/no_login.php');
include('../include/common.php');
$thorid='31';
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
	$_POST=m_esc(ck_gp($_POST));
	$uid=$_SESSION['admin_uid'];
	$old_pass_word=md5($_POST['old_pass_word']);
	$querya=$db->query("SELECT uid,pass_word FROM  admin_info WHERE  uid = '$uid'");
	$admin_d = $db->fetch_array($querya);
     if(!empty($old_pass_word) && $admin_d['pass_word']==$old_pass_word){
           echo 1; // 回傳給參數
     }else{
           echo 0; // 回傳給參數
      }

?>
