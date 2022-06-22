<!doctype html>
<html>
<head>
<meta charset="utf-8">
<titleチャットの超基本形</title>
</head>
<body>
<?php
  if(isset($_POST['fHandle'])) $sHandle=$_POST['fHandle'];  else $sHandle=""; //ハンドルネームを受け取る。
  if(isset($_POST['fMsg'])) $sMsg=$_POST['fMsg']; else $sMsg=""; //メッセージを受け取る。
  if(isset($_POST['fIn'])) $sIn=$_POST['fIn']; else $sIn=""; //最初のログイン時のパラメータ「logIn」を受け取る。
  if(isset($_POST['fSub2'])) $sSub2=$_POST['fSub2']; else $sSub2=""; //退室ボタンの情報を受け取る。
?>
  <div>チャットの超基本形
  <form name="form1" method="post" action="chat.php">
  メッセージ：<input name="fMsg" type="text" size="100">
  <input name="fHandle" type="hidden" value=<?= $sHandle ?>>　<!-- 発言のたびにハンドルネームを送信しています。 --> 
  <input type="submit" name="fSub1" value="発言">
  <input type="submit" name="fSub2" value="退室">
  </form>
  </div>
  <div style="color:blue;">メッセージ：</div>
  <hr>
<?php
  $conn=mysqli_connect('localhost','******','******') or die("データベース接続に失敗しました。");
  mysqli_select_db($conn,'chat_db') or die("指定されたデータベースは存在しません。");

  //入室時のメッセージを設定しています。
  if($sIn=="logIn"){
    $sMsg="{$sHandle}さんが入室されました。";
  }

  //退室時のメッセージを設定しています。
  if($sSub2=="退室"){ 
    $sMsg="{$sHandle}さんが退室されました。";
  }

  //チャットデータの書き込み
  $sql="insert into chat_tbl values(null,'{$sHandle}','{$sMsg}',null);";
  if(!mysqli_query($conn,$sql)){
    echo "チャットデータの書き込みに失敗しました。<br>\n";
    exit();
  }
		
  //メッセージの表示。
  $sql="select * from chat_tbl order by dateTime desc;"; //データベースのレコードを後ろから前に向かって読み込んでいます。
  if($result=mysqli_query($conn,$sql)){
    while($row=mysqli_fetch_array($result,MYSQL_ASSOC)){
      echo "<span style=\"color:pink;\">{$row['handle']}</span><span style=\"color:red;\">＞</span>\n
            <span style=\"color:black;\">{$row['message']}</span><span style=\"color:red;\">：</span>\n
            <span style=\"color:yellow;\">{$row['dateTime']}</span>\n
            <hr>\n";     
    }
  }
  else{
    echo "チャットデータの抽出に失敗しました<br>\n";
  }
  mysqli_close($conn);
?>
</body>
</html>