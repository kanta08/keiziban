<?php
session_start();
//データベースへの接続
$pdo = new PDO("mysql:dbname='データベース名';host=localhost;charset=utf8",'ユーザー名','パスワード');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
//ユーザログイン確認
if(!isset($_SESSION['id'])||!isset($_SESSION['pass'])){
	header('Location: mission_3-7.php');
	exit();
}else{
	echo "ユーザー情報".'<br>';
	echo "name:".$_SESSION['name'].'<br>';
	echo "id:".$_SESSION['id'].'<br>';
	echo '<hr style="border:0;border-top:1px solid #ccc;">';
}

//フォームからkeiziban tableへ書き込み
if(isset($_POST['name'])||isset($_POST['comment'])){
	//画像をフォームからtableへ保存

	if(isset($_FILES['upload']['tmp_name'])){
		//バイナリデータを扱う。拡張子mimeも受け取る
		$tempfile = $_FILES['upload']['tmp_name'];
		$upfilename = $_FILES['upload']['name'];
		$upfiledata = file_get_contents($tempfile);
//echo $upfiledata;
		$ext = substr($upfilename,strrpos($upfilename,'.')+1);
//echo $ext;

	}
	$sql = "INSERT INTO keiziban4 (name,comment,day,time,pass,img,mimeimg) VALUES(:name,:comment,:day,:time,:pass,:img,:mimeimg)";
	$stmt = $pdo->prepare($sql);
//代入
	$stmt->bindParam(':name',$_POST['name'],PDO::PARAM_STR);
	$stmt->bindParam(':comment',$_POST['comment'],PDO::PARAM_STR);
	$date=date("Y/m/d");
	$stmt->bindParam(':day',$date,PDO::PARAM_STR);
	$time=date("H:i:s");
	$stmt->bindParam(':time',$time,PDO::PARAM_STR);
	$stmt->bindParam(':pass',$_POST['new_pass'],PDO::PARAM_STR);
//画像部分

	$stmt->bindParam(':img',$upfiledata,PDO::PARAM_LOB);
	$stmt->bindParam(':mimeimg',$ext,PDO::PARAM_STR);
	
//実行
	$stmt->execute();

}
//削除
if(isset($_POST['number_delete'])){
	
//パスワード確認
	$sql = 'SELECT pass FROM keiziban4 where id = :id';
	$stmt = $pdo->prepare($sql);
	$id = $_POST['number_delete'];
	$stmt->bindParam(':id',$id,PDO::PARAM_INT);
	$stmt->execute();
//結果を配列へ
	$result = $stmt->fetch();

//実際に削除
	if($result['pass']==$_POST['delete_pass']){
		$sql = "delete from keiziban4 where id = :id";
		$stmt = $pdo->prepare($sql);
		$id = $_POST['number_delete'];
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
	}else{
		echo "パスワードが間違っています。";
	}
}
//編集申請受け取り準備
if(isset($_POST['number_edit'])){

	$sql = 'SELECT*FROM keiziban4 where id = :id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id',$_POST['number_edit'],PDO::PARAM_INT);
	$stmt->execute();
//結果を配列へ
	$result = $stmt->fetch();

	$temp_number=$result['id'];
	$temp_name=$result['name'];
	$temp_comment=$result['comment'];
	$temp_pass=$result['pass'];
}
//編集
if(isset($_POST['edit'])){

	$sql = "UPDATE keiziban4 SET name=:name,comment=:comment,day=:day,time=:time,pass=:pass where id = :id";
	$stmt = $pdo -> prepare($sql);

	$stmt->bindParam(':id',$_POST['edit'],PDO::PARAM_INT);
	$stmt->bindParam(':name',$_POST['editname'],PDO::PARAM_STR);
	$stmt->bindParam(':comment',$_POST['editcomment'],PDO::PARAM_STR);
	$date=date("Y/m/d");
	$stmt->bindParam(':day',$date,PDO::PARAM_STR);
	$time=date("H:i:s");
	$stmt->bindParam(':time',$time,PDO::PARAM_STR);
	$stmt->bindparam(':pass',$_POST['editnew_password'],PDO::PARAM_STR);
//実行
	$stmt->execute();
}

