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
     * @var array Options for RbacConfigParser
     * @see RbacConfigParser
     */
    public $parserOptions = [];

    /**
     * Update data from configuration
     */
    public function actionUpdate()
    {
        $parser = new RbacConfigParser($this->parserOptions);
        $parser->fill();
    }
}

