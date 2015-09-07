 <?php
 header('Content-Type: text/html; charset=utf8');

 function connectDB(){
 // try{
    $mysqli = new mysqli("127.0.0.1", "root", "egy578", "azone");
   $db = new PDO('mysql:dbname=azone;host=127.0.0.1', 'root', 'egy578',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
  //  if ($db->connect_errno) {
   //     echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
   // }
//}
    return $db;
}

function get_column_name(){
  //$col_name=array();
  $db=connectDB();
  $sqlcmd='DESCRIBE product';
  $sql=$db->prepare($sqlcmd);
  $sql->execute();
  $col_name=$sql->fetchALL(PDO::FETCH_COLUMN); 

return $col_name;
}

function queryColor($id){
  //get colorIDs of product
  $db=connectDB();
  $sqlcmd="SELECT colorID FROM productcolor WHERE ID=?"; // injection preventing
  $sql=$db->prepare($sqlcmd);
  $sql->execute(array($id));
  $colorid=$sql->fetchall(PDO::FETCH_COLUMN,0);

//get colornames
  $cid=join(",",$colorid);
  $sqlcmd="SELECT colorname FROM color WHERE colorID IN ({$cid})";
  $sql=$db->prepare($sqlcmd); 
  $sql->execute();
  $colorname=$sql->fetchall(PDO::FETCH_COLUMN,0);
  
  return $colorname;
}

 function DB_content($op){
    $col_name='';
    $sql=connectDB();

    switch($op[0]){

        case "full":
        $db=$sql->prepare('SELECT * from product');
        $db->execute();
        break;       
        
        case "filter":
        switch($op[1][2]){
        case "=":
        $sqlcmd="SELECT * FROM product WHERE {$op[1][0]} = '{$op[1][1]}'";
        $db=$sql->prepare($sqlcmd);
        $db->execute();
        break;
        
        case "contain":
          $sqlcmd="SELECT * FROM product WHERE {$op[1][0]} LIKE '%{$op[1][1]}%'";
        $db=$sql->prepare($sqlcmd);
        $db->execute();
        break;

        case ">":
          $sqlcmd="SELECT * FROM product WHERE {$op[1][0]} > '{$op[1][1]}'";
        $db=$sql->prepare($sqlcmd);
        $db->execute();
        break; 

        case "<":
          $sqlcmd="SELECT * FROM product WHERE {$op[1][0]} < '{$op[1][1]}'";
        $db=$sql->prepare($sqlcmd);
        $db->execute();
        break;
        }
        break;
    }

  
    $col_list=get_column_name();
 
    foreach($col_list as $name)
      {
   
        $col_name=$col_name.'<th nowrap="nowrap">'.$name.'</th>';
      }
 
    $content='
    <br>
    <table border=1 cellspacing="1" cellpadding="1" style="word-break:break-all" WIDTH="100%" align="center">
    <tr>
    <th>快照</th>
    '.$col_name.'</tr>';


    while($a = $db->fetch(PDO::FETCH_ASSOC))
     {
     
         $content.='<tr>';
         //load snapshot
         $b=$a['ID']."\\".$a['ID']."_1.jpg";
         if (file_exists($b)) {
         $content.='<td align="center">'.'<a href="main.php?ID='.$a['ID']."\"><img src=\"{$a['ID']}\\{$a['ID']}_1.jpg\" HEIGHT=\"40\">";
         }
         else{
            $content.='<td align="center">'.'<a href="main.php?ID='.$a['ID'].'" title="產品連結">產品連結</a>';
         }
  
         foreach($a as $key=>$c){
      //        var_dump($a);
            if($key=="ID" or $key=="NAME"){
                $content.='<td align="center" nowrap="nowrap">'.$c.'</td>';
            }         
            else if($key=="COLOR"){
              $content.='<td align="center" class="AutoNewline">';
              $colors=queryColor($a["ID"]);
              if(!empty($colors)){
                foreach($colors as $c){
                 $content.=$c."<br>";
                }
              } else {
                $content.='N/A';
              }
              $content.='</td>';
            }
              else {

                $content.='<td align="center" class="AutoNewline">'.$c.'</td>';
            }
         }
        $content.='</tr>';
     }
     return $content;
 }
    ?>