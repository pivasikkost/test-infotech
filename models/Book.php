<?php

namespace app\models;

use app\services\SmsService;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $cover_image
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BookAuthor[] $bookAuthors
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $coverImageFile;

    public static function tableName()
    {
        return 'book';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'year', 'isbn'], 'required'],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['isbn'], 'unique'],
            [['cover_image'], 'string', 'max' => 255],
            [['coverImageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['authorIds'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover_image' => 'Фото обложки',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'coverImageFile' => 'Фото обложки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->via('bookAuthors');
    }

    public $authorIds = [];

    public function afterFind()
    {
        parent::afterFind();
        $this->authorIds = \yii\helpers\ArrayHelper::getColumn($this->authors, 'id');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');

            // Обработка загрузки изображения
            if ($this->coverImageFile) {
                $fileName = Yii::$app->security->generateRandomString() . '.' . $this->coverImageFile->extension;
                $filePath = Yii::getAlias('@webroot/uploads/') . $fileName;
                if ($this->coverImageFile->saveAs($filePath)) {
                    $this->cover_image = $fileName;
                }
            }

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Обновление связей с авторами
        if (!$insert) {
            BookAuthor::deleteAll(['book_id' => $this->id]);
        }

        if (!empty($this->authorIds)) {
            foreach ($this->authorIds as $authorId) {
                $bookAuthor = new BookAuthor();
                $bookAuthor->book_id = $this->id;
                $bookAuthor->author_id = $authorId;
                $bookAuthor->save();
            }
        }

        // Отправка уведомлений подписчикам
        if ($insert) {
            $this->sendNewBookNotifications();
        }
    }

    /**
     * Отправка уведомлений подписчикам авторов
     */
    private function sendNewBookNotifications()
    {
        $authors = $this->authors;
        foreach ($authors as $author) {
            $subscriptions = AuthorSubscription::find()
                ->where(['author_id' => $author->id])
                ->all();

            foreach ($subscriptions as $subscription) {
                $smsService = new SmsService();
                $message = "Новая книга автора {$author->full_name}: \"{$this->title}\". ISBN: {$this->isbn}";
                $smsService->sendSms($subscription->phone, $message);
            }
        }
    }
}