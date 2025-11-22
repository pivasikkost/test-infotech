<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$this->title = 'Редактировать книгу: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="book-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="book-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'authorIds')->widget(Select2::class, [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Author::find()->all(), 'id', 'full_name'),
                    'options' => ['placeholder' => 'Выберите авторов...', 'multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tags' => false,
                    ],
                ])->label('Авторы') ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'year')->textInput([
                            'type' => 'number',
                            'min' => 1900,
                            'max' => date('Y'),
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                
                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
            </div>
            
            <div class="col-md-4">
                <?= $form->field($model, 'coverImageFile')->fileInput() ?>
                
                <?php if ($model->cover_image): ?>
                    <div class="form-group">
                        <label>Текущее изображение:</label>
                        <div>
                            <?= Html::img('@web/uploads/' . $model->cover_image, [
                                'style' => 'max-width: 200px; max-height: 200px;'
                            ]) ?>
                            <div class="mt-2">
                                <?= Html::a('Удалить изображение', ['delete-image', 'id' => $model->id], [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить изображение?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>