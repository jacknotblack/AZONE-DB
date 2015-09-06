 <?php
 header('Content-Type: text/html; charset=utf8');

 function connectDB(){
    $mysqli = new mysqli("127.0.0.1", "root", "egy578", "azone");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    return $mysqli;
}

function get_column_name(){
  $col_name=array();
  $mysqli=connectDB();
  $col_list=mysqli_query ($mysqli,'SHOW columns from product');
  while($a=mysqli_fetch_row($col_list))
    {$col_name[]=$a[0];}
$mysqli->close();
return $col_name;
}

 function DB_content($op){
    $col_name='';
    $mysqli=connectDB();
    switch($op[0]){
        case "full":
        $data=mysqli_query ($mysqli,'SELECT * from product');
        break;       
        
        case "filter":

        switch($op[1][2]){
        case "=":
        $str="SELECT * from product WHERE {$op[1][0]} = '{$op[1][1]}'";
        if(!$data=mysqli_query($mysqli,$str)){
            echo "<br>filter error<br>";}
        break;
        
        case "contain":
        $str="SELECT * from product WHERE {$op[1][0]} LIKE '%{$op[1][1]}%'";
        if(!$data=mysqli_query($mysqli,$str)){
            echo "<br>filter error<br>";}
        break;

        case ">":
        $str="SELECT * from product WHERE {$op[1][0]} > '{$op[1][1]}'";
        if(!$data=mysqli_query($mysqli,$str)){
            echo "<br>filter error<br>";}
        break; 

        case "<":
        $str="SELECT * from product WHERE {$op[1][0]} < '{$op[1][1]}'";
        if(!$data=mysqli_query($mysqli,$str)){
            echo "<br>filter error<br>";}
        break;
        }
        break;
    }

     //list column names
    $col_list=mysqli_query ($mysqli,'SHOW columns from product');
    while($a=mysqli_fetch_row($col_list)){
             $col_name=$col_name.'<th nowrap="nowrap">'.$a[0].'</th>';
    };

    $content='
    <br>
    <table border=1 cellspacing="1" cellpadding="1" style="word-break:break-all" WIDTH="100%" align="center">
    <tr>
    <th>快照</th>
    '.$col_name.'</tr>';
         
    while($a=mysqli_fetch_row($data))
     {
         $content=$content.'<tr>';
         //load snapshot
         $b=$a[0]."\\".$a[0]."_1.jpg";
         if (file_exists($b)) {
         $content=$content.'<td align="center">'.'<a href="main.php?ID='.$a[0]."\"><img src=\"{$a[0]}\\{$a[0]}_1.jpg\" HEIGHT=\"40\">";
         }
         else{
            $content=$content.'<td align="center">'.'<a href="main.php?ID='.$a[0].'" title="產品連結">產品連結</a>';
         }
         for($i=0;$i<count($a);++$i){
            if($i<2){
                $content=$content.'<td align="center" nowrap="nowrap">'.$a[$i].'</td>';
            }         
            else  {
                $content=$content.'<td align="center" class="AutoNewline">'.$a[$i].'</td>';
            }
         }
        $content=$content.'</tr>';
     }
     return $content;
 }
    ?>