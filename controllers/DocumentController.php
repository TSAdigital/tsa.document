<?php

namespace app\controllers;

use app\models\Discussion;
use app\models\Document;
use app\models\DocumentSearch;
use app\models\UploadForm;
use app\models\User;
use app\models\Viewed;
use Yii;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'file-delete' => ['POST'],
                        'delete-discussion' => ['POST'],
                        'viewed' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index'],
                            'roles' => ['user'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['view'],
                            'roles' => ['viewDocument'],
                            'roleParams' => function() {
                                return ['document_resolution' => Document::findOne(['id' => Yii::$app->request->get('id')])];
                            },
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['createDocument'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update'],
                            'roles' => ['updateDocument'],
                            'roleParams' => function() {
                                return ['document_author' => Document::findOne(['id' => Yii::$app->request->get('id')])];
                            },
                        ],
                        [
                            'allow' => true,
                            'actions' => ['upload'],
                            'roles' => ['updateDocument'],
                            'roleParams' => function() {
                                return ['document_author' => Document::findOne(['id' => Yii::$app->request->get('id')])];
                            },
                        ],
                        [
                            'allow' => true,
                            'actions' => ['file-delete'],
                            'roles' => ['updateDocument'],
                            'roleParams' => function() {
                                return ['document_author' => Document::findOne(['id' => Yii::$app->request->get('id')])];
                            },
                        ],
                        [
                            'allow' => true,
                            'actions' => ['download'],
                            'roles' => ['viewDocument'],
                            'roleParams' => function() {
                                return ['document_resolution' => Document::findOne(['id' => Yii::$app->request->get('id')])];
                            },
                        ],
                        [
                            'allow' => true,
                            'actions' => ['viewed'],
                            'roles' => ['viewDocument'],
                            'roleParams' => function() {
                                return ['document_resolution' => Document::findOne(['id' => Yii::$app->request->get('id')])];
                            },
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete'],
                            'roles' => ['admin'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete-discussion'],
                            'roles' => ['admin'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Document models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $files = $model->getFiles($id);
        $file = implode(' &equiv; ' , ArrayHelper::map($files, 'id', function($data)
        {
            if (\Yii::$app->user->can('updateDocument', ['document_author' => $this->findModel($data->document_id)])) {
                $delete = Html::a(' <sup><i class="fas fa-times text-danger"></i></sup>',
                    ['/document/file-delete', 'id' => $data->document_id, 'file' => $data->id],
                    ['data' => ['confirm' => "Вы уверены, что хотите удалить файл $data->name?", 'method' => 'post']]
                );
            }else{
                $delete = null;
            }

            $name = $data->getFileType($data->file_name) . ' ' . Html::a($data->name,
                ['/document/download', 'id' => $data->document_id, 'file' => $data->id]
            );
            return $name . $delete;
        }
        ));

        $viewed = Viewed::find()->where(['document_id' => $id]);

        $data = ArrayHelper::map($viewed->all(), 'id', 'user_id');

        if($model->resolution == NULL){
            $resolution = User::find()->where(['not in', 'id', $data])->andWhere(['not in', 'id', $model->author]);
        } else {
            $resolution = User::find()->where(['not in', 'id', $data])->andWhere(['id' => $model->resolution])->andWhere(['not in', 'id', $model->author]);
        }

        $dataViewed = new ActiveDataProvider([
            'query' => $viewed,
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-viewed',
            ],
        ]);

        $dataNoViewed = new ActiveDataProvider([
            'query' => $resolution,
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-no-viewed',
            ],
        ]);

        $discussion = new Discussion();

        $discussions = $discussion->find()->where(['document_id' => $id]);

        $discussions_count = $discussions->count();

        $dataDiscussions = new ActiveDataProvider([
            'query' => $discussions,
            'pagination' => [
                'pageSize' => 3,
                'pageParam' => 'page-discussions',
            ],
            'sort'=> [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);

        if ($this->request->isPost) {
            $discussion->document_id = is_numeric($id) ? $id : null;
            $discussion->author = Yii::$app->user->identity->getId();
            if ($discussion->load($this->request->post()) && $discussion->save()) {
                $this->refresh();
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('view', [
            'model' => $model,
            'file' => $file,
            'viewed' => $viewed,
            'dataViewed' => $dataViewed,
            'dataNoViewed' => $dataNoViewed,
            'discussion' => $discussion,
            'dataDiscussions' => $dataDiscussions,
            'discussions_count' => $discussions_count ?: null
        ]);
    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Document();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $model->send_email == true ? $this->sendMail($model->resolution, $model->name, $model->description) : false;
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $this->getUsersName(),
        ]);
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'users' => $this->getUsersName(),
        ]);
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteDiscussion(int $id, $discussion)
    {
        $this->findDiscussions($discussion)->delete();

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findDiscussions($discussion)
    {
        if (($model = Discussion::findOne(['id' => $discussion])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }

    /**
     *
     */
    public function getUsersName()
    {
        return ArrayHelper::map(User::find()->where(['status' => 10])->andWhere(['!=', 'id', Yii::$app->user->identity->getId()])->all(),'id','employee_name');

    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpload($id)
    {
        $model = new UploadForm();
        $document = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load($this->request->post())) {
            $model->document_id = $id;
            $model->dir = md5(microtime() . rand(0, 9999));
            FileHelper::createDirectory("uploads/$model->dir/");
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file_name = md5(microtime() . rand(0, 9999)) . '.' . $model->file->extension;

            if ($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $document->id]);
            }else{
                $model->loadDefaultValues();
            }
        }

        return $this->render('upload', ['model' => $model, 'document' => $document]);
    }

    /**
     *
     */
    public function actionFileDelete($id, $file)
    {
        $document = $this->findModel($id);
        $file = $this->findFile($file);
        empty($file->dir) ?: FileHelper::removeDirectory(Yii::$app->basePath . "/web/uploads/$file->dir/");
        $file->delete();

        return $this->redirect(['view', 'id' => $document->id]);
    }

    /**
     *
     */
    protected function findFile($id)
    {
        if (($model = UploadForm::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }

    /**
     *
     */
    public function actionDownload($id, $file)
    {
        $file = $this->findFile($file);
        $path = Yii::getAlias('@webroot') . "/uploads/$file->dir/$file->file_name";

        if(is_file($path)){
            $path_info = pathinfo($path);
            $file_name = !empty(Inflector::slug($file->name,'-')) ? Inflector::slug($file->name,'-') : $file->file_name;

            return Yii::$app->response->sendFile($path, $file_name. '.' .$path_info['extension']);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     *
     */
    public function actionViewed($id)
    {
        $user = Yii::$app->user->identity->getId();
        $model = new Viewed();
        $model->document_id = $id;
        $model->user_id = $user;
        if ($this->request->isPost && $model->save()) {
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     *
     */
    public function sendMail($email, $name, $description)
    {
        if(empty($email)){
            $email = ArrayHelper::map(User::find()->all(), 'id', 'email');
        }else{
            $email = ArrayHelper::map(User::find()->where(['id' => $email])->all(), 'id','email');
        }

        Yii::$app->mailer->compose('layouts/html', ['content' => $description])
            ->setFrom(['info@tsa-digital.ru' => 'TSAdocument'])
            ->setTo($email)
            ->setSubject('Опубликован новый документ: ' . $name)
            ->send();
    }
}
