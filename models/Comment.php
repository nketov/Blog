<?php

namespace app\models;

use Yii;
use yii\bootstrap\Html;


class Comment extends \yii\db\ActiveRecord
{

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;


    public static function tableName()
    {
        return '{{%comment}}';
    }

    public function rules()
    {
        return [
            [['content', 'status', 'author', 'email', 'post_id'], 'required'],
            [['content'], 'string'],
            [['status', 'create_time', 'post_id'], 'integer'],
            [['author', 'email', 'url'], 'string', 'max' => 128],
            ['email', 'email'],
            ['url', 'url'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Содержание',
            'status' => 'Состояние',
            'create_time' => 'Дата создания',
            'author' => 'Автор',
            'email' => 'E-mail',
            'url' => 'Url',
            'post_id' => 'Номер поста',
        ];
    }


    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }


    public function approve()
    {
        $this->status = Comment::STATUS_APPROVED;
        $this->update(array('status'));
    }


    public function getCommentUrl($post = null)
    {
        if ($post === null)
            $post = $this->post;
        return $post->url . '#c' . $this->id;
    }


    public function getAuthorLink()
    {
        if (!empty($this->url))
            return Html::a(Html::encode($this->author), $this->url);
        else
            return Html::encode($this->author);
    }


    public static function getPendingCommentCount()
    {
        return self::find()->joinWith('post')
            ->onCondition(['=', 'author_id', Yii::$app->user->id])
            ->andWhere(['tbl_comment.status' => self::STATUS_PENDING])
            ->count();
    }


    public static function findRecentComments($limit = 10)
    {
        return self::find()->
        where(['status' => self::STATUS_APPROVED])->
        orderBy('create_time DESC')->
        limit($limit)->with('post')->all();
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord)
                $this->create_time = time();
            return true;
        } else
            return false;
    }
}

