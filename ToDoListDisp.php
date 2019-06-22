<?php

try {

    // データベースに接続
    $dsn = 'mysql:dbname=todo;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {

    // リダイレクト先のURLへ転送する
    $url = 'error.html';
    header('Location: ' . $url, true, 301);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>ToDoList</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>ToDoリスト</h1>
        </div>
        <div id="content">

            <form method="post" action="change.php">
                <table>
                    <tr>
                        <th>日付</td>
                        <th>タスクを入力してください</td>
                    </tr>
                    <tr>
                        <td>
                            <input name="date" type="text" value="<?php echo date("Y/m/d"); ?>" style="width:100px">
                        </td>
                        <td><input name="task" type="text" style="width:300px" /></td>
                        <td><button name="action" type="submit" value="add">追加</button></td>
                    </tr>
                </table>
            </form>


            <?php

// データベースに接続
$dsn = 'mysql:dbname=todo;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// SQL文で命令を出す
$sql = 'SELECT * FROM task WHERE 1';
$stmt = $dbh->prepare($sql);
$stmt->execute();

$dbh = null;

print '<form method="post" action="change.php">';
print '<table>';
print '<tr>';
print '<th>No.</th>';
print '<th>日付</th>';
print '<th>タスク</th>';
print '<th>完了</th>';
print '<th>削除</th>';
print '</tr>';

$index = 0;

$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($list as $task) {
    print '<tr>';
    print '<td>' . $task['id'] . '</td>';
    print '<td>' . $task['create_date'] . '</td>';

    if ($task['done'] == 0) {
        print '<td>' . $task['item'] . '</td>';
        print '<td>' . '<input type="checkbox" name="done[' . "$index" . '] " value="0">' . '</td>';
    } else {
        print '<td><s>' . $task['item'] . '</s></td>';
        print '<td>' . '<input type="checkbox" name="done[' . "$index" . ']" value="1" checked="checked">' . '</td>';
    }

    if ($task['deleted'] == 0) {
        print '<td>' . '<input type="checkbox" name="deleted[' . "$index" . ']" value="0">' . '</td>';
    } else {
        print '<td>' . '<input type="checkbox" name="deleted[' . "$index" . ']" value="1" checked="checked">' . '</td>';
    }
    print '<input type="hidden" name="id[' . "$index" . ']" value="' . $task['id'] . '">';
    print '</tr>';
    $index++;
}
print '</table>';
print '<button name="action" type="submit" value="change">実行</button>';
print '</form>';

?>
</body>

</html>