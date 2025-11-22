<?php

use yii\helpers\Html;

$this->title = 'ТОП 10 авторов за ' . $year . ' год';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="author-top">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <?= Html::beginForm(['author/top'], 'get') ?>
            <div class="form-group">
                <?= Html::label('Год', 'year') ?>
                <?= Html::input('number', 'year', $year, ['class' => 'form-control', 'min' => 1900, 'max' => date('Y')]) ?>
            </div>
            <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>

    <hr>

    <?php if (empty($top)): ?>
        <p>Нет данных за выбранный год.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Автор</th>
                    <th>Количество книг</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top as $index => $author): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= Html::encode($author->full_name) ?></td>
                        <td><?= $author->book_count ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>