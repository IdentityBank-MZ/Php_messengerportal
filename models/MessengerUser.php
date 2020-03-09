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

use app\helpers\MessengerPortalConfig;
use Exception;
use yii\base\Model;
use yii\web\IdentityInterface;
use function error_log;

################################################################################
# Class(es)                                                                    #
################################################################################

class MessengerUser extends Model implements IdentityInterface
{

    public $username;
    public $accountName;
    public $email;
    public $phone;

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function isAdministrator()
    {
        return in_array($this->username, MessengerPortalConfig::get()->getYii2MessengerPortalAdmins());
    }

    public function getId()
    {
        return $this->username;
    }

    public static function findIdentity($id)
    {
        return self::getLdapUser($id);
    }

    public function validatePassword($password)
    {
        return self::validateLdapUser($this->getId(), $password);
    }

    private static function getLdapUser($username)
    {
        try {
            $user = null;
            $linkIdentifier = self::getLdapLinkIdentifier();
            if ($linkIdentifier) {
                try {
                    $baseDn = self::getLdapBaseDn($linkIdentifier, $username);
                    $ldapConfig = MessengerPortalConfig::get()->getYii2MessengerLdapConfig();
                    $messengerGroup = ((empty($ldapConfig['messengerGroup'])) ? null : $ldapConfig['messengerGroup']);
                    $checkGroup = self::checkGroupEx($linkIdentifier, $baseDn, $messengerGroup);
                    if ($checkGroup) {
                        $filter = "(objectclass=*)";
                        $readAttributes = ["cn", "sn", "uid", "mail", "mobile"];
                        $searchResult = ldap_read($linkIdentifier, $baseDn, $filter, $readAttributes);
                        $entries = ldap_get_entries($linkIdentifier, $searchResult);
                        if ($entries && is_array($entries) && (count($entries) > 0)) {
                            $entry = $entries[0];
                            $sn = $uid = $userName = $userLogin = $userEmail = $userMobile = $userDn = null;
                            if (!empty($entry['uid'][0])) {
                                $uid = $entry['uid'][0];
                            }
                            if (!empty($entry['sn'][0])) {
                                $sn = $entry['sn'][0];
                            }
                            if (!empty($entry['cn'][0])) {
                                $userName = $entry['cn'][0];
                            }
                            if (!empty($entry['cn'][1])) {
                                $userLogin = $entry['cn'][1];
                            }
                            if (!empty($entry['mail'][0])) {
                                $userEmail = $entry['mail'][0];
                            }
                            if (!empty($entry['mobile'][0])) {
                                $userMobile = $entry['mobile'][0];
                            }
                            if (!empty($entry['dn'])) {
                                $userDn = $entry['dn'];
                            }

                            $user = new self();
                            $user->username = $sn;
                            $user->accountName = $userName;
                            $user->email = $userEmail;
                            $user->phone = $userMobile;
                        } else {
                            error_log('No LDAP entries for user: ' . $username);
                        }
                    } else {
                        error_log("The user [$baseDn] is not part of messenger group [$messengerGroup].");
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
                ldap_close($linkIdentifier);

                return $user;
            }
        } catch (Exception $e) {
            error_log('Caught exception: ' . $e->getMessage());
        }

        return null;
    }

    private static function validateLdapUser($username, $password)
    {
        try {
            $passSha = base64_encode(pack('H*', sha1($password)));
            $attributeCompare = "uid";
            $linkIdentifier = self::getLdapLinkIdentifier();
            if ($linkIdentifier) {
                $baseDn = self::getLdapBaseDn($linkIdentifier, $username);
                ldap_set_option($linkIdentifier, LDAP_OPT_PROTOCOL_VERSION, 3);
                if (ldap_bind($linkIdentifier, $baseDn, $password)) {
                    return ldap_compare($linkIdentifier, $baseDn, $attributeCompare, $username);
                } else {
                    error_log("Unable to bind to LDAP server.");
                }

                ldap_close($linkIdentifier);
            }
        } catch (Exception $e) {
            error_log('Caught exception: ' . $e->getMessage());
        }

        return false;
    }

    private static function getLdapLinkIdentifier()
    {
        try {
            $ldapConfig = MessengerPortalConfig::get()->getYii2MessengerLdapConfig();
            $ldapHost = ((empty($ldapConfig['host'])) ? null : $ldapConfig['host']);
            $linkIdentifier = ldap_connect($ldapHost);
            if ($linkIdentifier) {
                return $linkIdentifier;
            } else {
                error_log("Unable to connect to LDAP server.");
            }
        } catch (Exception $e) {
            error_log('Caught exception: ' . $e->getMessage());
        }

        return null;
    }

    private static function getLdapBaseDn($linkIdentifier, $username)
    {
        $ldapConfig = MessengerPortalConfig::get()->getYii2MessengerLdapConfig();
        $distinguishedName = ((empty($ldapConfig['distinguishedName'])) ? null : $ldapConfig['distinguishedName']);
        $baseDn = self::getDn($linkIdentifier, $username, $distinguishedName);

        return $baseDn;
    }

    private static function getCn($dn)
    {
        preg_match('/[^,]*/', $dn, $matchs, PREG_OFFSET_CAPTURE, 3);

        return $matchs[0][0];
    }

    private static function getDn($ad, $cn, $basedn)
    {
        $result = ldap_search($ad, $basedn, "(cn={$cn})", ['dn']);
        if ($result) {
            $entries = ldap_get_entries($ad, $result);
            if ($entries['count'] > 0) {
                return $entries[0]['dn'];
            }
        }

        return '';
    }

    private static function checkGroupEx($ad, $userdn, $groupdn)
    {
        $result = ldap_read($ad, $groupdn, '(objectclass=*)', ['member']);
        if ($result) {
            $entries = ldap_get_entries($ad, $result);
            if ($entries['count'] <= 0) {
                return false;
            }
            if (empty($entries[0]['member'])) {
                return false;
            }
            foreach ($entries[0]['member'] as $member) {
                if ($member === $userdn) {
                    return true;
                }
            }
        }

        return false;
    }
}

################################################################################
#                                End of file                                   #
################################################################################
