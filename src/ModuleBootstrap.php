<?php

namespace tmukherjee13\sochat;

use yii\base\Application;
use yii\base\BootstrapInterface;

class ModuleBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, function () {
            // do something here
        });

        $sFilePathConfig = __DIR__ . '/config/_routes.php';
        if (file_exists($sFilePathConfig)) {
            \Yii::$app->getUrlManager()->addRules(require ($sFilePathConfig));
        }

        if ($app instanceof \yii\console\Application) {
            $app->controllerMap['chat-server'] = [
                'class' => 'tmukherjee13\sochat\console\controllers\ChatServerController',
            ];
        }
    }
}
