<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этого автора?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Добавить книгу', ['book/create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
        
        <?php if (Yii::$app->user->isGuest): ?>
            <?= Html::a('Подписаться на новые книги', 
                ['subscribe', 'author_id' => $model->id], 
                ['class' => 'btn btn-info']
            ) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            [
                'label' => 'Количество книг',
                'value' => function ($model) {
                    return count($model->books);
                },
            ],
        ],
    ]) ?>

    <h3>Книги автора</h3>
    
    <?php if ($model->books): ?>
        <?= GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $model->books,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'title',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::a(Html::encode($model->title), ['book/view', 'id' => $model->id]);
                    },
                ],
                'year',
                'isbn',
                [
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                ],
            ],
        ]); ?>
    <?php else: ?>
        <div class="alert alert-info">
            У этого автора пока нет книг.
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::a('Добавить первую книгу', ['book/create'], ['class' => 'alert-link']) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>