?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
<title>釣り掲示板</title>
<head>
<body>
<h1 style="padding: .25em 0 .5em .75em;border-left: 6px solid #ccc;border-bottom:1px solid #ccc;">釣り掲示板</h1>
<p>ここは釣果自慢や釣りのテクニックなど、釣りに関することを自由に投稿できる場です。</p>
<?php if(isset($_POST['switch'])&&$_POST['edit_pass']==$temp_pass):?>
<h2>～編集画面～</h2>
	<form method="post" action = "mission_3-10.php"> 
	<p>名前：<input type="text" name="editname" value="<?php echo $temp_name; ?>" size="17"></p>
	<p>釣った魚や釣った場所、日付、使った仕掛け等自由にコメントしてください。</p>
	<p><textarea name = "editcomment" rows="5" cols="50"><?php echo $temp_comment; ?></textarea></p>
	<p>パスワード登録:<input type="text" name="editnew_password" value="" size="15"></p>
		<input type="hidden" name="edit" value="<?php echo $temp_number; ?>">
		<input type="submit" value="編集送信">
	</form>
<?php else : ?>
<h2>投稿申請</h2>
	<form method="post" action = "mission_3-10.php" enctype="multipart/form-data">
	<p>名前:<input type="text" name="name" value="<?php echo $_SESSION['name'];?>" size="17">
		パスワード入力:<input type="text" name = "new_pass" value="<?php echo $_SESSION['pass'];?>" size="15"></p>
	<p>釣った魚や釣った場所、日付、使った仕掛け等自由にコメントしてください。</p>
	<p><textarea name = "comment" rows="5" cols="50"></textarea></p>
	<p>画像:<input type="file" name="upload"></p>
	<input type="submit" value="投稿送信">
	</form>
<hr style="border:0;border-top:1px solid #ccc;">
<h2>削除申請</h2>
	<form method="post" action = "mission_3-10.php">
	<p>削除対象番号<input type="text" name="number_delete" value="" size="5"></p>
	<p>パスワード入力:<input type="text" name="delete_pass" value="" size="15"></p>
	<input type="submit" value="削除申請送信">
	</form>
<hr style="border:0;border-top:1px solid #ccc;">
<h2>編集申請</h2>
	<form method="post" action = "mission_3-10.php">
	<p>編集対象番号:<input type="text" name = "number_edit" value="" size="5"></p>
	<p>パスワード入力:<input type="text" name = "edit_pass" vlue="" size="15"></p>
	<input type="hidden" name = "switch" value="1">
	<?php if($_POST['edit_pass']!=$temp_pass):?>
		パスワードが間違っています。
	<?php endif; ?>
		<input type = "submit" value="編集申請送信">
	</form>
<hr style="border:0;border-top:1px solid #ccc;">
<?php endif; ?>

<?php


//掲示板投稿表示

$sql = 'SELECT*FROM keiziban4 ORDER BY id';

$stmt = $pdo->query($sql);//実行・結果取得
$result = $stmt->fetchall(PDO::FETCH_ASSOC);
//出力
foreach($result as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['day'].',';
	echo $row['time'].'<br>';
	echo $row['comment'].'<br>';


$id = $row['id'];
//画像表示
	if($row['mimeimg'] == "mp4"){
	echo("<video src =\"create_image.php?id=$id\" height=\"200\" width=\"400\" controls></video>");
	echo '<br>';
	}
	elseif($row['mimeimg'] == "jpg"||$row['mimeimg'] == "jpeg"||$row['mimeimg']=="gif"||$row['mimeimg']=="png"){
		echo("<img src=\"create_image.php?id=$id\" height=\"100\" width=\"200\">");
		echo '<br>';
	}

}
?>

</body>
</html>