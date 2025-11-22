<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "author_subscription".
 *
 * @property int $id
 * @property int $author_id
 * @property string $phone
 * @property string $created_at
 *
 * @property Author $author
 */
class AuthorSubscription extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'author_subscription';
    }

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    public function rules()
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['created_at'], 'safe'],
            [['phone'], 'string', 'max' => 20],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Автор',
            'phone' => 'Телефон',
            'created_at' => 'Дата подписки',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}