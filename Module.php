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
    public $controllerNamespace = 'budanoff\synchuser\controllers';

    /**
     * @var string
     * */
    public $secret_key;

    /**
     * @var array
     * */
    public $role;

    /**
     * @var boolean
     * */
    public $reload_role;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!$this->secret_key) {
            throw new HttpException(401, "secret key is empty!");
        }
        if (!$this->role) {
            $this->role = [];
        }
        if (!$this->reload_role) {
            $this->reload_role = false;
        }
    }
}
