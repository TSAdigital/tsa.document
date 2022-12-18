<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap5\Html;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $date
 * @property Json $resolution
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $author
 */
class Document extends ActiveRecord
{
    public $send_email;
    public $date_from;
    public $date_to;

    /**
     *
     */
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status', 'date'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 600],
            ['date', 'date', 'format' => 'php:d.m.Y'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            ['author', 'integer'],
            ['resolution', 'checkIsArray'],
            [['name', 'description'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['name', 'description'], 'trim'],
            ['send_email', 'boolean']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'name' => 'Наименование',
            'description' => 'Описание',
            'date' => 'Дата документа',
            'author' => 'Автор',
            'document_author' => 'Автор документа',
            'resolution' => 'Резолюция',
            'viewed' => 'Ознакомлены',
            'files' => 'Файлы',
            'status' => 'Статус',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }

    /**
     *
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_INACTIVE => 'Аннулирован',
        ];
    }

    /**
     *
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }


    /**
     *
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->date = !empty($this->date) ? Yii::$app->formatter->asDate($this->date): NULL;
    }

    /**
     *
     */
    public function beforeSave($insert)
    {
        !$this->isNewRecord ?: $this->author = Yii::$app->user->identity->getId();
        is_array($this->resolution) ?: $this->resolution = NULL;
        $this->date = !empty($this->date) ? date('Y-m-d', strtotime($this->date)) : NULL;

        return parent::beforeSave($insert);
    }

    /**
     *
     */
    public function checkIsArray(){
        if(!is_array($this->resolution)){
            $this->addError('resolution','Это не массив');
        }
    }

    /**
     *
     */
    public function getEmployeesName()
    {
        $employee = new Employee();
        return $employee->getEmployeeName();
    }

    /**
     *
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'author']);
    }

    /**
     *
     */
    public function getFiles($id)
    {
        return UploadForm::find()->where(['document_id' => $id]);
    }

    /**
     *
     */
    public function getUsers($id)
    {
        return implode(' &equiv; ', ArrayHelper::map(User::findAll(['id' => $id]),'id',function($data){return  Html::a($data->employee_name, ['site/profile', 'id' => $data->id]); }));
    }

    /**
     *
     */
    public function isViewed($document_id)
    {
        return Viewed::findOne(['document_id' => $document_id, 'user_id' => Yii::$app->user->identity->getId()]);
    }

    /**
     *
     */
    public function isResolution()
    {
        if($this->resolution != NULL){
            if(in_array(Yii::$app->user->identity->getId(), (array)$this->resolution)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
}
