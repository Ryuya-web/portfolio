<?php
session_start();
require('../dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();


    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();

}else{
    header("Location:login.php");
    exit();
}

if(!empty($_POST)) {
  if($_POST['message'] !== '') {
    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, created=NOW()');
    $message->execute(array(
        $member['id'],
        $_POST['message'],
    ));

    header('Location: index.php');
    exit();
  }
}
$posts = $db->prepare('SELECT m.name, m.image, p.*FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');
$posts->execute();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>掲示板</title>

	<link rel="stylesheet" type="text/css" href="ss.css" />
</head>

<body>
<div class="wrapper">
  <div class="header">
    <h1>みんなの掲示板</h1>
  </div>
    <div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <div class="text">
    <form action="" method="post">
      <dl>
        <dt><?php print(htmlspecialchars($member['name'], ENT_QUOTES));?>さん、一言どうぞ</dt>
        <dd>    
          <textarea name="message" cols="50" rows="5"><?php print(htmlspecialchars($message, ENT_QUOTES));?></textarea>
          <input type="hidden" name="reply_id" value="" />
        </dd>
      </dl>
        <p>
          <input type="submit" value="投稿する" />
        </p>
  </form>
</div>
<div class="new">
  <?php foreach ($posts as $post):?>
    <img src="../member_picture/<?php print(htmlspecialchars($post['image'], ENT_QUOTES));?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES));?>" />
  
    id:<?php print(htmlspecialchars($post['id'],ENT_QUOTES)); ?>
  （<?php print(htmlspecialchars($post['name'], ENT_QUOTES));?>)<span>
    <div class="message"><p><?php print(htmlspecialchars($post['message'], ENT_QUOTES));?></div><?php print(htmlspecialchars($post['created'], ENT_QUOTES));?></a>
    

    <?php if($_SESSION['id'] == $post['member_id']): ?>

    [<a href="delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>"
    style="color: #F33;">削除</a>]
  <?php endif?>
  <p> ---------------------------------------------------------------------------</p>
    </p>
    <?php endforeach; ?>
</div>

</div>
</body>
</html>
