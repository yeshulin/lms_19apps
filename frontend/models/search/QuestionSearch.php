<?php

namespace frontend\models\search;

use frontend\models\Question;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * ContentSearch represents the model behind the search form about `backend\models\Content`.
 */
class QuestionSearch extends Question
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'touserid', 'created_at', 'created_at', 'status'], 'integer'],
            [['question'], 'string'],
            [['qsign', 'question', 'content', 'userid', 'touserid', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
    public function  search($params)
    {
        $query = Question::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'qsign' => $this->qsign,
            'userid' => $this->userid,
            'touserid' => $this->touserid,
            'listorder' => $this->listorder,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_at' => $this->created_at,

        ]);
        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
