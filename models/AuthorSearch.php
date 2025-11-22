<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class AuthorSearch extends Author
{
    public $bookCount;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['full_name'], 'safe'],
            [['bookCount'], 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Author::find()
            ->select([
                'author.*',
                'COUNT(book_author.book_id) as book_count'
            ])
            ->leftJoin('book_author', 'book_author.author_id = author.id')
            ->groupBy('author.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'full_name',
                    'book_count' => [
                        'asc' => ['book_count' => SORT_ASC],
                        'desc' => ['book_count' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['full_name' => SORT_ASC],
            ],
        ]);

        // Важно: после создания dataProvider загружаем параметры
        $this->load($params);

        if (!$this->validate()) {
            // Отключаем фильтрацию если валидация не прошла
            $query->where('0=1');
            return $dataProvider;
        }

        // Фильтрация
        $query->andFilterWhere(['author.id' => $this->id])
            ->andFilterWhere(['like', 'author.full_name', $this->full_name]);

        // Фильтр по количеству книг
        if ($this->bookCount !== null && $this->bookCount !== '') {
            $query->andHaving(['book_count' => $this->bookCount]);
        }

        return $dataProvider;
    }
}