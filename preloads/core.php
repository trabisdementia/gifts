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
 * @package         gifts
 * @since           0.1
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class GiftsCorePreload extends XoopsPreloadItem
{
    static function eventCoreIncludeCommonEnd()
    {
        $router = Xmf_Router::getInstance();
        $router->map('GET|POST', 'gifts', array('module' => 'gifts', 'controller'=>'index'), 'gifts-index');

        $router->map('GET|POST', 'gifts/index', array('module' => 'gifts', 'controller'=>'index'), '');
        $router->map('GET|POST', 'gifts/[index:action]/[i:uid]', array('module' => 'gifts', 'controller'=>'index'), 'gifts-index-uid');
        $router->map('GET|POST', 'gifts/[offer:action]', array('module' => 'gifts', 'controller'=>'index'), 'gifts-index-offer');
        $router->map('GET|POST', 'gifts/[offer:action]/[i:gift_id]/[i:uid]', array('module' => 'gifts', 'controller'=>'index'), 'gifts-index-offer-gift_id-uid');
        $router->map('GET|POST', 'gifts/mygifts/[received:action]', array('module' => 'gifts', 'controller'=>'mygifts'), 'gifts-mygifts-received');
        $router->map('GET|POST', 'gifts/mygifts/[sent:action]', array('module' => 'gifts', 'controller'=>'mygifts'), 'gifts-mygifts-sent');

    }
}