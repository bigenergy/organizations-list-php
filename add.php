<?php

include 'engine/inc/header.php';

?>
<? include 'engine/inc/footer.php'; ?>
<!-- Begin page content -->
<main role="main" class="container">
    <h1 class="mt-5">Добавление новой организации</h1>
    <p class="lead">Заполните форму, некоторые поля являются обязательными</p>

    <form method="GET" role="form" id="ajax_add">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="orgname">Наименование организации</label>
                <input type="text" class="form-control" id="orgname" name="orgname" maxlength="255" placeholder="Наименование" required>
            </div>
            <div class="form-group col-md-6">
                <label for="inputState">Тип организации</label>
                <select id="inputState" name="orgtype" class="form-control" required>
                    <option value="IP" selected>Индивидуальный предприниматель</option>
                    <option value="UL">Юридическое лицо</option>
                </select>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="INN">ИНН</label>
                <input type="number" class="form-control" id="INN" minlength="12" maxlength="12" name="INN" placeholder="ИНН" required>
            </div>
            <div class="form-group col-md-6">
                <label for="KPP">КПП</label>
                <input type="number" class="form-control" id="KPP" name="KPP"  minlength="9" maxlength="9" placeholder="Только для ЮР лиц" disabled>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="number">Контактный телефон</label>
                <input type="text" class="form-control js-phone" id="number" name="number" placeholder="+7...." required>
            </div>
            <div class="form-group col-md-6">
                <label for="email">Контактный E-Mail (не обязательно)</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="example@test.ru">
            </div>
        </div>

        <button type="submit" id="btn-addnew" class="btn btn-primary">Добавить новую организацию <i class="fas fa-arrow-right"></i></button>
    </form>

</main>


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
<script src="engine/core-js/add.js"></script>

