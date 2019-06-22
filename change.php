<?php

try {
    // データベースに接続
    $dsn = 'mysql:dbname=todo;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->query('SET NAMES utf8mb4');

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // データベースにタスクを登録する
                if (isset($_POST['date']) and isset($_POST['task'])) {
                    $date = $_POST['date'];
                    $task = $_POST['task'];

                    // SQL文で命令を出す
                    $insert_sql = 'INSERT INTO task (create_date,item) VALUES(?,?)';
                    $insert_stmt = $dbh->prepare($insert_sql);
                    $insert_stmt->bindValue(1, $date);
                    $insert_stmt->bindValue(2, $task);
                    $insert_stmt->execute();

                    // データーベースから切断
                    $dbh = null;
                }
                break;

            case 'change':
                // データベースの更新、削除
                $update_sql = "UPDATE task SET done=? WHERE id=?";
                $delete_sql = "DELETE FROM task WHERE id=?";
                $update_stmt = $dbh->prepare($update_sql);
                $delete_stmt = $dbh->prepare($delete_sql);

                $index = 0;
                while (isset($_POST['id'][$index])) {
                    $id = $_POST['id'][$index];
                    $deleted = isset($_POST['deleted'][$index]);

                    if ($deleted == true) {
                        $delete_stmt->bindValue(1, $id);
                        $delete_stmt->execute();
                    } else {
                        if (isset($_POST['done'][$index])) {
                            $done = 1;
                        } else {
                            $done = 0;
                        }

                        $update_stmt->bindValue(1, $done);
                        $update_stmt->bindValue(2, $id);
                        $update_stmt->execute();
                    }
                    $index++;
                }
                $dbh = null;
                break;
        }

    }

    // リダイレクト先のURLへ転送する
    $url = 'todolistdisp.php';
    header('Location: ' . $url, true, 301);
    exit;
} catch (Exception $ex) {

    // リダイレクト先のURLへ転送する
    $url = 'error.html';
    header('Location: ' . $url, true, 301);
    exit;
}
