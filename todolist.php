<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>ToDoList</title>
    <link rel="stylesheet" href="style-test.css">
</head>

<body>
<div id="wrap">
  <div id="head">
<h1>ToDoリスト</h1>
</div>
<div id="content">


<?php

try {

    if (isset($_POST['date']) and isset($_POST['task'])) {
        $date = $_POST['date'];
        $task = $_POST['task'];

        // データベースに接続
        $dsn = 'mysql:dbname=todo;host=localhost';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->query('SET NAMES utf8');

        $create_date = date("Y/m/d");

        // SQL文で命令を出す
        $sql = 'INSERT INTO task(create_date,item) VALUES("' . $create_date . '","' . $task . '")';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        // データーベースから切断
        $dbh = null;

    }
    ?>


        <form method="post" action="todolist.php">
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
                    <!--           <td><input type="reset" value="クリア"></td> -->
                    <td><input type="submit" value="追加" /></td>
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
    while (true) {
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rec == false) {
            break;
        }
        print '<tr>';
        print '<td>' . $rec['id'] . '</td>';
        print '<td>' . $rec['create_date'] . '</td>';
        if ($rec['done'] == 0) {
            print '<td>' . $rec['item'] . '</td>';
            print '<td>' . '<input type="checkbox" name="done[' . "$index" . '] " value="0">' . '</td>';
        } else {
            print '<td><s>' . $rec['item'] . '</s></td>';
            print '<td>' . '<input type="checkbox" name="done[' . "$index" . ']" value="1" checked="checked">' . '</td>';
        }
        if ($rec['deleted'] == 0) {
            print '<td>' . '<input type="checkbox" name="deleted[' . "$index" . ']" value="0">' . '</td>';
        } else {
            print '<td>' . '<input type="checkbox" name="deleted[' . "$index" . ']" value="1" checked="checked">' . '</td>';
        }
        print '<input type="hidden" name="id[' . "$index" . ']" value="' . $rec['id'] . '">';
        print '</tr>';
        $index++;
    }
    print '</table>';
    print '<input type="submit" name="change" value="実行">';
    print '</form>';

} catch (Exception $e) {
    print 'エラーが発生しました。';
}

?>

</body>


</html>