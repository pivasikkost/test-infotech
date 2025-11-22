<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class BookSearch extends Book
{
    public $authorName;

    public function rules()
    {
        return [
            [['id', 'year'], 'integer'],
            [['title', 'isbn', 'description', 'authorName'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Book::find()->joinWith(['authors']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'title',
                    'year',
                    'isbn',
                    'created_at',
                    'authorName' => [
                        'asc' => ['author.full_name' => SORT_ASC],
                        'desc' => ['author.full_name' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'book.id' => $this->id,
            'book.year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'book.title', $this->title])
            ->andFilterWhere(['like', 'book.isbn', $this->isbn])
            ->andFilterWhere(['like', 'book.description', $this->description])
            ->andFilterWhere(['like', 'author.full_name', $this->authorName]);

        return $dataProvider;
    }
}