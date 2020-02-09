<?php
session_start();
require('../dbconnect.php');
if(!isset($_SESSION['join'])){
    header('Location:index.php');
    exit();
}


if(!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET name=?, password=?, image=?, created=NOW()');
    $statement->execute(array(
        $_SESSION['join']['name'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ));
    unset($_SESSION['join']);

    header('Location:thanks.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="ssss.css">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
	<input type="hidden" name="action" value="submit" />
    <div class="header">
    <h1>会員登録確認画面</h1>
    </div>
    <div class="main">
    <p>ニックネーム</p>
    <?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
    <p>パスワード</p>
    <p>パスワードは表示できません</p>

    <p>画像</p>
    <?php if($_SESSION['join']['image'] !== ''): ?>
	<img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>">
	<?php endif; ?>
    </div>
    
    <div><a href="index.php?action=rewrite" class="button">&laquo;&nbsp;書き直す</a> <input type="submit"  class="button2" value="登録する" /></div>

</form>
    
</body>
</html>