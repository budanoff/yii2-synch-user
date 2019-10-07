<?php

namespace budanoff\synchuser;

use yii\base\InvalidConfigException;
use yii\web\HttpException;

/**
 * synchuser module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\module\synchuser\controllers';

    /**
     * @var string
     * */
    public $secret_key;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!$this->secret_key) {
            throw new HttpException(401, "secret key is empty!");
        }
    }
}
