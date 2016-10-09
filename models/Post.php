<?php

namespace app\models;

use app\models\Tag;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 *
 * @property Comment[] $comments
 * @property User $author
 */
class Post extends \yii\db\ActiveRecord
{

    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'author_id'], 'required'],
            [['content', 'tags'], 'string'],
            [['status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            array('status', 'in', 'range' => array(1, 2, 3)),
            array('tags', 'match', 'pattern' => '/^[А-Яа-я\w\s,]+$/u',
                'message' => 'В тагах можно использовать только буквы.'),
            array('tags', 'normalizeTags')
        ];
    }

    public function normalizeTags($attribute, $params)
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'content' => 'Содержание',
            'tags' => 'Таги',
            'status' => 'Статус',
            'create_time' => 'Время создания',
            'update_time' => 'Время изменения',
            'author_id' => 'Автор',
        ];
    }


    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id'])
            ->where('status = ' . Comment::STATUS_APPROVED)
            ->orderBy('create_time DESC');
    }


    public function getCommentCount()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id'])
            ->where('status = ' . Comment::STATUS_APPROVED)->count();
    }


    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }


    public function getUrl()
    {
        return Url::toRoute(['post/view',
            'id' => $this->id,
            'title' => $this->title
        ]);
    }

    public function getTagLinks()
    {
        $links = array();
        foreach (Tag::string2array($this->tags) as $tag)
            $links[] = Html::a($tag, array('post/index', 'tag' => $tag));
        return $links;
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->create_time = $this->update_time = time();
                $this->author_id = Yii::$app->user->id;
            } else     
                $this->update_time = time();       
            return true;
        } else
            return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        Tag::updateFrequency($this->_oldTags, $this->tags);
    }

    private $_oldTags;

    public function afterDelete()
    {
        parent::afterDelete();
        Comment::deleteAll('post_id='.$this->id);
        Tag::updateFrequency($this->tags, '');
    }


    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
    }


    public function addComment($comment)
    {
        if (Yii::$app->params['commentNeedApproval'])
            $comment->status = Comment::STATUS_PENDING;
        else
            $comment->status = Comment::STATUS_APPROVED;
                
        $comment->post_id = $this->id;
        return $comment->save();
    }

}