<?php

namespace arogachev\rbac\controllers;

use arogachev\rbac\components\RbacConfigParser;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'update';

    /**
     * Update data from configuration
     * @param string $configPath
     */
    public function actionUpdate($configPath)
    {
        $parser = new RbacConfigParser(['configPath' => $configPath]);
        $parser->fill();
    }
}

