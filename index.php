<?php
/**
 * Created by PhpStorm.
 * User: Big_Energy
 * Date: 26.09.2018
 * Time: 18:02
 * Главный индекс
 */
// получаем json массив через api
$url = 'http://gpool.io/engine/api/list.php';
$response = json_decode(file_get_contents($url), true);

?>

<? include 'engine/inc/header.php'; ?>

<!-- Begin page content -->
<main role="main" class="container">
    <h1 class="mt-5">Списки организаций</h1>
    <p class="lead">Просмотр списка организаций</p>
        <?php
        if(!empty($response)) {
            ?>
            <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Наименование</th>
                <th scope="col">Тип</th>
                <th scope="col">Действие</th>
            </tr>
            </thead>
            <tbody>
            <?
            foreach($response as $val) {
            ?>
             <tr>
                 <th scope="row"><?=$val['id'];?></th>
                 <td><?=$val['name'];?></td>
                 <td><? if($val['type'] == 'IP') { echo 'Индивидуальный предприниматель'; } else { echo 'Юридическое лицо'; }?></td>
                 <td><a href="edit.php?id=<?=$val['id'];?>" class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Редактировать</a></td>
             </tr>
            <?php } ?>
            </tbody>
            </table>
             <?
            } else {
                echo '<div class="alert alert-warning" role="alert">Произошла ошибка получения списка организаций, возможно их не существует или отстуствует подключение к базе данных!</div>';
            }
            ?>
</main>
<br>
<? include 'engine/inc/footer.php'; ?>