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

namespace app\helpers;

################################################################################
# Include(s)                                                                   #
################################################################################

include_once 'simplelog.inc';
include_once 'jsonsimpleconfig.inc';

################################################################################
# Local Config                                                                 #
################################################################################

const messengerPortalConfigFile = '/etc/p57b/messenger_portal.jsc';

################################################################################
# Use(s)                                                                       #
################################################################################

use xmz\jsonsimpleconfig\Jsc;

################################################################################
# Class(es)                                                                    #
################################################################################

class MessengerPortalConfig
{

    private static $instance;

    private function __construct()
    {
        $this->jscData = Jsc::get(messengerPortalConfigFile);
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function get()
    {
        if (!isset(self::$instance) || !self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function getValue($group, $key, $default = null)
    {
        return ($this->jscData->getValue($group, $key, $default));
    }

    function getSection($group)
    {
        return ($this->jscData->getSection($group));
    }

    function getYii2MessengerPortalCookieValidationKey()
    {
        return $this->getValue('"Yii2"."messengerPortal"', 'cookieValidationKey', 'MessengerPortal');
    }

    function getYii2MessengerPortalDbHost()
    {
        return $this->getValue('"Yii2"."messengerPortal"."db"', 'dbHost');
    }

    function getYii2MessengerPortalDbPort()
    {
        return $this->getValue('"Yii2"."messengerPortal"."db"', 'dbPort');
    }

    function getYii2MessengerPortalDbSchema()
    {
        return 'messenger';
    }

    function getYii2MessengerPortalDbName()
    {
        return $this->getValue('"Yii2"."messengerPortal"."db"', 'dbName');
    }

    function getYii2MessengerPortalAdmins()
    {
        return $this->getValue('"Yii2"."messengerPortal"', 'admins', []);
    }

    function getYii2MessengerPortalDbUser()
    {
        return $this->getValue('"Yii2"."messengerPortal"."db"', 'dbUser');
    }

    function getYii2MessengerPortalDbPassword()
    {
        return $this->getValue('"Yii2"."messengerPortal"."db"', 'dbPassword');
    }

    function getYii2MessengerLdapConfig()
    {
        return $this->getSection('"Yii2"."messengerPortal"."LDAP"');
    }

    function getYii2MessengerPortalMessagesRefreshPeriod()
    {
        return $this->getValue('"Yii2"."messengerPortal"', 'messagesRefreshPeriod', 30);
    }

    function getYii2MessengerPortalAttempts()
    {
        return $this->getValue('"Yii2"."messengerPortal"."messages"', 'attempts', 5);
    }

    function getYii2MessengerPortalSleep()
    {
        return $this->getValue('"Yii2"."messengerPortal"."messages"', 'sleep', 2);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
