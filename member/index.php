<?php
session_start();
require('../dbconnect.php');


if(!empty($_POST)){
    if($_POST['name'] === ''){
        $error['name'] = 'blank';
    }
    if(strlen($_POST['password']) < 4){
        $error['password'] = 'length';
    }
    if($_POST['password'] === ''){
        $error['password'] = 'blank';
    }

    $fileName = $_FILES['image']['name'];
    if(!empty($fileName)) {
        $ext = substr($fileName, -3);
        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
            $error['image'] = 'type';
        }
    }



    if(empty($error)){
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM  members WHERE name=?');
        $member->execute(array($_POST['name']));
        $record = $member->fetch();
        if ($record{'cnt'}>0) {
            $error['name'] = "equal";
        }
    }
    if(empty($error)){
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location:check.php');
        exit();
    }
}
if($_REQUEST['action'] === 'rewrite' && isset($_SESSION['join'])){
    $_POST = $_SESSION['join'];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style1.css">
    <title>会員登録</title>
</head>
<body>
    <div id="wrapper">
    <div class="header">
    <h1>会員登録</h1>
    </div>
    <div class="main">
    <form method="post" action=""  enctype = "multipart/form-data">
    ニックネームを入力してください<br>
    <input type="text" name="name" style="width:200px" value="<?php print(htmlspecialchars($_POST['name'],ENT_QUOTES));?>" /><br>
    <?php if ($error['name'] === 'blank'):?>
    <p class="error">＊ニックネームを入力してください</p>
    <?php endif;?>  
    <?php if ($error['name'] === 'equal'):?>
    <p class="error">指定された名前は既に登録されています</p>
    <?php endif;?>  
    パスワードを入力してください<br>
    <input type="password" name="password" style="width:200px" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES));?>"><br/>
    <?php if ($error['password'] === 'blank'):?>
    <p class="error">＊パスワードを入力してください</p>
    <?php endif;?>
    <?php if ($error['password'] === 'length'):?>
    <p class="error">＊パスワード4文字以上でを入力してください</p>
    <?php endif;?>
    画像を選んでください。
    <br>
    <input type="file" name="image" style = "width:400px"><br>
    <?php if ($error['image'] === 'type'):?>
    <p class="error">*写真などは「.gif」,「.jpg」,「.png」の画像を指定してください</p>
    <?php endif;?>
    <?php if (!empty($error)): ?>
    <p class="error">*恐れ入りますが、改めて指定してください</p>
    <?php endif;?>
    <br>
    <input type="button" onclick="history.back()" value="戻る">
    <input type="submit" value="OK">
    </form>
    </div>
    </div>
</body>
</html>