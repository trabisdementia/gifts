<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: handler.php 8065 2011-11-06 02:02:32Z beckmi $
 */
include_once dirname(dirname(__FILE__)) . '/include/common.php';

class Gifts extends Xmf_Module_Helper_Abstract
{
    /**
     * Wrapper, allows good code inspection
     *
     * @return GiftsLogsHandler
     */
    public function logsHandler()
    {
        return $this->getHandler('logs');
    }

    /**
     * Wrapper, allows good code inspection
     *
     * @return GiftsGiftsHandler
     */
    public function giftsHandler()
    {
        return $this->getHandler('gifts');
    }

    /**
     * Wrapper, allows good code inspection
     *
     * @return GiftsCreditsHandler
     */
    public function creditsHandler()
    {
        return $this->getHandler('credits');
    }

    /**
     * @param int $uid
     *
     * @return bool|object
     */
    public function getUser($uid)
    {
        $mHandler = $this->xmf()->getHandlerMember();
        $uid = intval($uid);
        if ($user = $mHandler->getUser($uid)) {
            return $user;
        }
        return false;
    }

}