<?php
// タスクの登録をする、タスクの一覧をHTMLで表示する

// 外部関数の読み込み
require_once 'functions.php';

try {
    $dbh = connect_db();
    $list = get_task_list($dbh);
    $dbh = null;

} catch (Exception $e) {
    //redirect_to_error_page();
    var_dump($e); //デバッグ情報
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
                            <!-- <input name="date" type="text" value="<?php echo date("Y/m/d"); ?>" style="width:100px"> -->
                            <input name="date" type="date" value="<?=date('Y-m-d');?>">
                        </td>
                        <td><input name="task" type="text" style="width:300px" /></td>
                        <td><button name="action" type="submit" value="add">追加</button></td>
                    </tr>
                </table>
            </form>

            <form method="post" action="change.php">
                <table>
                    <tr>
                        <th>No.</th>
                        <th>日付</th>
                        <th>タスク</th>
                        <th>完了</th>
                        <th>削除</th>
                    </tr>

                    <?php $index = 0;?>
                    <?php //タスク一覧を表示 ?>
                    <?php foreach ($list as $task): ?>

                    <tr>
                        <?php if ($task['done'] == 0): ?>
                            <td><?=$task['id']?></td>
                            <td><?=$task['create_date']?></td>
                            <td><?=$task['item']?></td>
                            <td><input type="checkbox" name="done[<?=$index?>]" value="0"></td>
                        <?php else: ?>
                            <td><s><?=$task['id']?></s></td>
                            <td><s><?=$task['create_date']?></s></td>
                            <td><s><?=$task['item']?></s></td>
                            <td><input type="checkbox" name="done[<?=$index?>]" value="1" checked="checked"></td>
                        <?php endif;?>

                        <td><input type="checkbox" name="deleted[<?=$index?>]" value="0"></td>
                        <input type="hidden" name="id[<?=$index?>]" value="<?=$task['id']?>">
                    </tr>
                    <?php $index++;?>
                    <?php endforeach;?>

                </table>
                <button name="action" type="submit" value="change">実行</button>
            </form>
</body>

</html>