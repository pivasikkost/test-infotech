<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'cover_image',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->cover_image) {
                        return Html::img('@web/uploads/' . $model->cover_image, [
                            'style' => 'max-width: 100px; max-height: 100px;'
                        ]);
                    }
                    return Html::tag('span', 'Нет изображения', ['class' => 'text-muted']);
                },
            ],
            'title',
            [
                'attribute' => 'authors',
                'value' => function ($model) {
                    return implode(', ', \yii\helpers\ArrayHelper::getColumn($model->authors, 'full_name'));
                },
            ],
            'year',
            'isbn',
            //'description:ntext',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        if (Yii::$app->user->isGuest) {
                            return '';
                        }
                        return Html::a('<span class="fas fa-edit"></span>', $url, [
                            'title' => 'Редактировать',
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        if (Yii::$app->user->isGuest) {
                            return '';
                        }
                        return Html::a('<span class="fas fa-trash"></span>', $url, [
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>