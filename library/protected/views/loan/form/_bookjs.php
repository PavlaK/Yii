<?php
//Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScript('bookRow', "var lastBook = 0;
    var trBook = new String(" .
    CJSON::encode($this->renderPartial('form/_bookRow', array('id' => 'idRep', 'model' => new Item, 'form' => $form, 'this' => $this), true, false)) .
    ");

    function addBook(button)
    {
        lastBook++;
        button.parents('table').children('tbody').append(trBook.replace(/idRep/g, 'newRow' + lastBook));
    }


    function deleteBook(button)
    {
        button.parents('tr').detach();
    }
    $('body').on('focus','.datepicker1', function(){

            $(this).datepicker();
    })
");
?>





