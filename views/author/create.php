<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Добавить автора';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="author-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'full_name')->textInput([
            'maxlength' => true,
            'placeholder' => 'Введите полное имя автора'
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>