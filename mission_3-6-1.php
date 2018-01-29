<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
<title>掲示板</title>
<head>
<body>
ユーザー登録確認画面

こちらの情報で本当に登録しますか？
よろしければ、メール送信を押していただき、下記のアドレス宛に送られてきたメール内の認証URLを押してください。

<form method="post" action = "new_regist.php"
<p>名前:<input type="text" name="regist_name1" value="<?php echo $_POST['regist_name'];?>" size="17"></p>
<p>パスワード:<input type="text" name="regist_pass1" value="<?php echo $_POST['regist_pass'];?>" size="15"></p>
<p>メールアドレス:<input type = "text" name = "regist_mail1" value="<?php echo $_POST['regist_mail'];?>"></p>
<input type = "hidden" name="regist_id1" value="<?php echo uniqid(); ?>">
<input type ="submit" value="メール送信">
<input type ="button" valule="戻る" onclick="history.back()">
</form>

</body>
</html>