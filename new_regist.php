<?php
$pdo = new PDO("mysql:dbname='データベース名';host=localhost;charset=utf8",'ユーザー名','パスワード');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
//ユーザー登録の受け取り,tableへ書き込み

if(isset($_POST['regist_name1'])&&isset($_POST['regist_pass1'])&&isset($_POST['regist_mail1'])){
//メール送信
	mb_language("ja");
	mb_internal_encoding("UTF-8");
	$to=$_POST['regist_mail1'];
	$uniqid=$_POST['regist_id1'];
	$name = $_POST['regist_name1'];
	$pass = $_POST['regist_pass1'];
	$url = "http://co-608.it.99sv-coco.com/registration_form.php?uniqid=$uniqid";
//
$body = <<<EOM
{$name}様
こちらのURLから本登録をしてください。
{$url}
EOM;
//
	$ad = mb_convert_encoding($to,"JIS","SJIS");
	$ke = mb_convert_encoding("掲示板本登録のご案内","ISO-2022-JP-MS");
	$ho = mb_convert_encoding($body,"ISO-2022-JP-MS");
	if(mb_send_mail($ad,$ke,$ho)){
		echo "メールを送信致しました。".'<br>';
		echo "***まだ本登録は完了しておりません。メール認証を行ってください***。".'<br>';

		$sql = "INSERT INTO usertable2(uniqid,name,pass,mailadr,date) VALUES(:uniqid,:name,:pass,:mailadr,now())";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':uniqid',$uniqid,PDO::PARAM_STR);
		$stmt->bindParam(':name',$name,PDO::PARAM_STR);
		$stmt->bindParam(':pass',$pass,PDO::PARAM_STR);
		$stmt->bindParam(':mailadr',$to,PDO::PARAM_STR);
		
		$stmt->execute();
	}else{
		echo "メール送信に失敗しました。もう一度やり直してください。".'<br>';
	}



	
}

?>
<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
<title>新規ユーザー登録</title>
</head>
<body>

<form method = "post" action="mission_3-6-1.php">
	<fieldset>
		<legend>新規ユーザー登録フォーム</legend>
		<p>名前:<input type="text" name="regist_name" value="" size="17"></p>
		<p>パスワード:<input type="text" name="regist_pass" value="" size="15"></p>
		<p>メールアドレス:<input type = "text" name = "regist_mail"></p>
		<input type="submit" value="新規ユーザー登録">
	</fieldset>
</form>
<form action="mission_3-7.php">
	<fieldset>
		<legend>ログインフォーム<legend>
	<input type="submit" value="ログインはこちら">
	</fieldset>
</form>
</body>
</html>
