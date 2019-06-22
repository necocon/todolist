<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>データーベース変更テスト</title>
</head>
<body>

<?php

try {
// データベースに接続
    $dsn = 'mysql:dbname=todo;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->query('SET NAMES utf8mb4');

// print $_POST['id'][0];
    // print $_POST['done'][0];
    // print $_POST['deleted'][0];

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

            // print '<br>';
            // print "id: $id" . '<br>';
            // print "done: $done" . '<br>';
            // print "deleted: $deleted" . '<br>';

            $update_stmt->bindValue(1, $done);
            $update_stmt->bindValue(2, $id);
            $update_stmt->execute();
        }
        $index++;
    }

    $dbh = null;

// リダイレクト先のURLへ転送する
    $url = 'http://localhost:8888/todolist/todolist.php';
    header('Location: ' . $url, true, 301);

// すべての出力を終了
    exit;

} catch (Exception $ex) {
    print 'エラーが発生しました';
}

?>

</body>
</html>
