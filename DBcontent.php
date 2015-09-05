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
 // foreach($col_list->fetch(PDO::FETCH_ASSOC) as $name)
 //   {$col_name[]=$name;}
//  var_dump($col_name);
  //$col_list=mysqli_query ($db,'SHOW columns from product');
  //while($a=mysqli_fetch_row($col_list))
  //  {$col_name[]=$a[0];}
//$db->close();
return $col_name;
}

 function DB_content($op){
    $col_name='';
    $sql=connectDB();

    
  //  $db->execute();
    switch($op[0]){

        case "full":

        //$data=mysqli_query ($db,'SELECT * from product');
        $db=$sql->prepare('SELECT * from product');
        $db->execute();
        break;       
        
        case "filter":

        

        switch($op[1][2]){
        case "=":
        $sqlcmd="SELECT * FROM product WHERE {$op[1][0]} = '{$op[1][1]}'";
        $db=$sql->prepare($sqlcmd);
        $db->execute();
        //$str="SELECT * from product WHERE {$op[1][0]} = '{$op[1][1]}'";
        //if(!$data=mysqli_query($db,$str)){
        //    echo "<br>filter error<br>";}
   //     $data=$db->fetch(PDO::FETCH_ASSOC);
  //     var_dump($data);
        break;
        
        case "contain":
          $sqlcmd="SELECT * FROM product WHERE {$op[1][0]} LIKE '%{$op[1][1]}%'";
     //     var_dump($op[1][1]);
       //   var_dump($op[1][0]);
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

     //list column names
    //$col_list=mysqli_query ($db,'SHOW columns from product');
    //$col_list=$db->prepare('SHOW columns from product');
    $col_list=get_column_name();
   // var_dump($col_list);
    foreach($col_list as $name)
      {
      //  var_dump($name);
        $col_name=$col_name.'<th nowrap="nowrap">'.$name.'</th>';
      }
  //    var_dump($col_name);
   // while($a=mysqli_fetch_row($col_list)){
     //        $col_name=$col_name.'<th nowrap="nowrap">'.$a[0].'</th>';
    //};
//$a;
    $content='
    <br>
    <table border=1 cellspacing="1" cellpadding="1" style="word-break:break-all" WIDTH="100%" align="center">
    <tr>
    <th>快照</th>
    '.$col_name.'</tr>';
//         $a = $db->fetch(PDO::FETCH_ASSOC); var_dump($a);

    while($a = $db->fetch(PDO::FETCH_ASSOC))
     {
     
         $content=$content.'<tr>';
         //load snapshot
         $b=$a['ID']."\\".$a['ID']."_1.jpg";
         if (file_exists($b)) {
         $content=$content.'<td align="center">'.'<a href="main.php?ID='.$a['ID']."\"><img src=\"{$a['ID']}\\{$a['ID']}_1.jpg\" HEIGHT=\"40\">";
         }
         else{
            $content=$content.'<td align="center">'.'<a href="main.php?ID='.$a['ID'].'" title="產品連結">產品連結</a>';
         }
        // for($i=0;$i<count($a);++$i){
         foreach($a as $key=>$c){
       //   var_dump($c);
        //  echo '<br>';
        //  var_dump($key);
        //  echo '<br>';
            if($key=="ID" or $key=="NAME"){
                $content=$content.'<td align="center" nowrap="nowrap">'.$c.'</td>';
            }         
            else  {
        //      var_dump($c);
                $content=$content.'<td align="center" class="AutoNewline">'.$c.'</td>';
            }
         }
        $content=$content.'</tr>';
     }
     return $content;
 }
    ?>