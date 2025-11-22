<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Подписка на новые книги автора';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $author->full_name, 'url' => ['view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-subscribe">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <p>Вы подписываетесь на уведомления о новых книгах автора: <strong><?= Html::encode($author->full_name) ?></strong></p>
        <p>При появлении новых книг этого автора вы получите SMS уведомление на указанный номер телефона.</p>
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone')->textInput([
        'maxlength' => true,
        'placeholder' => '79991234567'
    ])->hint('Введите номер телефона в формате 79991234567') ?>

    <div class="form-group">
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['view', 'id' => $author->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="mt-4">
        <h4>Как это работает?</h4>
        <ul>
            <li>Вы указываете свой номер телефона</li>
            <li>Когда появится новая книга автора <?= Html::encode($author->full_name) ?>, вы получите SMS</li>
            <li>В SMS будет указано название книги и ISBN</li>
            <li>Вы можете отписаться, удалив подписку в любое время</li>
        </ul>
    </div>

</div>