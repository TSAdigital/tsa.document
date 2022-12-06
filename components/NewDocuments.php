<?php

namespace app\components;

use app\models\Document;
use app\models\Viewed;
use Yii;
use yii\base\Component;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class NewDocuments extends Component {

    public function getNewDocuments() {
        $user_id = Yii::$app->user->identity->getId();
        $user_role = current(ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id), 'name'));
        if($user_role == 'user'){
            $documents = Document::find()->where(new Expression("JSON_CONTAINS(resolution, '\"$user_id\"')"))
                ->orWhere(['resolution' => NULL])->count();
        }elseif($user_role == 'author'){
            $documents = Document::find()
                ->where(new Expression("JSON_CONTAINS(resolution, '\"$user_id\"')"))
                ->orWhere(['resolution' => NULL])
                ->andWhere(['!=', 'author', $user_id])
                ->count();
        }else{
            $documents = Document::find()
            ->andWhere(['!=', 'author', $user_id])
            ->count();
        }

        $viewDocuments = Viewed::find()->where(['user_id' => $user_id])->count();

        $data = $documents - $viewDocuments;

        return $data > 0 ? $data : null;
    }

}