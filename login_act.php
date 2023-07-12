<?php

//最初にSESSIONを開始！！ココ大事！！

session_start();

//POST値
$lid = $_POST['lid'];
$lpw = $_POST['lpw'];

//1.  DB接続します
try {
    //Password:MAMP='root',XAMPP=''
    $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost', 'root', '');
} catch (PDOException $e) {
    exit('DBConnectError'.$e->getMessage());
}

//2. データ登録SQL作成
// gs_user_tableに、IDとWPがあるか確認する。
$stmt = $pdo->prepare('SELECT * FROM gs_user_table WHERE lid = :lid AND lpw=:lpw');
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR); //* Hash化する場合はコメントする
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if($status === false){
    sql_error($stmt);
}

//4. 抽出データ数を取得
$val = $stmt->fetch();

//if(password_verify($lpw, $val['lpw'])){ //* PasswordがHash化の場合はこっちのIFを使う

    if( $val['id'] != ''){
        //Login成功時 該当レコードがあればSESSIONに値を代入
    $_SESSION['chk_ssid']=session_id();
    $_SESSION['kanri_flg']=$val['kanri_flg'];
    
        header('Location: menu2.php');
    }else{
        //Login失敗時(Logout経由)
        header('Location: login.php');
    }
    
    exit();