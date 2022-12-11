<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;


/**
 * DocumentSearch represents the model behind the search form of `app\models\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * {@inheritdoc}
     */

    public $document_author;

    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'resolution', 'author', 'date', 'date_from', 'date_to', 'document_author'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $user_id = Yii::$app->user->identity->getId();
        $user_role = current(ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id), 'name'));

        if($user_role == 'user'){
            $query = Document::find()->where(new Expression("JSON_CONTAINS(resolution, '\"$user_id\"')"))
                ->orWhere(['resolution' => NULL]);
        }elseif($user_role == 'author'){
            $query = Document::find()->where(new Expression("JSON_CONTAINS(resolution, '\"$user_id\"')"))
                ->orWhere(['resolution' => NULL])
                ->orWhere(['author' => $user_id]);
        }else{
            $query = Document::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        $query->joinWith(['user', 'user.employee']);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'document_author' => [
                    'asc' => ['employee.first_name' => SORT_ASC],
                    'desc' => ['employee.first_name' => SORT_DESC],
                    'label' => 'document_author',
                    'default' => SORT_ASC
                ],
                'name',
                'date',
                'status',
            ],
            'defaultOrder' => [
                'id' => SORT_DESC
            ]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'document.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(
                ['or',
                    ['like', 'employee.first_name', $this->document_author],
                    ['like', 'employee.last_name', $this->document_author],
                    ['like', 'employee.middle_name', $this->document_author],
                ])
            ->andFilterWhere(['>=', 'date', $this->date_from ? date('Y-m-d', strtotime($this->date_from)) : null])
            ->andFilterWhere(['<=', 'date', $this->date_to ? date('Y-m-d', strtotime($this->date_to)) : null]);

        return $dataProvider;
    }
}