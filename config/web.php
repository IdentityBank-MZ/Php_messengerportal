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
# Load params                                                                  #
################################################################################

$params = require(__DIR__ . '/params.php');

################################################################################
# Include(s)                                                                   #
################################################################################

include_once __DIR__ . '/../helpers/MessengerPortalConfig.php';

################################################################################
# Use(s)                                                                       #
################################################################################

use app\helpers\MessengerPortalConfig;

################################################################################
# Web Config                                                                   #
################################################################################

$config =
    [
        'id' => 'Messenger Portal',
        'name' => 'Messenger Portal',
        'version' => '1.0.1',
        'vendorPath' => $yii . '/vendor',
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'language' => 'en-GB',
        'sourceLanguage' => 'en-GB',
        'modules' =>
            [
                'api' =>
                    [
                        'class' => 'app\modules\api\ApiModule',
                    ],
            ],
        'components' =>
            [
                'request' =>
                    [
                        'cookieValidationKey' => MessengerPortalConfig::get()->getYii2MessengerPortalCookieValidationKey(),
                    ],
                'db' => require(__DIR__ . '/db_messenger.php'),
                'user' =>
                    [
                        'identityClass' => 'app\models\MessengerUser',
                        'enableAutoLogin' => false,
                        'identityCookie' => ['name' => '_identity-messenger', 'httpOnly' => true],
                        'absoluteAuthTimeout' => 432000,
                        'authTimeout' => 36000,
                        'loginUrl' => ['/login'],
                    ],
                'urlManager' =>
                    [
                        'class' => 'yii\web\UrlManager',
                        'showScriptName' => false,
                        'enablePrettyUrl' => true,
                        'enableStrictParsing' => false,
                        'rules' =>
                            [
                                'defaultRoute' => '/site/index',
                                'login' => '/site/login',
                                'logout' => '/site/logout',
                            ],
                    ],
                'errorHandler' =>
                    [
                        'errorAction' => 'site/error',
                    ],
                'log' =>
                    [
                        'traceLevel' => YII_DEBUG ? 3 : 0,
                        'targets' =>
                            [
                                [
                                    'class' => 'yii\log\FileTarget',
                                    'levels' => ['error', 'warning'],
                                ],
                            ],
                    ],
            ],
        'params' => $params,
    ];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] =
        [
            'class' => 'yii\debug\Module',
        ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] =
        [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1'] // only for localhost
        ];
}

if (YII_DEBUG) {
    $config['bootstrap'] = ['log'];
}

return $config;

################################################################################
#                                End of file                                   #
################################################################################
