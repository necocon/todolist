<?php
// データベースに接続する
function connect_db()
{
    $dsn = 'mysql:dbname=todo;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->query('SET NAMES utf8mb4');

    return $dbh;
}

// タスク一覧を取得する
function get_task_list($dbh)
{
    $sql = 'SELECT * FROM task WHERE 1';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $list;
}

// データベースにタスクを登録する
function register_task($dbh, $date, $task)
{
    $insert_sql = 'INSERT INTO task (create_date,item) VALUES(?,?)';
    $insert_stmt = $dbh->prepare($insert_sql);
    $insert_stmt->bindValue(1, $date);
    $insert_stmt->bindValue(2, $task);
    $insert_stmt->execute();
}

// タスクの更新
function update_task($dbh, $id, $done)
{
    $update_sql = "UPDATE task SET done=? WHERE id=?";
    $update_stmt = $dbh->prepare($update_sql);
    $update_stmt->bindValue(1, $done);
    $update_stmt->bindValue(2, $id);
    $update_stmt->execute();

}

// タスク削除
function delete_task($dbh, $id)
{
    $delete_sql = "DELETE FROM task WHERE id=?";
    $delete_stmt = $dbh->prepare($delete_sql);
    $delete_stmt->bindValue(1, $id);
    $delete_stmt->execute();

}

// エラーページにリダイレクトする
function redirect_to_error_page()
{
    $url = 'error.html';
    header('Location: ' . $url, true, 301);
    exit;
}

// メインページにリダイレクトする
function redirect_to_main_page()
{
    $url = 'todolist.php';
    header('Location: ' . $url, true, 301);
    exit;
}
