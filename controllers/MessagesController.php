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

namespace app\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\helpers\MessengerPortalConfig;
use app\models\Messages;
use Yii;
use yii\data\ActiveDataProvider;

################################################################################
# Class(es)                                                                    #
################################################################################

class MessagesController extends MessengerController
{

    public function actionIndex()
    {
        $attempts = MessengerPortalConfig::get()->getYii2MessengerPortalAttempts();
        $sleepTime = MessengerPortalConfig::get()->getYii2MessengerPortalSleep();
        while (true) {
            try {

                $query = Messages::find();
                if (Yii::$app->user->identity->isAdministrator() === false) {
                    $query = $query->where(['to' => Yii::$app->user->identity->email]);
                    $query = $query->orWhere(['to' => Yii::$app->user->identity->phone]);
                }
                $dataProvider = new ActiveDataProvider(
                    [
                        'query' => $query,
                        'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
                        'pagination' => ['pageSize' => 10]
                    ]
                );
            } catch (RuntimeException $e) {
                Yii::error($e->getMessage());
                $dataProvider = null;
            } catch (ErrorException $e) {
                Yii::error($e->getMessage());
                $dataProvider = null;
            }
            if (
                empty($dataProvider)
                && ($attempts > 0)
            ) {
                $attempts--;
                sleep($sleepTime);
            } else {
                break;
            }

        };

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }
}

################################################################################
#                                End of file                                   #
################################################################################
