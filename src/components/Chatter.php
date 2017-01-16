<?php

namespace tmukherjee13\chatter\components;

use tmukherjee13\chatter\models\Chat;
use common\models\User;
use yii\db\Query;

trait Chatter
{

    public function insertMessage($data)
    {
        $chat = new Chat();
        $chat->load($data);
        if ($chat->save()) {

            $msg = $this->getMessage($chat->id);
            return $msg;
        } else {
            return $chat->errors;
        }
    }

    public function getMessage($msgId)
    {
        $query = new Query;
        $query->select(['c.id', 'c.sender', 'c.receiver', 'c.message', 'c.created_at', 'u.username'])
            ->from(['c' => Chat::tableName()])
            ->where(['=', 'c.id', $msgId])
        // ->where(['or', ['to_user' => $id], ['from_user' => $id]])
            ->orderBy('created_at', SORT_DESC);
        // ->limit(10);

        $query->leftJoin(['u' => User::tableName()], 'u.id = c.sender');
        // $query->join('LEFT JOIN', User::tableName(), 'wf_user.id = wf_chat.from_user');

        $message = $query->one();
        return $message;
    }
}
