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
class GiftsMygiftsController extends Xmf_Module_Controller_Abstract
{
    /**
     * @var Gifts
     */
    public $gifts;

    /**
     * @var XoopsUser
     */
    public $user;
    /**
     * @var int
     */
    public $uid;

    /**
     * @var int
     */
    public $limit = 10;

    /**
     * @var Xmf_Request
     */
    public $router;

    public function init()
    {
        global $xoopsUser;
        if (!$xoopsUser) {
            $this->redirect(XOOPS_URL, 2, _GIFTS_ERROR);
        }

        xoops_load('xoopspagenav');
        xoops_load('xoopsuserutility');

        $this->user = $xoopsUser;
        $this->uid = $xoopsUser->getVar('uid');
        $this->gifts = Gifts::getInstance();
        $this->setTemplatePath('db:gifts_mygifts.html');
        $this->setActions(array(
                'index', 'received', 'sent'
        ));

        $this->router = Xmf_Router::getInstance();

        $view = $this->getViewRenderer();
        $view->assign('gifts_received', $this->router->buildUrl('gifts-mygifts-received', array('action' => 'received')));
        $view->assign('gifts_sent', $this->router->buildUrl('gifts-mygifts-sent', array('action' => 'sent')));
        $view->assign('gifts_url', $this->router->buildUrl('gifts-index'));
    }

    public function indexAction()
    {
        $this->forward('received');
    }

    public function receivedAction()
    {
        $start = Xmf_Request::getInt('start');
        $lHandler = $this->gifts->logsHandler();
        $gHandler = $this->gifts->giftsHandler();

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('log_ruid', $this->uid));
        $count = $lHandler->getCount($criteria);

        $criteria->setSort('log_id');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($this->limit);
        $items = $lHandler->getObjects($criteria, false, false);
        $pagenav = new XoopsPageNav($count, $this->limit, $start, 'start', 'action=' . $this->getAction());
        foreach ($items as $i => $item) {
            $gift = $gHandler->get($items[$i]['log_gid']);
            $suser_link = XoopsUserUtility::getUnameFromId($items[$i]['log_suid'], false, true);
            $items[$i]['log_image_url'] = $gift->getVar('gift_image_url');
            $items[$i]['log_title'] = $gift->getVar('gift_title');
            $items[$i]['log_sname'] = $suser_link;
            $items[$i]['log_created'] = formatTimestamp($items[$i]['log_created']);
        }
        $view = $this->getViewRenderer();
        $view->assign('count', $count);
        $view->assign('items', $items);
        $view->assign('pagenav', $pagenav->renderNav());
        $view->assign('action', $this->getAction());
        $this->display();
    }

    public function sentAction()
    {
        $start = Xmf_Request::getInt('start');
        $lHandler = $this->gifts->logsHandler();
        $gHandler = $this->gifts->giftsHandler();

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('log_suid', $this->uid));
        $count = $lHandler->getCount($criteria);

        $criteria->setSort('log_id');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($this->limit);
        $items = $lHandler->getObjects($criteria, false, false);
        $pagenav = new XoopsPageNav($count, $this->limit, $start, 'start', 'action=' . $this->getAction());
        foreach ($items as $i => $item) {
            $gift = $gHandler->get($items[$i]['log_gid']);
            $ruser_link = XoopsUserUtility::getUnameFromId($items[$i]['log_ruid'], false, true);
            $items[$i]['log_image_url'] = $gift->getVar('gift_image_url');
            $items[$i]['log_title'] = $gift->getVar('gift_title');
            $items[$i]['log_rname'] = $ruser_link;
            $items[$i]['log_created'] = formatTimestamp($items[$i]['log_created']);
        }
        $view = $this->getViewRenderer();
        $view->assign('count', $count);
        $view->assign('items', $items);
        $view->assign('pagenav', $pagenav->renderNav());
        $view->assign('action', $this->getAction());
        $this->display();
    }
}
