<?php
session_start();
$pdo = new PDO("mysql:dbname=データベース名;host=localhost;charset=utf8",'ユーザー名','パスワード');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
//URLからuniqid受け取る
$id = $_GET['uniqid'];

if(isset($id)){
	$sql = 'SELECT*FROM usertable2 where uniqid = :uniqid AND flag= :flag';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':uniqid',$id,PDO::PARAM_STR);
	$flag=0;
	$stmt->bindParam(':flag',$flag,PDO::PARAM_INT);
	$stmt->execute();

//記録確認
	$row_count = $stmt->rowCount();
echo $row_count;
	if($row_count==1){
		$sql = "update usertable2 set flag = :flag where uniqid = :uniqid";
		$stmt = $pdo -> prepare($sql);
		$stmt->bindParam(':uniqid',$id,PDO::PARAM_STR);
		$flag=1;
		$stmt->bindParam(':flag',$flag,PDO::PARAM_INT);
		$stmt->execute();
		echo "登録が完了致しました。".'<br>';
		echo "ログインにこちらのIDが必要ですので忘れないように保存しておいてください。".'<br>';
		echo "ID:".$id.'<br>';
	}else{
		echo "こちらのメールアドレスは既に使用されているか、URLの有効期限が切れている可能性がございます。";
	}
}else{
	echo "予期せぬエラーが発生しました。申し訳ございませんが、もう一度登録からやり直してください。";
}
	


?>
<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
<title></title>
</head>
<body>


<form action="mission_3-7.php">
	<fieldset>
		<legend>ログインフォーム<legend>
	<input type="submit" value="ログインはこちら">
	</fieldset>
</form>
</body>
</html>
