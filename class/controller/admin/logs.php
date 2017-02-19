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
class GiftsLogsAdminController extends Xmf_Module_Controller_Abstract
{
    /**
     * @var XoopsDatabase
     */
    public $db;
    /**
     * @var array
     */
    public $config;
    /**
     * @var false|XoopsModule
     */
    public $module;
    /**
     * @var MyTextSanitizer
     */
    public $myts;
    /**
     * @var int
     */
    public $mid;
    /**
     * @var string
     */
    public $dirname;
    /**
     * @var string
     */
    public $url;
    /**
     * @var array
     */
    public $moduleConfigs;
    /**
     * @var GiftsGiftsHandler
     */
    public $handler;
    public $itemIdentifier = 'log_id';
    public $itemName = 'log_gid';

    public function init()
    {
        xoops_load('xoopslists');
        xoops_load('xoopsformloader');
        xoops_load('xoopspagenav');
        xoops_load('xoopsuserutility');

        $gifts = $this->gifts();
        $this->handler = $gifts->logsHandler();
        $this->db = $gifts->db();
        $this->module = $gifts->getModule();
        $this->myts = $gifts->myts();
        $this->dirname = $this->module->getVar('dirname');
        $this->mid = $this->module->getVar('mid');
        $this->url = $gifts->url('admin/logs.php');
        $this->setTemplatePath($gifts->path('templates/admin/logs.html'));
        $this->setActions(array('index'));
    }

    /**
     * @return Gifts
     */
    public function gifts()
    {
        return Gifts::getInstance();
    }

    public function indexAction()
    {
        /* @var $session Xmf_Module_Decorator_Session */
        $session = $this->gifts()->getDecorator('session');
        $start = Xmf_Request::getInt('start');
        $limit = 20;
        if (isset($_GET['start'])) {
            $session->set('logs_start', $start);
        } elseif ($sessionStart = $session->get('logs_start')) {
            $start = $sessionStart;
        }
        $gHandler = $this->gifts()->giftsHandler();
        $count = $this->handler->getCount();
        $criteria = new CriteriaCompo();
        $criteria->setSort('log_id');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $items = $this->handler->getObjects($criteria, false, false);
        $pagenav = new XoopsPageNav($count, $limit, $start, 'start', '');
        foreach ($items as $i => $item) {
            $gift = $gHandler->get($items[$i]['log_gid']);
            $suser_link = XoopsUserUtility::getUnameFromId($items[$i]['log_suid'], false, true);
            $ruser_link = XoopsUserUtility::getUnameFromId($items[$i]['log_ruid'], false, true);
            $items[$i]['log_image_url'] = $gift->getVar('gift_image_url');
            $items[$i]['log_sname'] = $suser_link;
            $items[$i]['log_rname'] = $ruser_link;
            $items[$i]['log_created'] = formatTimestamp($items[$i]['log_created']);
        }
        $view = $this->getViewRenderer();
        $view->assign('id', $this->itemIdentifier);
        $view->assign('url', $this->url);
        $view->assign('gifts_url', $this->gifts()->url());
        $view->assign('count', $count);
        $view->assign('items', $items);
        $view->assign('pagenav', $pagenav->renderNav());
        $this->display();
    }
}
