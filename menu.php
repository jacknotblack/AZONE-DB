<?php
header('Content-Type: text/html; charset=utf8');

class menu 
{
	public $form="";
	public $message="";

	function display_menu(){
		$menu="
		<form action='main.php' method='post'>
		<INPUT TYPE=submit VALUE='新增' name='menu_op'>
		<INPUT TYPE=submit VALUE='修改' name='menu_op'>
		<INPUT TYPE=submit VALUE='刪除' name='menu_op'>
		<INPUT TYPE=submit VALUE='查詢' name='menu_op'>
		<INPUT TYPE=submit VALUE='總覽' name='menu_op'>
		</form>    
		";
		return $menu;
	}

	public function switchform($op)
	{
		switch ($op) {
    //enter main menu    

			case "新增":
			$this->addentry_form();  
			$this->message="";
			break;

			case "修改":
			$this->modentry_form();
			$this->message="";
			break;

			case "刪除":
			$this->delentry_form();
			$this->message="";
			break;

			case "查詢":
			$this->filter_form();
			$this->message="";
			break;

			case 'add':
			$this->addentry($_REQUEST);
			$this->form="";
			break;

			case 'mod':
			$this->message="修改<font color='blue'> {$_REQUEST['mod_id']} </font>的資料";
			array_shift($_REQUEST);
			$this->modentry_form2($_REQUEST);
			break;

			case 'mod2':
			array_shift($_REQUEST);
			$this->modentry(array_shift($_REQUEST),$_REQUEST);
			$this->form="";
			break;

			case 'del':
			$this->delentry($_REQUEST["del_prod_id"]);
			$this->form="";
			break;

			case 'filter':
			$GLOBALS['DB_display']=$this->filter($_REQUEST["col_name"],$_REQUEST["filter_op"],$_REQUEST["filter_col"]);		
			$this->message="顯示所有符合  <font color = '#0000FF' >{$_REQUEST["col_name"]}</font><font color='red'> {$_REQUEST["filter_op"]} </font><font color = '#0000FF' >{$_REQUEST["filter_col"]}  </font>的項目";
			$this->form="";
			break;

			case "總覽":
			$this->form="";
			$this->message="";

		}

	}

	function addentry_form(){

		//global $mysqli;
		global $col_names;  
		$this->form='<form class="pure-form pure-form-stacked" action="main.php?menu_op=add" method="post"><fieldset>';
		for($i=0;$i<count($col_names)-1;++$i){
			if ($col_names[$i]=="TAG"){
				$this->form=$this->form.$col_names[$i].'<br><input type=text name='.$col_names[$i].'_add value="N/A"><br>';
			}
			else {
				$this->form=$this->form.$col_names[$i].'<br><input type=text name='.$col_names[$i].'_add><br>';
			}
		}
		$this->form=$this->form."<P><INPUT TYPE=submit name='new_prod' VALUE='新增產品'></p></form>";
	}

	function addentry($add_values){
		$sql=connectDB();
		global $col_names;
		if (preg_match('/\s/',$add_values["ID_add"]) or $add_values["ID_add"]==""){
			$this->message="<font color='red'>項目未新增 ID不可空白</font>";
		//	echo "bad";
			return $message;
		}
		$this->message="產品： <font color='#0000FF'>{$add_values['ID_add']}</font>
		--<font color='blue'> {$add_values['NAME_add']}</font>  已新增";
		$sqlcmd='INSERT into product values(';
			array_shift($add_values);    
			for($i=0;$i<count($col_names)-1;++$i){
				$sqlcmd=$sqlcmd."'".addslashes(array_shift($add_values))."',";
			}
			$sqlcmd=$sqlcmd."DEFAULT)";
$db=$sql->prepare($sqlcmd);
$db->execute();
//if(!mysqli_query($mysqli,$command)){
//	$this->message="<font color='red'>指令未完成 ID不可重複</font>";
//} 
}

function modentry_form(){
	$this->form="
	<form method='post' action='main.php?menu_op=mod'>
	欲修改產品代碼<input type=text name=mod_id><INPUT TYPE=submit name='mod_prod' VALUE='修改產品'></form>";
}

function modentry_form2($id){
	$sql=connectDB();

	global $col_names;

	$sqlcmd="SELECT * from product WHERE ID='".addslashes($id["mod_id"])."'";
	$db=$sql->prepare($sqlcmd);
	$db->execute();
	$data=$db->fetch(PDO::FETCH_ASSOC);
	//if(!$data=mysqli_fetch_row(mysqli_query($mysqli,$command))){echo "<br>filter error<br>";}
	
	//$mysqli->close();
	$this->form="<form method='post' action='main.php?menu_op=mod2&id_mod=".$id["mod_id"]."'>";
	for($i=0;$i<count($col_names)-1;++$i){
		$this->form=$this->form."(新)".$col_names[$i].'<br><input type=text name='.$col_names[$i].'_moded value="'.htmlspecialchars(array_shift($data),ENT_COMPAT,'UTF-8',FALSE).'"><br>';
	}
	$this->form=$this->form."<P><INPUT TYPE=submit name='mod_prod' VALUE='修改產品'></p></form>";
}

function modentry($id,$moded_values){
	$sql=connectDB();
	global $col_names;
	$sqlcmd='UPDATE product SET ';

	for($i=0;$i<count($col_names)-1;++$i){
		$sqlcmd=$sqlcmd.$col_names[$i]." = '".addslashes(array_shift($moded_values))."',";
	}
	$sqlcmd=rtrim($sqlcmd,',');
	$sqlcmd=$sqlcmd." WHERE ID = '".addslashes($id)."'";
	$db=$sql->prepare($sqlcmd);
	$db->execute();
	
//	if (!mysqli_query($mysqli,$command)){
//		printf("Errorcode: %d\n", $mysqli->errno);
//		exit();
//	}
	//$mysqli->close();    
	$this->message="產品： <font color='blue'>{$id}</font> 已修改";
}

function delentry_form(){
	$this->form="
	<form method='post' action='main.php?menu_op=del'>
	產品代碼<input type=text name=del_prod_id><br>
	<P><INPUT TYPE=submit name='del_prod' VALUE='刪除產品'></p>
	</form>
	";    
}

function delentry($id){
	$sql=connectDB();
	$sqlcmd="DELETE FROM `product` WHERE `ID` = '$id'";
	$db=$sql->prepare($sqlcmd);
	$db->execute();
	$this->message="產品： <font color = '#0000FF' >{$id}</font> 已刪除";
//	if(mysqli_query($mysqli,"DELETE FROM `product` WHERE `ID` = '$id'")){$this->message="產品： <font color = '#0000FF' >{$id}</font> 已刪除";}
//	else { $this->message="指令未完成";}
//	$mysqli->close();
}

function filter_form(){
		$col_name='';
		$sql=connectDB();
		$sqlcmd="SHOW columns from product";
		$db=$sql->prepare($sqlcmd);
		$col_list=get_column_name();
//		var_dump($col_list);
		//$col_list=mysqli_query ($mysqli,'SHOW columns from product');
		//while($a=mysqli_fetch_row($col_list)){
		foreach($col_list as $a){
	//		var_dump($a);
			if ($a!="last updated"){$col_name=$col_name.'<option value="'.$a.'">'.$a.'</option>';}
		}
	//	var_dump($col_name);
	//	$mysqli->close();
		$this->form="
		<form method='post' action='main.php?menu_op=filter'>
		<select name='col_name'>".$col_name."        
		</select>
		<select name='filter_op'>
		<option value='='> = </option>
		<option value='contain'> 包含 </option>   
		<option value='>'> > </option>
		<option value='<'> < </option>              
		</select>
		<input type=text name=filter_col>
		<P><INPUT TYPE=submit name='filter_value' VALUE='查詢產品'></p>
		</form>
		";
	}

