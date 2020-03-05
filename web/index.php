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
# Location(s)                                                                  #
################################################################################

define('YII_VERSION', 'idb');
$dirname = dirname(__FILE__);
$dirConfig = $dirname . '/../config';
define('YII_DIR_CONFIG', $dirConfig);
$yii = '/usr/local/share/p57b/php/3rdparty/yii2/yii-advanced-app-' . YII_VERSION;

################################################################################
# Use(s)                                                                       #
################################################################################

use yii\web\Application as YiiApplication;

################################################################################
# Yii Application Config                                                       #
################################################################################

$debug = false;

// DEBUG mode
if ($debug) {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    defined('YII_ENV') or define('YII_ENV', 'prod');
}

require($yii . '/vendor/autoload.php');
require($yii . '/vendor/yiisoft/yii2/Yii.php');
$config = require($dirConfig . '/web.php');

################################################################################
# Start Yii Application                                                        #
################################################################################

(new YiiApplication($config))->run();

################################################################################
#                                End of file                                   #
################################################################################
