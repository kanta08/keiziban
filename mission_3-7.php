<?php
session_start();

//例外処理
if(empty($_POST['login_id'])){
	echo "ユーザーＩＤが未入力です。";
}else if(empty($_POST['login_pass'])){
	echo "パスワードが未入力です。";
}
//ログイン入力されたとき
if(isset($_POST['login_id'])&&isset($_POST['login_pass'])){
	$pdo = new PDO("mysql:dbname='データベース名';host=localhost;charset=utf8",'ユーザー名','パスワード');
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

	$sql = 'SELECT * from usertable2 where uniqid = :id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id',$_POST['login_id']);
	$stmt->execute();

	$result = $stmt->fetch();
	if($result['flag']==1){
		if($result['pass']==$_POST['login_pass']){
			$_SESSION['id'] = $result['uniqid'];
			$_SESSION['name'] = $result['name'];
			$_SESSION['pass'] = $result['pass'];
			header('Location: mission_3-6.php');
			exit();
		}
	}
	else{
		echo "ユーザーIDまたはパスワードに誤りがあります。";
	}
	
}

?>
<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
<title>ログイン画面</title>
</head>
<body>

<form method = "post" action="">
	<fieldset>
		<legend>ログインフォーム</legend>
		<p>ユーザーID:<input type="text" name="login_id" value="<?php echo $_SESSION['id'];?>" size="20"></p>
		<p>パスワード:<input type="text" name="login_pass" value="<?php echo $_SESSION[pass];?>" size="20"></p>
		<input type="submit" value="ログイン">
	</fieldset>
</form>
<form action="new_regist.php">
	<fieldset>
		<legend>新規登録フォーム<legend>
	<input type="submit" value="新規登録はこちら">
	</fieldset>
</form>
</body>
</html>
