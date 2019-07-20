<html>
<head>
  <meta charset="utf-8">
  <title>mission5</title>
</head>
<?php
$dsn = 'mysql:dbname=tb210049db;host=localhost';
$user = 'tb-210049';
$password = 'UP9pLRA2Nj';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS mission5"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."pass TEXT,"
."now TEXT"
.");";
$stmt = $pdo->query($sql);

$sql ='SHOW TABLES';
$result = $pdo -> query($sql);
foreach ($result as $row){
        echo $row[0];
        echo '<br>';
}
echo "<hr>";

if (isset($_POST["data"])||isset($_POST["delete"])||isset($_POST["number"])){
$name = $_POST["name"];
$data = $_POST["data"];
$delete = $_POST["delete"];
$number = $_POST["number"];
$editnum2 = $_POST["editnum2"];
$wripass = $_POST["wripass"];
$delpass = $_POST["delpass"];
$edpass = $_POST["edpass"];
if(empty($data)&&empty($delete)&&empty($number)){
  echo "error";
}
else{




      if(empty($_POST["editnum2"]) == FALSE){
        $pass = passcheck($editnum2);
        if($pass==$wripass){
          editwrite($_POST["editnum2"],$name,$data);
          uptext2();
          }
      }

      //else
      if(empty($_POST["data"])==FALSE){
        writetext($pdo,$name,$data,$wripass);
        uptext($pdo);
      }

      if(empty($_POST["delete"])==FALSE){
        $pass = passcheck($pdo,$delete);
        echo $pass;
          if($pass==$delpass){
            deletetext($pdo,$delete);
            //numchange($pdo,$_POST["number"]);
            uptext($pdo);
          }
      }
      $changetext = array("","");
      if(empty($_POST["number"])==FALSE){
        $pass = passcheck($pdo,$number);
        echo $pass;
          if($pass==$edpass){
            $changetext = editupcomments($pdo,$_POST["number"]);
          }
      }

    }
  }


function writetext($pdo,$name,$data,$pass){
  $timestamp = time() ;
  $time = date( "Y/m/d/ G:i:s" , $timestamp );

  $sql ='SHOW CREATE TABLE mission5';
  $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment,pass,now) VALUES (:name, :comment, :pass, :now)");
  $sql -> bindParam(':name', $name, PDO::PARAM_STR);
  $sql -> bindParam(':comment', $data, PDO::PARAM_STR);
  $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
  $sql -> bindParam(':now', $time, PDO::PARAM_STR);
  $sql -> execute();

}
//書き込み

function uptext($pdo){
  $sql = 'SELECT * FROM mission5';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchALL();
  foreach ($results as $row){
    echo $row['id'].', ';
    echo $row['name'].', ';
    echo $row['comment'].', ';
    echo $row['pass'].', ';
    echo $row['now'].'<br>';
  echo "<hr>";
  }
}

//表示

function deletetext($pdo,$delete){
  $sql = 'delete from mission5 where id=:id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
  $stmt->execute();
}
//消去

function editupcomments($pdo,$num){
  $sql = 'SELECT * FROM mission5';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchALL();
  foreach ($results as $row){
      if($row['id']==$num){
        $name2=$row['name'];
        $text =$row['comment'];
      }
    }
    return array($num,$name2,$text);
  }
//編集行を表示

function editwrite($num,$name,$data){
  $sql = 'update mission5 set name=:name,comment=:data where id=:id';
  $stmt = $pdo->prepare($sql);
  $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
  $stmt -> bindParam(':comment', $data, PDO::PARAM_STR);
  $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
}
//消して書き込む

function passcheck($pdo,$num){
  $sql = 'SELECT * FROM mission5';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchALL();
  foreach ($results as $row){
      if($row['id']==$num){
        $pass=$row['pass'];
        return $pass;
      }
    }
}

  function numchange($pdo,$num){
    $sql = 'update  mission5 set id=:id where id > $num';
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(':id', $id-1, PDO::PARAM_INT);
    $stmt->execute();
  }

?>
<body>
  <form action="mission5-1.php" method="post" >
    <label>氏名</label>
    <input type="hidden" name="editnum2" value="<?php if(isset($changetext[0])){echo $changetext[0];}?>"><b>
    <input type="text" name="name" value="<?php if(isset($changetext[1])){echo $changetext[1];}?>"><br>
    <label>コメント</label>
    <input type="text" name="data" value="<?php if(isset($changetext[2])){echo $changetext[2];}?>"><br>
    <label>パスワード</label>
    <input type="text" name="wripass" ><br>
    <input type="submit" value="送信"/><br>
    <label>削除</label>
    <input type="text" name="delete"/><br>
    <label>パスワード</label>
    <input type="text" name="delpass" ><br>
    <input type="submit" value="削除"/><br>
    <label>編集番号</label>
    <input type="text" name="number"/><br>
    <label>パスワード</label>
    <input type="text" name="edpass" ><br>
    <input type="submit" value="送信"/><br>
  </form>
</body>
</html>
