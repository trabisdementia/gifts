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

include_once dirname(dirname(dirname(__FILE__))) . '/include/common.php';

class GiftsIndexController extends Xmf_Module_Controller_Abstract
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
     * @var Xmf_Router
     */
    public $router;


    public function init()
    {
        global $xoopsUser;
        if (!$xoopsUser) {
            $this->redirect(XOOPS_URL, 2, _GIFTS_ERROR);
        }

        $this->user = $xoopsUser;
        $this->uid = $xoopsUser->getVar('uid');
        $this->gifts = Gifts::getInstance();
        $this->setTemplatePath('db:gifts_index.html');
        $this->setActions(array(
                'index', 'offer', 'send'
        ));
        $this->router = Xmf_Router::getInstance();

    }

    public function indexAction()
    {
        $uid = Xmf_Request::getInt('uid');
        $view = $this->getViewRenderer();
        $handler = $this->gifts->giftsHandler();
        $criteria = new CriteriaCompo();
        $criteria->setOrder('ASC');
        $criteria->setSort('gift_price');
        $gifts = $handler->getAll($criteria, null, false);
        $view->assign('items', $gifts);
        $view->assign('count', count($gifts));
        $view->assign('uid', $uid);
        $view->assign('description', _GIFTS_INDEX_DESCRIPTION);

        $this->assignGlobalScripts();
        $this->display();
    }

    public function offerAction()
    {

        $this->setTemplatePath('db:gifts_offer.html');
        $view = $this->getViewRenderer();

        $handler = $this->gifts->giftsHandler();

        $gift_id = Xmf_Request::getInt('gift_id');
        $uid = Xmf_Request::getInt('uid');
        if (!$gift = $handler->get($gift_id)) {
            $this->redirect($this->router->buildUrl('gifts-index'), 2, _GIFTS_ERROR);
        }
        $values = $gift->getValues();
        $credits = $this->gifts->creditsHandler()->getCreditsByUser($this->uid);

        $view->assign('uid', $uid);
        $view->assign('item', $values);
        $view->assign('credits', $credits);
        $view->assign('form', $this->form($gift));

        $this->assignGlobalScripts();
        $this->display();
    }

    public function sendAction()
    {
        $id = Xmf_Request::getInt('gift_id');
        $ruid = Xmf_Request::getInt('gift_ruid');
        $message = Xmf_Request::getText('gift_message');
        $suid = $this->uid;

        $giftHandler = $this->gifts->giftsHandler();
        //check if Gift exists
        if (!$gift = $giftHandler->get($id)) {
            $this->redirect($this->router->buildUrl('gifts-index'), 2, _GIFTS_ERROR);
        }
        $price = $gift->getVar('gift_price');
        //charge user and check if it fails
        if (!$this->gifts->creditsHandler()->chargeUser($suid, $price)) {
            $this->redirect($this->router->buildUrl('gifts-index'), 2, _GIFTS_ERROR);
        }
        $credit = intval($price / 2);
        $this->gifts->creditsHandler()->creditUser($ruid, $credit);
        //Add to log
        $logHandler = $this->gifts->logsHandler();
        $log = $logHandler->create();
        $log->setVar('log_gid', $id);
        $log->setVar('log_ruid', $ruid);
        $log->setVar('log_suid', $suid);
        $log->setVar('log_message', $message);
        $log->setVar('log_created', time());
        $logHandler->insert($log);
        //Pm User
        /* @var $pm_handler XoopsPersistableObjectHandler */
        $tpl = new XoopsTpl();
        $tpl->assign('gifts_url', $this->router->buildUrl('gifts-index'));
        $tpl->assign('message', $message);
        $tpl->assign('item', $gift->getValues());
        $tpl->assign('uid', $suid);
        xoops_load('xoopsuserutility');
        $suser_link = XoopsUserUtility::getUnameFromId($suid, false, true);
        $tpl->assign('suser_link', sprintf(_GIFTS_VISIT_PROFILE, $suser_link));

        $message = $tpl->fetch('db:gifts_message.html');

        $pm_handler = xoops_gethandler('privmessage');
        $pm = $pm_handler->create();
        $pm->setVar("subject", sprintf(_GIFTS_PM_GIFT_SUBJECT, $this->user->getVar('uname')));
        $pm->setVar("msg_text", $message);
        $pm->setVar("to_userid", $ruid);
        $pm->setVar("from_userid", 1);
        $pm_handler->insert($pm, true);

        $this->redirect($this->router->buildUrl('gifts-index'), 2, _GIFTS_YOUR_GIFT_SENT);
    }

    /**
     * @param GiftsGifts $gift
     *
     * @return string
     */
    public function form($gift)
    {
        xoops_load('xoopsformloader');
        $sform = new XoopsThemeForm(_GIFTS_OFFER, 'form', $this->router->buildUrl('gifts-index'), 'post', true);

        $uid = Xmf_Request::getInt('uid', $this->uid);
        $user = new XoopsFormSelectUser(_GIFTS_CHOOSE_USER, 'gift_ruid', false, $uid);
        $sform->addElement($user, true);
        $message = new XoopsFormTextArea(_GIFTS_CHOOSE_MESSAGE, 'gift_message', $gift->getVar('gift_description'), 3, 50);
        $sform->addElement($message, false);
        //Hidden Elements
        $sform->addElement(new XoopsFormHidden('action', 'send'), false);
        $sform->addElement(new XoopsFormHidden('gift_id', $gift->getVar('gift_id')), false);

        // Submit buttons
        $button_tray = new XoopsFormElementTray('', '');
        $submit_btn = new XoopsFormButton('', 'post', _SEND, 'submit');
        $button_tray->addElement($submit_btn);
        $sform->addElement($button_tray);
        return $sform->render();
    }


    public function assignGlobalScripts()
    {
        $view = $this->getViewRenderer();
        $credits = $this->gifts->creditsHandler()->getCreditsByUser($this->user->getVar('uid'));
        $view->assign('credits', $credits);
        $view->assign('youhave', sprintf(_GIFTS_YOUHAVEXCREDITS, $credits));
        $view->assign('get_credits_url', $this->gifts->getConfig('get_credits_url'));
        $view->assign('gifts_received', $this->router->buildUrl('gifts-mygifts-received', array('action' => 'received')));
        $view->assign('gifts_sent', $this->router->buildUrl('gifts-mygifts-sent', array('action' => 'sent')));
        $view->assign('gifts_url', $this->router->buildUrl('gifts-index'));
        $view->assign('gifts_offer', $this->router->buildUrl('gifts-index-offer', array('action' => 'offer')));
    }
}
