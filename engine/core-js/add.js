$(document).ready(function(){
    jQuery.validator.addMethod("checkMask", function(value, element) {
        return /\+\d{1}\(\d{3}\)\d{3}-\d{4}/g.test(value);
    });

    $("#ajax_add").validate({
        rules:{

            orgname:{
                required: true,
                maxlength: 255,
            },
            number:{
                required: true,
                checkMask: true,
            },
        },

        messages:{

            orgname:{
                required: "Это поле обязательно для заполнения",
                maxlength: "Максимальное число символов - 255",
            },

            email:{
                email: "Введите E-Mail в верном формате",
            },

            INN:{
                required: "Это поле обязательно для заполнения",
                maxlength: jQuery.validator.format("Не более {0} символов"),
                minlength: jQuery.validator.format("Не менее {0} символов"),
            },

            KPP:{
                required: "Это поле обязательно для заполнения",
                maxlength: jQuery.validator.format("Не более {0} символов"),
                minlength: jQuery.validator.format("Не менее {0} символов"),
            },

            number:{
                required: "Это поле обязательно для заполнения",
                checkMask: "Введите полный номер телефона",
            },

        },
        submitHandler: sendAjaxForm

    });
    $('.js-phone').mask("+7(999)999-9999", {autoclear: false});

    function sendAjaxForm() {
        $.ajax({
            url:     "http://gpool.io/engine/api/add.php", //url страницы (add-new.php)
            type:     "GET", //метод отправки
            dataType: "html", //формат данных
            data: $('#ajax_add').serialize(),

            success: function(response) { //Данные отправлены успешно
                result = $.parseJSON(response);
                if(result.error) {
                    $.alert({
                        icon: 'fas fa-times',
                        type: 'red',
                        title: 'Ошибка',
                        content: 'Проверьте все поля на правильность',
                        theme: 'modern',
                        autoClose: 'thx3|5000',
                        buttons: {
                            thx3: {
                                text: 'ОК',
                            }
                        }


                    });
                }  else if(result.empty) {
                    $.alert({
                        icon: 'far fa-edit',
                        type: 'red',
                        title: 'Ошибка',
                        content: 'Заполните все поля формы',
                        theme: 'modern',
                        autoClose: 'thx3|5000',
                        buttons: {
                            thx3: {
                                text: 'ОК',
                            }
                        }


                    });
                } else if(result.success) {
                    $.alert({
                        icon: 'fas fa-check-double',
                        type: 'green',
                        title: 'Успешно',
                        content: 'Организация добавлена',
                        theme: 'modern',
                        autoClose: 'thx3|5000',
                        buttons: {
                            thx3: {
                                text: 'ОК',
                            }
                        }


                    });
                    var delay = 5000;
                    setTimeout("document.location.href='/'", delay);
                    //document.location.href = 'entry';
                } else if(result.already) {
                    $.alert({
                        icon: 'fas fa-times',
                        type: 'red',
                        title: 'Ошибка',
                        content: 'Организация с таким ИНН или КПП уже зарегистрирована',
                        theme: 'modern',
                        autoClose: 'thx3|5000',
                        buttons: {
                            thx3: {
                                text: 'ОК',
                            }
                        }


                    });
                } else if(result.errorlang) {
                    $.alert({
                        icon: 'fas fa-language',
                        type: 'red',
                        title: 'Ошибка',
                        content: 'Наименование организации должно использовать или кириллицу, или латиницу',
                        theme: 'modern',
                        autoClose: 'thx3|5000',
                        buttons: {
                            thx3: {
                                text: 'ОК',
                            }
                        }


                    });
                }

            },
            error: function(response) {
                $.alert({
                    icon: 'fas fa-times',
                    type: 'red',
                    title: 'Ошибка',
                    content: 'Данные не отправлены',
                    theme: 'modern',
                    autoClose: 'thx3|3000',
                    buttons: {
                        thx3: {
                            text: 'ОК',
                        }
                    }


                });
            }
        });


    }



}); //end of ready