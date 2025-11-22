<?php

use yii\db\Migration;

class m251122_174820_create_books_and_authors extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Таблица авторов
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Таблица книг
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->notNull()->unique(),
            'cover_image' => $this->string(500), // путь к файлу изображения
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Промежуточная таблица для связи многие-ко-многим
        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        // Добавляем внешние ключи
        $this->addForeignKey(
            'fk-book_author-book_id',
            '{{%book_author}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_author-author_id',
            '{{%book_author}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Создаем составной первичный ключ для промежуточной таблицы
        $this->addPrimaryKey('pk-book_author', '{{%book_author}}', ['book_id', 'author_id']);

        // Добавляем индексы для улучшения производительности
        $this->createIndex('idx-author-full_name', '{{%author}}', 'full_name');
        $this->createIndex('idx-book-title', '{{%book}}', 'title');
        $this->createIndex('idx-book-year', '{{%book}}', 'year');
        $this->createIndex('idx-book-isbn', '{{%book}}', 'isbn', true); // unique index
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // В обратном порядке удаляем таблицы
        $this->dropTable('{{%book_author}}');
        $this->dropTable('{{%book}}');
        $this->dropTable('{{%author}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251122_174820_create_books_and_authors cannot be reverted.\n";

        return false;
    }
    */
}
