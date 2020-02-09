<?php
session_start();
require('../dbconnect.php');

if(!empty($_POST)) {
  if($_POST['name'] !== '' && $_POST['password'] !==''){
    $login = $db->prepare('SELECT * FROM members WHERE name=? AND password=?');
    $login->execute(array(
      $_POST['name'],
      sha1($_POST['password'])
    ));
    $member = $login->fetch();

    if ($member) {
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();
      header('Location: index.php');
      exit();
    }else{
      $error['login'] = 'failed';
    }
  }else{
      $error['login'] = 'blank';

  }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="sss.css" />
<title>ログインする</title>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ログインする</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>ニックネームとパスワードを記入してログインしてください。</p>
      <p>入会手続きがまだの方はこちらからどうぞ。</p>
      <p>&raquo;<a href="../member/">入会手続きをする</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>ニックネーム</dt>
        <dd>
          <input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" />
          <?php if ($error['login'] === 'blank'): ?>
            <p class="error">ニックネームとパスワードをご記入ください</p>
          <?php endif ?>
          <?php if ($error['login'] === 'failed'): ?>
            <p class="error">*ログインに失敗しました。正しくご記入ください</p>
          <?php endif ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
        </dd>
      <div>
        <input type="submit" value="ログインする" />
      </div>
    </form>
  </div>
  <div id="foot">
  </div>
</div>
</body>
</html>
