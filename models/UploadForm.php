<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "positions".
 *
 * @property int $id
 * @property int $document_id
 * @property string $name
 * @property string $dir
 * @property string $file_name
 * @property int $created_at
 * @property int $updated_at
 *
 */

class UploadForm extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['name','dir'], 'string', 'max' => 255],
            [['file', 'name'], 'required'],
            ['document_id', 'integer'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, zip, 7z, pdf, xls, doc', 'maxSize' => 10*(1024*1024), 'tooBig' => 'Превышен максимально допустимый размер (объём) файла в 10 Мб'],
            ['name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['name', 'trim'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs("uploads/$this->dir/" . $this->file_name);
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'file' => 'Файл',
        ];
    }

    public function getFileType($file_name)
    {
        $path_parts = pathinfo($file_name);

        switch (empty($path_parts['extension']) ?: $path_parts['extension']) {
            case 'pdf':
                return 'Документ Pdf';
            case 'doc':
                return 'Документ Word';
            case '7z':
            case 'zip':
                return 'Архив';
            case 'xls':
                return 'Документ Excel';
            case 'png':
            case 'jpg':
                return 'Изображение';

            default: return 'Тип файла не определен';
        }
    }
}