	function filter($col,$op,$value){
		$filter[0]="filter";
		$filter[1]=array("$col",$value,$op);
		return $filter;
	}

	function create_profile($id){
		Global $DB_display;
    $sql=connectDB();
  //  $form="";
//update memo and tag
    if (isset($_REQUEST['memo'])) {
        //parse into HTML with special chars
        $memo_parsed = $sql->quote($_REQUEST['memo']);
        $tag_parsed = $sql->quote($_REQUEST['tag']);
      //  $tag_parsed= mysqli_real_escape_string($sql, $_REQUEST['tag']);
    	$sqlcmd="UPDATE product SET MEMO= $memo_parsed , TAG=$tag_parsed WHERE ID='{$id}'";
    	$db=$sql->prepare($sqlcmd);
    	$db->execute();
      // mysqli_query($sql,"UPDATE product SET MEMO='{$memo_parsed}' , TAG='{$tag_parsed}' WHERE ID='{$id}'");
    }


//load pic
    $sqlcmd="SELECT pic FROM product WHERE ID='{$id}'";
    $db=$sql->prepare($sqlcmd);
    $db->execute();
    $pic_num=$db->fetch(PDO::FETCH_ASSOC);
    for($i=1;$i<$pic_num['pic']+1;++$i){
        $this->form=$this->form."<img src='{$id}\\{$id}_{$i}.jpg' WIDTH='25%'>";
    }
//load tag
    $sqlcmd="SELECT TAG FROM product WHERE ID='{$id}'";
    $db=$sql->prepare($sqlcmd);
    $db->execute();
    $tag=$db->fetch(PDO::FETCH_ASSOC);
  //  var_dump($tag);
    //$tag=mysqli_fetch_row(mysqli_query($db,"SELECT TAG FROM product WHERE ID='{$id}'"));
    
 //   var_dump(mysqli_fetch_row(mysqli_query($db,"SELECT TAG FROM product WHERE ID='{$id}'")));
    if($tag['TAG']!="N/A"){
        $this->form=$this->form."<img src='{$id}\\{$id}_tag.jpg' WIDTH='15%'style='float:right'>";
    }   

   //$memo=mysqli_fetch_row(mysqli_query($db,"SELECT MEMO FROM product WHERE ID='$id'"));
    $sqlcmd="SELECT MEMO FROM product WHERE ID='{$id}'";
    $db=$sql->prepare($sqlcmd);
    $db->execute();
    $memo=$db->fetch(PDO::FETCH_ASSOC);
   // $tag=mysqli_fetch_row(mysqli_query($db,"SELECT TAG FROM product WHERE ID='$id'"));
   // $db->close();
    $DB_display=$this->filter("ID","=",$id);
    $this->form=$this->form."<form method='post' action='main.php?ID={$id}'><pre>MEMO                                      TAG</pre>
    <textarea style='height: 20%; resize: none; width: 30%;' name=memo rows=10 cols=30 resize:none style='position:relative'>{$memo['MEMO']}</textarea>
    <textarea style='height: 20%; resize: none; width: 30%;' name=tag rows=10 cols=30 resize:none>{$tag['TAG']}</textarea><br>
    <INPUT TYPE=submit name='update_memo' VALUE='更新MEMO&TAG'></p>
    </form>";
   // var_dump($this->form);

}
}
?>