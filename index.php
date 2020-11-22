<?php if(isset($_GET['switch'])) { $switch = $_GET['switch']; } // URL中のswitchパラメータを取得
    //$pdo = new PDO('mysql:host=localhost;dbname=remotepc;charset=utf8','default','test');
    //$qry = $pdo->prepare('SELECT isRunning FROM current');
    //$qry->execute();
    //$a = $qry->fetchAll();
    //print_r($a[0]['isRunning']);

    $pdo = new PDO('mysql:host=localhost;dbname=remotepc;charset=utf8','default','test');
    $qry = $pdo->prepare('UPDATE current SET isRunning = 1');
    $qry->execute();
    //$a = $qry->fetchAll();
    //print_r($a[0]['isRunning']);
?>