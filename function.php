
//ck_num 寫在 global.func.php 裡

function ck_num($ot){
	if(preg_match("/^([0-9]{1,})$/",$ot)){
		return true;
	}else{
		return false;
	}
}
原本
if(!empty($_GET['mt'])){
	$wherea[]="uid='".m_esc($_GET['mt'])."'";
	$lurl.="&mt=".$_GET['mt'];
	$logw[]="uid = ".m_esc($_GET['mt']);
}
帶入function 如下
<?
if (ck_num($_GET['act'])){
	$wherea[]="aid='".m_esc($_GET['act'])."'";
	$lurl.="&act=".$_GET['act'];
	$logw[]="aid = ".m_esc($_GET['act']);
}

//表格內也需要驗證

//原本
                <tr>
                    <td align="center">動作</td>
                    <td colspan="5"><input type="radio" name="act" id="act" value="" <?=empty($_GET['act'])? ' checked="checked"': '';?> />不拘
                        <? $x=1;foreach($use_act as $k => $v){?><input type="radio" name="act" id="act"  value="<?=$k?>" <?=($k==$_GET['act'] )? ' checked="checked"': '';?> />
                            <?=$v;?>
                                <?=($x%11==0)?'<br>':'';?>
                                    <? ++$x;}?>
                    </td>
                </tr>	
				?>
				
	//			帶入function  && ck_num($_GET['act'])
				                <tr>
                    <td align="center">動作</td>
                    <td colspan="5"><input type="radio" name="act" id="act" value="" <?=empty($_GET['act'])? ' checked="checked"': '';?> />不拘
                        <? $x=1;foreach($use_act as $k => $v){?><input type="radio" name="act" id="act"  value="<?=$k?>" <?=($k==$_GET['act'] && ck_num($_GET['act']))? ' checked="checked"': '';?> />
                            <?=$v;?>
                                <?=($x%11==0)?'<br>':'';?>
                                    <? ++$x;}?>
                    </td>
                </tr>	
				?>