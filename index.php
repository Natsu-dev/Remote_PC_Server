
<?php
    date_default_timezone_set('Asia/Tokyo'); //タイムゾーンを東京に設定
?>

<?php if(isset($_GET['change'])) { // URLにupdateパラメータがある → スイッチ更新処理

    $nowtime = date("Y-m-d H:i:s"); //実行される時刻を記録 (string型?)

    $change = $_GET['change']; //updateの値を取得
    $pdo = new PDO('mysql:host=localhost;dbname=remotepc;charset=utf8','default','test'); //Update用のPDOを宣言
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //例外用

    try {

        $query = $pdo->prepare('UPDATE current SET isRunning = :change'); //$changeの値をisRunningにセットする準備
        $query->bindValue(':change', (int)$change, PDO::PARAM_INT); //$changeの値をisRunningの?部分にバインド
        $query->execute(); //実行
        echo '<p>update done</p>';

        $query2 = $pdo->prepare('INSERT INTO change_log (id, change, changed_date, changed_ip) VALUES (:id, :change, :changed_date, :changed_ip)');

        $query2->bindValue(':id', 1, PDO::PARAM_INT);
        $query2->bindValue(':change', (int)$change, PDO::PARAM_INT);
        $query2->bindValue(':changed_date', $nowtime);
        $query2->bindValue(':changed_ip', $_SERVER["REMOTE_ADDR"]);
        $query2->execute(); //実行
        echo '<p>record done</p>';

    } catch(PDOException $ex) {

        exit('<p>データベース反映失敗</p>'.$ex->getMessage());

    }

}
?>

    //$pdo = new PDO('mysql:host=localhost;dbname=remotepc;charset=utf8','default','test');
    //$query = $pdo->prepare('SELECT isRunning FROM current');
    //$query->execute();
    //$a = $query->fetchAll();
    //print_r($a[0]['isRunning']);

    $pdo = new PDO('mysql:host=localhost;dbname=remotepc;charset=utf8','default','test');
    $query = $pdo->prepare('UPDATE current SET isRunning = 1');
    $query->execute();
    //$a = $query->fetchAll();
    //print_r($a[0]['isRunning']);