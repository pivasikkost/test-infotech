<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        
        <?php if (Yii::$app->user->isGuest): ?>
            <?php foreach ($model->authors as $author): ?>
                <?= Html::a('Подписаться на ' . $author->full_name, 
                    ['author/subscribe', 'author_id' => $author->id], 
                    ['class' => 'btn btn-info']
                ) ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            [
                'attribute' => 'authors',
                'value' => function ($model) {
                    return implode(', ', \yii\helpers\ArrayHelper::getColumn($model->authors, 'full_name'));
                },
            ],
            'isbn',
            [
                'attribute' => 'cover_image',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->cover_image) {
                        return Html::img('@web/uploads/' . $model->cover_image, [
                            'style' => 'max-width: 300px; max-height: 300px;'
                        ]);
                    }
                    return 'Нет изображения';
                },
            ],
            'description:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>