<?php
    date_default_timezone_set('Asia/Tokyo'); //タイムゾーンを東京に設定
    $nowtime = date('Y-m-d H:i:s'); //実行される時刻を記録 (string型?)

    require('variable.php'); //変数ファイルを読み込み
    $pdo = new PDO('mysql:host=' . $_SERVER['HTTP_HOST'] . ';dbname=' . $dbName . ';charset=utf8', $userName, $passWord); //PDOを宣言
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //例外用

    if (isset($_GET['changed'])) { //URLにchangedパラメータがある → スイッチ更新処理

        $changed = $_GET['changed']; //changedの値を取得

        try {

            $query = $pdo -> prepare('UPDATE current SET isRunning = :changed'); //changedを更新する準備
            $query -> bindValue(':changed', (int)$changed, PDO::PARAM_INT); //$changedの値をバインド
            $query -> execute(); //実行

            $query2 = $pdo -> prepare('INSERT INTO changed_log (changed, changed_date, changed_ip) VALUES (:changed, :changed_date, :changed_ip)'); //changed_log追加の準備
            $query2 -> bindValue(':changed', (int)$changed, PDO::PARAM_INT); //$changedの値をバインド
            $query2 -> bindValue(':changed_date', $nowtime); //$nowtimeの値をバインド
            $query2 -> bindValue(':changed_ip', $_SERVER["REMOTE_ADDR"]); //IPアドレスの値をバインド
            $query2 -> execute(); //実行
        
        } catch (PDOException $ex) { //PDO例外をキャッチ

            exit('<p>データベース反映失敗</p>' . $ex -> getMessage());

        }
    }

    if (isset($_GET['reader'])) { //URLにreaderパラメータがある → スイッチ確認処理

        try {

            $query = $pdo -> prepare('UPDATE raspi_refresh SET last_access = :last_access'); //$changedの値をisRunningにセットする準備
            $query -> bindValue(':last_access', $nowtime); //$changedの値をisRunningの?部分にバインド
            $query -> execute(); //実行

            $state = $pdo -> prepare('SELECT isRunning FROM current'); //isRunningの値を取得する準備
            $state -> execute(); //実行
            $isRun = $state -> fetch(PDO::FETCH_ASSOC); //得られたステートメントから値をフェッチ
            print_r($isRun['isRunning']); //フェッチした$isRunの値をエコー
        
        } catch (PDOException $ex) { //PDO例外をキャッチ

            exit('-1' . $ex -> getMessage()); //$isRunの代わりに'-1'を返す
        
        }
    }