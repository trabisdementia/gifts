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
 * @version         $Id: $
 */

include_once dirname(dirname(__FILE__)) . '/include/common.php';

class GiftsCredits extends XoopsObject
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->initVar("credit_id", XOBJ_DTYPE_INT);
        $this->initVar('credit_uid', XOBJ_DTYPE_INT, 0);
        $this->initVar("credit_credits", XOBJ_DTYPE_INT, 0);
    }
}

class GiftsCreditsHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param XoopsDatabase $db
     */
    public function __construct($db)
    {
        parent::__construct($db, "gifts_credits", 'GiftsCredits', "credit_id",
                "credit_uid");
    }

    /**
     * @return Xmf_Module_Helper_Abstract|Gifts
     */
    public function gifts()
    {
        return Gifts::getInstance();
    }


    /**
     * @param int $uid
     *
     * @return int
     */
    public function getCreditsByUser($uid)
    {
        $criteria = new Criteria('credit_uid', intval($uid));
        if ($objects = $this->getObjects($criteria)) {
            return $objects[0]->getVar('credit_credits');
        }
        return 0;
    }

    /**
     * @param int $uid
     * @param int $credits
     * @return mixed
     */
    public function updateCreditsByUser($uid, $credits)
    {
        $criteria = new Criteria('credit_uid', intval($uid));
        $object = false;
        if ($objects = $this->getObjects($criteria)) {
            $object = $objects[0];
        }
        if (!$object) {
            $object = $this->create();
            $object->setVar('credit_uid', intval($uid));
        }
        $object->setVar('credit_credits', intval($credits));
        return $this->insert($object);
    }


    /**
     * @param int $uid
     * @param int $credits
     *
     * @return bool
     */
    public function chargeUser($uid, $credits)
    {
        $uid = intval($uid);
        $credits = intval($credits);
        $user_credits = $this->getCreditsByUser($uid);
        if ($user_credits >= $credits) {
            $this->updateCreditsByUser($uid, $user_credits - $credits);
            return true;
        }
        return false;
    }

    /**
     * @param int $uid
     * @param int $credits
     *
     * @return bool
     */
    public function creditUser($uid, $credits)
    {
        $uid = intval($uid);
        $credits = intval($credits);
        $user_credits = $this->getCreditsByUser($uid);
        $this->updateCreditsByUser($uid, $user_credits + $credits);
        return true;
    }

}
