<?php

use yii\db\Migration;

class m251122_184930_create_subscriptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Таблица подписок на авторов
        $this->createTable('{{%author_subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Добавляем внешний ключ
        $this->addForeignKey(
            'fk-author_subscription-author_id',
            '{{%author_subscription}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Добавляем индексы для улучшения производительности
        $this->createIndex('idx-author_subscription-author_id', '{{%author_subscription}}', 'author_id');
        $this->createIndex('idx-author_subscription-phone', '{{%author_subscription}}', 'phone');

        // Уникальный индекс, чтобы один телефон не мог подписаться дважды на одного автора
        $this->createIndex('idx-author_subscription-author_phone', '{{%author_subscription}}', ['author_id', 'phone'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%author_subscription}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251122_184930_create_subscriptions cannot be reverted.\n";

        return false;
    }
    */
}
