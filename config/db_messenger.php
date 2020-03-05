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
# Use(s)                                                                       #
################################################################################

use app\helpers\MessengerPortalConfig;

################################################################################
# DB Config                                                                    #
################################################################################

$dbHost = MessengerPortalConfig::get()->getYii2MessengerPortalDbHost();
$dbPort = MessengerPortalConfig::get()->getYii2MessengerPortalDbPort();
$dbSchema = MessengerPortalConfig::get()->getYii2MessengerPortalDbSchema();
$dbName = MessengerPortalConfig::get()->getYii2MessengerPortalDbName();
$dbUser = MessengerPortalConfig::get()->getYii2MessengerPortalDbUser();
$dbPassword = MessengerPortalConfig::get()->getYii2MessengerPortalDbPassword();

$dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName";

return
    [
        'class' => 'yii\db\Connection',
        'dsn' => "$dsn",
        'username' => "$dbUser",
        'password' => "$dbPassword",
        'charset' => 'utf8',
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 3600,
        'schemaCache' => 'cache',
        'schemaMap' =>
            [
                'pgsql' =>
                    [
                        'class' => 'yii\db\pgsql\Schema',
                        'defaultSchema' => "$dbSchema"
                    ]
            ],
        'on afterOpen' => function ($event) {
            $dbSchema = MessengerPortalConfig::get()->getYii2MessengerPortalDbSchema();
            $event->sender->createCommand("SET search_path TO $dbSchema;")->execute();
        },
    ];

################################################################################
#                                End of file                                   #
################################################################################
