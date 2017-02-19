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

class GiftsGifts extends XoopsObject
{

    /**
     * constructor
     */
    public function __construct()
    {
        $this->initVar("gift_id", XOBJ_DTYPE_INT, null, false);
        $this->initVar("gift_title", XOBJ_DTYPE_TXTBOX, '', true, 255);
        $this->initVar("gift_description", XOBJ_DTYPE_TXTAREA, '', true, 255);
        $this->initVar("gift_image_url", XOBJ_DTYPE_TXTBOX, 'default.png', true, 255);
        $this->initVar("gift_price", XOBJ_DTYPE_INT, 0, true);

    }

    /**
     * Allows $this->getVar('var', 's') using $this->var('s')
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        $arg = isset($args[0]) ? $args[0] : null;
        return $this->getVar($method, $arg);
    }

    /**
     * @return Gifts|Xmf_Module_Helper_Abstract
     */
    public function gifts()
    {
        return Gifts::getInstance();
    }

}

class GiftsGiftsHandler extends XoopsPersistableObjectHandler
{

    /**
     * @param null|object $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'gifts_gifts', 'GiftsGifts', 'gift_id', 'gift_title');
    }

    /**
     * @return Gifts
     */
    public function gifts()
    {
        return Gifts::getInstance();
    }
}