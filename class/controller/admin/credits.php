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
class GiftsCreditsAdminController extends Xmf_Module_Controller_Abstract
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
     * @var XoopsMemberHandler
     */
    public $mHandler;

    /**
     * @var GiftsCreditsHandler
     */
    public $cHandler;

    public function init()
    {
        xoops_load('xoopslists');
        xoops_load('xoopsformloader');
        xoops_load('xoopspagenav');
        xoops_load('xoopsuserutility');
        $gifts = $this->gifts();
        $this->mHandler = $gifts->xmf()->getHandlerMember();
        $this->cHandler = $gifts->creditsHandler();
        $this->db = $gifts->db();
        $this->module = $gifts->getModule();
        $this->myts = $gifts->myts();
        $this->dirname = $this->module->getVar('dirname');
        $this->mid = $this->module->getVar('mid');
        $this->url = $gifts->url('admin/credits.php');
        $this->setTemplatePath($gifts->path('templates/admin/credits.html'));
        $this->setActions(array('index', 'confirm', 'save'));
    }

    /**
     * @return Gifts|Xmf_Module_Helper_Abstract
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
            $session->set('credits_start', $start);
        } elseif ($sessionStart = $session->get('credits_start')) {
            $start = $sessionStart;
        }
        $count = $this->cHandler->getCount();
        $criteria = new CriteriaCompo();
        $criteria->setSort('credit_credits');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $top_users = $this->cHandler->getAll($criteria, null, false, false);
        $pagenav = new XoopsPageNav($count, $limit, $start, 'start', '');
        $items = array();
        /* @var $user XoopsUser */
        foreach ($top_users as $user) {
            if ($user = $this->mHandler->getUser($user['credit_uid'])) {
                $items[] = array(
                        'link' => XoopsUserUtility::getUnameFromId($user->getVar('uid'),
                                false, true),
                        'credits' => $this->cHandler->getCreditsByUser($user->getVar('uid')),
                        'uid' => $user->getVar('uid')
                );
            } else {
                //Was this user 'deleted'? Check his wallet.
            }
        }
        $view = $this->getViewRenderer();
        $view->assign('url', $this->url);
        $view->assign('count', $count);
        $view->assign('items', $items);
        $view->assign('pagenav', $pagenav->renderNav());
        $this->form(Xmf_Request::getInt('uid'));
        $this->display();
    }

    public function form($uid = 0, $credits = 0)
    {
        $form = new XoopsThemeForm(_GIFTS_CREDITS_FORM, "form", "credits.php", "post", true);
        $uid = new XoopsFormText(_GIFTS_UID, "uid", 45, 255, $uid);
        $credits = new XoopsFormText(_GIFTS_CREDITS_TO_ADD, "credits", 45, 255, $credits);
        $submit_btn = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
        $form->addElement($uid);
        $form->addElement($credits);
        $form->addElement($submit_btn);
        $form->addElement(new XoopsFormHidden('action', 'confirm'), false);
        $ret = $form->render();
        $view = $this->getViewRenderer();
        $view->assign('form', $ret);
    }

    public function confirmAction()
    {
        $uid = Xmf_Request::getInt('uid');
        $credits = Xmf_Request::getInt('credits');
        $user = $this->mHandler->getUser($uid);
        if (is_object($user)) {
            $uid = $user->getVar('uid');
            $uname = $user->getVar('uname');
            $old_credits = $this->cHandler->getCreditsByUser($user->getVar('uid'));
            $new_credits = $old_credits + $credits;
            $this->header();
            xoops_confirm(array(
                    'action'  => 'save',
                    'uid'     => $uid,
                    'credits' => $credits
            ), 'credits.php', sprintf(_GIFTS_ADD_CREDITS, $credits, $uname, $old_credits, $new_credits));
            $this->footer();
            exit();
        }
        $this->redirect('credits.php', 2, _GIFTS_ERROR);
    }

    public function saveAction()
    {
        $uid = Xmf_Request::getInt('uid');
        $credits = Xmf_Request::getInt('credits');
        $user = $this->mHandler->getUser($uid);
        if (is_object($user) && $this->gifts()->xmf()->security()->check()) {
            $this->cHandler->creditUser($uid, $credits);
            $this->redirect('credits.php', 1, _GIFTS_SUCCESS);
        }
        $this->redirect('credits.php', 2, _GIFTS_ERROR);
    }
}
