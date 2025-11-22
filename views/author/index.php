<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Добавить автора', ['create'], ['class' => 'btn btn-success']) ?>
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
            'full_name',
            [
                'attribute' => 'book_count',
                'label' => 'Количество книг',
                'value' => function ($model) {
                    return $model->books ? count($model->books) : 0;
                },
                'filter' => false,
            ],
            [
                'attribute' => 'books',
                'label' => 'Книги',
                'format' => 'html',
                'value' => function ($model) {
                    $books = [];
                    foreach ($model->books as $book) {
                        $books[] = Html::a(Html::encode($book->title), ['book/view', 'id' => $book->id]);
                    }
                    return implode('<br>', $books) ?: '<span class="text-muted">Нет книг</span>';
                },
                'filter' => false,
                'enableSorting' => false,
            ],

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
                                'confirm' => 'Вы уверены, что хотите удалить этого автора?',
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