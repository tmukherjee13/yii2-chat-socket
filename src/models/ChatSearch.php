<?php

namespace tmukherjee13\chatter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use tmukherjee13\chatter\models\Chat;
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
            [['id', 'receiver', 'sender', 'status'], 'integer'],
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
            'receiver' => $this->receiver,
            'sender' => $this->sender,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }

    public function searchChat($params)
    {


        // $id = $params['receiver'];

        $id = $this->receiver;
        $query = new Query;
        $query->select(['sender','receiver','message','username'])
            ->from(['c'=>Chat::tableName()])
            ->orderBy('created_at', SORT_DESC);
            $query->leftJoin(['u' => User::tableName()], 'u.id = c.sender');

        // $query->join('LEFT JOIN', User::tableName(), 'wf_user.id = wf_chat.sender');

        // echo $query->createCommand()->rawSql;
        // die;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->where(['or', ['receiver' => $id], ['sender' => $id]]);
            // return $dataProvider;
        }

        $messages = $query->all();

        return $messages;
    }
}
