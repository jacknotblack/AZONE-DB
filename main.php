<?php
header('Content-Type: text/html; charset=utf8');
include ("DBcontent.php");
include("menu.php");
//var_dump($_REQUEST);
if (!isset($_REQUEST["menu_op"])){$_REQUEST["menu_op"]="總覽";}

//use for test
//var_dump($_REQUEST);
//echo "<br>";

//default DB=full display
$DB_display=array("full",array());

//retrieve column names
$col_names=get_column_name();

//make menu
$menu=new menu;

//retrieve menu operation

if(isset($_GET["ID"])){$menu->create_profile($_GET["ID"]);}
else $menu->switchform($_REQUEST["menu_op"]);




function make_page($menu,$form,$DBcontent,$message) {
    $page = "
    <html>
    <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <title>尚肯產品資料庫</title>
    </head>
    <body>
    <div id='wrapper'> 
    <div id='menu' style='text-align:center'>
    $menu
    </div>

    <div id='message' style='text-align:center'>
    $message
    </div>
    
    <div id='form' style='text-align:center'>
    $form
    </div>
    
    <div id='DB' style='text-align:center'>
    $DBcontent
    </div>
    
    </div>
    </body>
    </html>";
    
    return $page;
}

echo make_page($menu->display_menu(),$menu->form,DB_content($DB_display),$menu->message);


?>
