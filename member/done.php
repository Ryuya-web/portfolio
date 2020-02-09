
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php

    try{
        require_once('../common/common.php');

        $post=sanitize($_POST);
        $name=$post['name'];
        $pass=$post['pass'];
        $gazou_name=$post['gazou_name'];
        $dsn = 'mysql:dbname=portfolio;host=localhost;charset=utf8';
        $user='root';
        $password='';
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $sql='INSERT INTO member(name,password,gazou) VALUES (?,?,?)';
        $stmt=$dbh->prepare($sql);
        $data[]=$name;
        $data[]=$pass;
        $data[]=$gazou_name;
        $stmt->execute($data);

        $dbh=null;
    }
    catch(Exception $e)
    {
        print'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
    }

    
    
    ?>

    <div class="header">
    <h1>会員登録</h1>
    </div>
    <div class="main">
        <p>ユーザー登録が完了しました</p>
    </div>
   

    <a href="pro_list.php">戻る</a>
</body>
</html>