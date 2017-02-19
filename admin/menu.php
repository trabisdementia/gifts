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
 * @package         myinviter
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: menu.php 8065 2011-11-06 02:02:32Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

$i = 0;
$adminmenu[$i]['title'] = _GIFTS_ADMENU_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"] = '../xmf/images/icons/32/home.png';
$i++;
$adminmenu[$i]['title'] = _GIFTS_ADMENU_GIFTS;
$adminmenu[$i]['link'] = "admin/gifts.php";
$adminmenu[$i]["icon"] = '../xmf/images/icons/32/category.png';
$i++;
$adminmenu[$i]['title'] = _GIFTS_ADMENU_LOGS;
$adminmenu[$i]['link'] = "admin/logs.php";
$adminmenu[$i]["icon"] = '../xmf/images/icons/32/content.png';
$i++;
$adminmenu[$i]['title'] = _GIFTS_ADMENU_CREDITS;
$adminmenu[$i]['link'] = "admin/credits.php";
$adminmenu[$i]["icon"] = '../xmf/images/icons/32/cash_stack.png';