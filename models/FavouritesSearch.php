<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FavouritesSearch represents the model behind the search form of `app\models\Favourites`.
 */
class FavouritesSearch extends Favourites
{

    public $document_name;
    public $document_author;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['document_name', 'document_author', 'date_from', 'date_to'], 'safe'],
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
        $query = Favourites::find()->where(['user_id' => Yii::$app->user->identity->getId()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        $query->joinWith(['document', 'document.user.employee', 'user']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'document_name' => [
                    'asc' => ['document.name' => SORT_ASC],
                    'desc' => ['document.name' => SORT_DESC],
                    'label' => 'document_name',
                    'default' => SORT_ASC
                ],
                'document_author' => [
                    'asc' => ['employee.last_name' => SORT_ASC],
                    'desc' => ['employee.last_name' => SORT_DESC],
                    'label' => 'document_author',
                    'default' => SORT_ASC
                ],
                'document_date' => [
                    'asc' => ['document.date' => SORT_ASC],
                    'desc' => ['document.date' => SORT_DESC],
                    'label' => 'document_date',
                    'default' => SORT_ASC
                ],

            ],
            'defaultOrder' => [
                'id' => SORT_DESC
            ]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'document_id' => $this->document_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'document.name', $this->document_name])
            ->andFilterWhere(
                ['or',
                    ['like', 'employee.first_name', $this->document_author],
                    ['like', 'employee.last_name', $this->document_author],
                    ['like', 'employee.middle_name', $this->document_author],
                    ['like', 'user.username', $this->document_author],
                ])
            ->andFilterWhere(['>=', 'document.date', $this->date_from ? date('Y-m-d', strtotime($this->date_from)) : null])
            ->andFilterWhere(['<=', 'document.date', $this->date_to ? date('Y-m-d', strtotime($this->date_to)) : null]);

        return $dataProvider;
    }
}
