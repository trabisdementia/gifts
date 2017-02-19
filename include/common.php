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
 * Publisher class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Include
 * @subpackage      Functions
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: common.php 8065 2011-11-06 02:02:32Z beckmi $
 */

include_once dirname(dirname(dirname(dirname(__FILE__)))) . '/mainfile.php';

//XMF inclusion
if (!xoops_isActiveModule('xmf')) {
    if (file_exists($file = dirname(dirname(dirname(__FILE__))) . '/xmf/include/bootstrap.php')) {
        include_once $file;
        echo 'Please install or reactivate XMF module';
    } else {
        redirect_header(XOOPS_URL, 5, 'Please install XMF module');
    }
}

define("GIFTS_DIRNAME", basename(dirname(dirname(__FILE__))));
define("GIFTS_URL", XOOPS_URL . '/modules/' . GIFTS_DIRNAME);
define("GIFTS_IMAGES_URL", GIFTS_URL . '/images');
define("GIFTS_ADMIN_URL", GIFTS_URL . '/admin');
define("GIFTS_UPLOADS_URL", XOOPS_URL . '/uploads/' . GIFTS_DIRNAME);

define("GIFTS_ROOT_PATH", XOOPS_ROOT_PATH . '/modules/' . GIFTS_DIRNAME);
define("GIFTS_UPLOADS_PATH", XOOPS_ROOT_PATH . '/uploads/' . GIFTS_DIRNAME);

define("GIFTS_CSS_VERSION", rand());

xoops_loadLanguage('common', GIFTS_DIRNAME);

include_once GIFTS_ROOT_PATH . '/class/helper.php';
Gifts::getInstance()->setDebug(true);