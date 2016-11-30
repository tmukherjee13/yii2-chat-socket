<?php

namespace tmukherjee13\sochat\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use tmukherjee13\sochat\models\Chat;
use common\models\User;
use yii\db\Query;

/**
 * ChatSearch represents the model behind the search form about `common\modules\chat\models\Chat`.
 */
class ChatSearch extends Chat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'to_user', 'from_user', 'status'], 'integer'],
            [['message', 'created_at'], 'safe'],
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
    public function search($params)
    {
        $query = Chat::find();

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
            'to_user' => $this->to_user,
            'from_user' => $this->from_user,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }

    public function searchChat($params)
    {


        // $id = $params['to_user'];

        $id = $this->to_user;
        $query = new Query;
        $query->select(['from_user','to_user','message','username'])
            ->from(['c'=>Chat::tableName()])
            ->orderBy('created_at', SORT_DESC);
            $query->leftJoin(['u' => User::tableName()], 'u.id = c.from_user');

        // $query->join('LEFT JOIN', User::tableName(), 'wf_user.id = wf_chat.from_user');

        // echo $query->createCommand()->rawSql;
        // die;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->where(['or', ['to_user' => $id], ['from_user' => $id]]);
            // return $dataProvider;
        }

        $messages = $query->all();

        return $messages;
    }
}
