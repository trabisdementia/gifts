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
class GiftsGiftsAdminController extends Xmf_Module_Controller_Abstract
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
    public $itemIdentifier = 'gift_id';
    public $itemName = 'gift_title';

    public function init()
    {
        xoops_load('xoopslists');
        xoops_load('xoopsformloader');
        xoops_load('xoopspagenav');
        $gifts = $this->gifts();
        $this->handler = $gifts->giftsHandler();
        $this->db = $gifts->db();
        $this->module = $gifts->getModule();
        $this->myts = $gifts->myts();
        $this->dirname = $this->module->getVar('dirname');
        $this->mid = $this->module->getVar('mid');
        $this->url = $gifts->url('admin/gifts.php');
        $this->setTemplatePath($gifts->path('templates/admin/gifts.html'));
        $this->setActions(array('delete', 'delete_confirm', 'save'));
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
            $session->set('gifts_start', $start);
        } elseif ($sessionStart = $session->get('gifts_start')) {
            $start = $sessionStart;
        }
        $count = $this->handler->getCount();
        $criteria = new CriteriaCompo();
        $criteria->setSort('gift_price');
        $criteria->setOrder('ASC');
        $criteria->setStart($start);
        $criteria->setLimit($start + $limit);
        $items = $this->handler->getObjects($criteria, false, false);
        $pagenav = new XoopsPageNav($count, $limit, $start, 'start', '');

        $view = $this->getViewRenderer();
        $view->assign('id', $this->itemIdentifier);
        $view->assign('url', $this->url);
        $view->assign('gifts_url', $this->gifts()->url());
        $view->assign('count', $count);
        $view->assign('items', $items);
        $view->assign('pagenav', $pagenav->renderNav());
        $this->display(array('form'));
    }

    public function deleteConfirmAction()
    {
        $this->header();
        $this->displayAdminMenu();
        $item_id = Xmf_Request::getInt($this->itemIdentifier);
        $obj = $this->handler->get($item_id);
        xoops_confirm(array(
                'action'              => 'delete',
                $this->itemIdentifier => $item_id,
                'ok'                  => 1
        ), $this->url, _GIFTS_AREYOUSURETODELETE . '<br />' . $obj->getVar($this->itemName));
        $this->footer();
    }

    public function deleteAction()
    {
        $id = Xmf_Request::getInt($this->itemIdentifier);
        if (!isset($_POST['ok'])) {
            $this->forward('delete_confirm');
        } else {
            if ($obj = $this->handler->get($id)) {
                if ($this->handler->delete($obj)) {
                    $this->redirect($this->url, 1, _GIFTS_SUCCESS);
                }
            }
        }
        $this->redirect($this->url, 1, _GIFTS_ERROR);
    }

    public function saveAction()
    {
        $id = Xmf_Request::getInt('gift_id');
        $obj = $id ? $this->handler->get($id) : $this->handler->create();
        $obj->setVars($_POST);
        if ($this->handler->insert($obj)) {
            $this->redirect($this->url, 1, _GIFTS_SUCCESS);
        }
        $this->redirect($this->url, 1, _GIFTS_ERROR);
    }

    public function form()
    {
        $id = Xmf_Request::getInt($this->itemIdentifier);
        $obj = $this->handler->get($id);
        if ($id > 0) {
            $formOptions = array(
                    'btnlabel'  => _GIFTS_MODIFY,
                    'formlabel' => _GIFTS_MODIFY,
            );
        } else {
            $formOptions = array(
                    'btnlabel'  => _GIFTS_ADD,
                    'formlabel' => _GIFTS_ADD,
            );
        }
        $sform = new XoopsThemeForm($formOptions['formlabel'], 'form', $this->url, 'post');

        $form = new Xmf_Object_Decorator_QuickForm($obj, 'gifts', 'gifts');
        $form->setForm($sform);
        //Title
        $form->addText('gift_title');
        //Description
        $form->addDhtmlTextArea('gift_description');
        //Price
        $form->addText('gift_price');
        //Image
        $imgtray = new XoopsFormElementTray(_GIFTS_IMAGE, '<br />');
        $imgpath = sprintf(_GIFTS_IMAGE_LOC, 'modules/' . $this->dirname . '/images/gifts/big/');
        $imageselect = new XoopsFormSelect($imgpath, 'gift_image_url', $obj->getVar('gift_image_url', 'e'));
        $topics_array = XoopsLists:: getImgListAsArray(GIFTS_ROOT_PATH . '/images/gifts/big/');
        foreach ($topics_array as $image) {
            $imageselect->addOption("$image", $image);
        }
        $uploadirectory = '/modules/' . $this->dirname . '/images/gifts/big';
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"gift_image_url\", \"" . $uploadirectory .
                               "\", \"\", \"" . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect, false);
        $imgtray->addElement(new XoopsFormLabel('',
                "<br /><img class='img-responsive' src='" . XOOPS_URL . "/" . $uploadirectory . "/" .
                $obj->getVar('gift_image_url', 'e') . "' name='image3' id='image3' alt='' />"));
        $sform->addElement($imgtray);

        //Hidden Elements
        $sform->addElement(new XoopsFormHidden('action', 'save'), false);
        $form->addHidden('gift_id');

        // Submit buttons
        $button_tray = new XoopsFormElementTray('', '');
        $submit_btn = new XoopsFormButton('', 'post', $formOptions['btnlabel'], 'submit');
        $button_tray->addElement($submit_btn);
        $sform->addElement($button_tray);
        $ret = $form->render();
        $view = $this->getViewRenderer();
        $view->assign('form', $ret);
    }
}
