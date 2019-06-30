<?php
// データベースへの接続
// タスク登録、タスク更新、タスク削除
// メインページへのリダイレクト、エラーページへのリダイレクト処理をする

require_once 'functions.php';

try {

    // データベースに接続する
    $dbh = connect_db();

    if (isset($_POST['action'])) {

        switch ($_POST['action']) {
            case 'add':
                if (isset($_POST['date']) and isset($_POST['task'])) {

                    $date = $_POST['date'];
                    $task = $_POST['task'];

                    // データベースにタスクを登録する
                    register_task($dbh, $date, $task);

                    // データベースから切断
                    $dbh = null;
                }
                break;

            case 'change':
                $index = 0;

                while (isset($_POST['id'][$index])) {
                    $id = $_POST['id'][$index];
                    $is_deleted = isset($_POST['deleted'][$index]);

                    if ($is_deleted) {

                        // タスクの削除
                        delete_task($dbh, $id);

                    } else {
                        if (isset($_POST['done'][$index])) {
                            $done = 1;
                        } else {
                            $done = 0;
                        }

                        // タスクの更新
                        update_task($dbh, $id, $done);
                    }
                    $index++;
                }

                $dbh = null;
                break;
        }

    }

    // メインページにリダイレクトする
    redirect_to_main_page();
} catch (Exception $ex) {
    // エラーページにリダイレクトする
    redirect_to_error_page();
//    var_dump($e);  // エラー確認用
    exit;
}
