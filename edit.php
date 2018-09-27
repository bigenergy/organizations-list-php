<?php

include 'engine/inc/header.php';

ini_set('display_errors',1);
error_reporting(E_ALL);

// получаем json массив через api

$getid = $_GET['id'];

if($getid == NULL) {
    header("Location: /"); /* Перенаправление браузера */
    exit();
}

$url = 'http://gpool.io/engine/api/list.php?id=' . $getid;
$response = json_decode(file_get_contents($url), true);

foreach($response as $val) {
    $OrgName = $val['name'];
    $OrgType = $val['type'];
    $OrgINN = $val['inn'];
    $OrgKPP = $val['kpp'];
    $OrgTel = $val['phone'];
    $OrgEmail = $val['email'];
}



?>

<!-- Begin page content -->
<main role="main" class="container">
    <h1 class="mt-5">Редактирование организации "<?=$OrgName?>"</h1>
    <p class="lead">Заполните форму, некоторые поля являются обязательными</p>

    <form method="GET" role="form" id="ajax_add">
        <input name="id" type="hidden" value="<?=$getid?>">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="orgname">Наименование организации</label>
                <input type="text" class="form-control" id="orgname" name="orgname" maxlength="255" placeholder="Наименование" value="<?=$OrgName?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="inputState">Тип организации</label>
                <select id="inputState" name="orgtype" class="form-control" required>
                    <?=str_replace('"'.$OrgType.'"', '"'.$OrgType.'" selected', '<option value="IP">Индивидуальный предприниматель</option><option value="UL">Юридическое лицо</option>'); ?>
                </select>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="INN">ИНН</label>
                <input type="number" class="form-control" id="INN" minlength="12" maxlength="12" name="INN" placeholder="ИНН" value="<?=$OrgINN?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="KPP">КПП</label>
                <input type="number" class="form-control" id="KPP" name="KPP"  minlength="9" maxlength="9" placeholder="Только для ЮР лиц" value="<?=$OrgKPP?>" <? if($OrgType == 'IP') { echo 'disabled'; } ?>>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="number">Контактный телефон</label>
                <input type="text" class="form-control js-phone" id="number" name="number" placeholder="+7...." required value="<?=$OrgTel?>">
            </div>
            <div class="form-group col-md-6">
                <label for="email">Контактный E-Mail (не обязательно)</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="example@test.ru" value="<?=$OrgEmail?>">
            </div>
        </div>

        <button type="submit" id="btn-addnew" class="btn btn-primary"><i class="fas fa-save"></i> Сохранить изменения</button>

    </form>
    <form method="GET" role="form" id="ajax_delete">
        <input name="id" type="hidden" value="<?=$getid?>">
        <button type="button" id="btn-delete" class="btn btn-danger"><i class="fas fa-trash"></i> Удалить организацию</button>
    </form>
</main>
<? include 'engine/inc/footer.php'; ?>

<script type="text/javascript">
    $('#inputState').change(function() {
        if( $(this).val() == "UL") {
            $('#KPP').prop( "disabled", false );
            $('#KPP').prop( "required", true );
            document.getElementById('INN').setAttribute('minlength',10);
            document.getElementById('INN').setAttribute('maxlength',10);
        } else {
            $('#KPP').prop( "disabled", true );
            $('#KPP').prop( "required", false );
            document.getElementById('INN').setAttribute('minlength',12);
            document.getElementById('INN').setAttribute('maxlength',12);
            document.getElementById("KPP").value = "";
        }
    });
</script>

<script src="engine/core-js/edit.js"></script>

