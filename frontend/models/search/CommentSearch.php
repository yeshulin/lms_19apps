<?php

namespace frontend\models\search;

use frontend\models\Comment;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ContentSearch represents the model behind the search form about `backend\models\Content`.
 */
class CommentSearch extends Comment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qid','aid', 'userid', 'created_at', 'updated_at', 'status'], 'integer'],
            [['content'], 'string'],
            [['aid','qid','content', 'userid', 'created_at', 'updated_at'], 'safe'],
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
        $query = Comment::find();

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
            'aid' => $this->aid,
            'qid' => $this->qid,
            'userid' => $this->userid,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_at' => $this->created_at,

        ]);
        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
