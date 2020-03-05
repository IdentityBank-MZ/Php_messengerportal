<?php
# * ********************************************************************* *
# *                                                                       *
# *   Web interface for Messenger data                                    *
# *   This file is part of messengerportal. This project may be found at: *
# *   https://github.com/IdentityBank/Php_messengerportal.                *
# *                                                                       *
# *   Copyright (C) 2020 by Identity Bank. All Rights Reserved.           *
# *   https://www.identitybank.eu - You belong to you                     *
# *                                                                       *
# *   This program is free software: you can redistribute it and/or       *
# *   modify it under the terms of the GNU Affero General Public          *
# *   License as published by the Free Software Foundation, either        *
# *   version 3 of the License, or (at your option) any later version.    *
# *                                                                       *
# *   This program is distributed in the hope that it will be useful,     *
# *   but WITHOUT ANY WARRANTY; without even the implied warranty of      *
# *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the        *
# *   GNU Affero General Public License for more details.                 *
# *                                                                       *
# *   You should have received a copy of the GNU Affero General Public    *
# *   License along with this program. If not, see                        *
# *   https://www.gnu.org/licenses/.                                      *
# *                                                                       *
# * ********************************************************************* *

################################################################################
# Namespace                                                                    #
################################################################################

namespace app\models;

################################################################################
# Use(s)                                                                       #
################################################################################

use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

################################################################################
# Class(es)                                                                    #
################################################################################

class MessengerLoginForm extends Model
{

    public $username;
    public $password;
    private $_user = false;

    public function attributeLabels()
    {
        return
            [
                'username' => 'Messenger login',
                'password' => 'Messenger password',
            ];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $classPath = explode('\\', get_class($this));
        $className = array_pop($classPath);
        foreach ($_REQUEST[$className] as $attribute => $value) {
            if (!in_array($attribute, $this->attributes())) {
                throw new Exception('Invalid request.');
                Yii::$app->end();
            }
        }
        $this->username = preg_replace('/\s+/', '', $this->username);
        $this->username = Html::encode($this->username);

        return parent::validate($attributeNames, $clearErrors);
    }

    public function rules()
    {
        return
            [
                [['username', 'password'], 'required'],
                ['password', 'validatePassword'],
            ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 0);
        }

        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = MessengerUser::findIdentity($this->username);
            if ($this->_user) {
                $session = Yii::$app->session;
                if (!$session->isActive) {
                    $session->open();
                }
                if ($session->isActive) {
                    $session->set('login_' . $this->_user->id, $this->username);
                    $session->close();
                }
            }
        }

        return $this->_user;
    }
}

################################################################################
#                                End of file                                   #
################################################################################
