<? session_start();
include('include/no_login.php');
include('../include/common.php');
$thorid='38';
//print_r ($_POST);
if(!in_array($thorid,$p_array)){header("Location:index.php"); exit();}
	$_POST=m_esc(ck_gp($_POST));
	$user_name=($_POST['user_name']);
	//echo $user_name;
	$query_rid = $db->query("SELECT COUNT(*) FROM admin_info where user_name='$user_name'");
	$q_rid = $db->fetch_row($query_rid);
	$res_rid = $q_rid[0];	
     if (!empty($res_rid)){
           echo 1; // 回傳給參數
     }else{
           echo 0; // 回傳給參數
      }
?>
