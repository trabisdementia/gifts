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

class GiftsLogs extends XoopsObject
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->initVar("log_id", XOBJ_DTYPE_INT);
        $this->initVar('log_gid', XOBJ_DTYPE_INT, 0);
        $this->initVar('log_ruid', XOBJ_DTYPE_INT, 0);
        $this->initVar("log_suid", XOBJ_DTYPE_INT, 0);
        $this->initVar("log_message", XOBJ_DTYPE_TXTAREA, '', false, 255);
        $this->initVar("log_created", XOBJ_DTYPE_INT, 0);
    }
}

class GiftsLogsHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param XoopsDatabase $db
     */
    public function __construct($db)
    {
        parent::__construct($db, "gifts_logs", 'GiftsLogs', "log_id", "log_gid");
    }

    /**
     * @return Xmf_Module_Helper_Abstract|Gifts
     */
    public function gifts()
    {
        return Gifts::getInstance();
    }

    /**
     * @param $object XoopsObject
     * @param $field_name
     * @param $field_value
     *
     * @return mixed
     */
    public function updateByField(&$object, $field_name, $field_value)
    {
        $object->unsetNew();
        $object->setVar($field_name, $field_value);
        return $this->insert($object);
    }


    /**
     * @param int $uid
     *
     * @return mixed
     */
    public function getCountBySender($uid = 0)
    {
        $sql = 'SELECT count(*) as cpt FROM ' . $this->table;
        $sql .= ' WHERE log_suid = ' . intval($uid);
        $sql .= ' ORDER BY log_created DESC';
        $result = $this->db->query($sql);
        $myrow = $this->db->fetchArray($result);
        return $myrow['cpt'];
    }

    /**
     * @param int $uid
     *
     * @return mixed
     */
    public function getCountByReceiver($uid = 0)
    {
        $sql = 'SELECT count(*) as cpt FROM ' . $this->table;
        $sql .= ' WHERE log_ruid = ' . intval($uid);
        $sql .= ' ORDER BY log_created DESC';
        $result = $this->db->query($sql);
        $myrow = $this->db->fetchArray($result);
        return $myrow['cpt'];
    }

    /**
     * @param int $gid
     *
     * @return mixed
     */
    public function getCountByGift($gid = 0)
    {
        $sql = 'SELECT count(*) as cpt FROM ' . $this->table;
        $sql .= ' WHERE log_gid = ' . intval($gid);
        $sql .= ' ORDER BY log_created DESC';
        $result = $this->db->query($sql);
        $myrow = $this->db->fetchArray($result);
        return $myrow['cpt'];
    }

    /**
     * @param int  $uid
     * @param int  $limit
     * @param int  $start
     * @param bool $asobject
     *
     * @return array
     */
    public function getAllBySender($uid = 0, $limit = 0, $start = 0, $asobject = false)
    {
        $ret = array();
        $uid = intval($uid);
        $sql = "SELECT * from " . $this->table . " WHERE log_suid = " . $uid;
        $result = $this->db->query($sql, intval($limit), intval($start));
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asobject) {
                $obj = $this->create();
                $obj->assignVars($myrow);
                $ret[] = $obj;
            } else {
                $ret[] = $myrow['log_id'];
            }
        }
        return $ret;
    }

       /**
     * @param int  $uid
     * @param int  $limit
     * @param int  $start
     * @param bool $asobject
     *
     * @return array
     */
    public function getAllByReceiver($uid = 0, $limit = 0, $start = 0, $asobject = false)
    {
        $ret = array();
        $uid = intval($uid);
        $sql = "SELECT * from " . $this->table . " WHERE log_ruid = " . $uid;
        $result = $this->db->query($sql, intval($limit), intval($start));
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asobject) {
                $obj = $this->create();
                $obj->assignVars($myrow);
                $ret[] = $obj;
            } else {
                $ret[] = $myrow['log_id'];
            }
        }
        return $ret;
    }

    /**
     * @param int $uid
     * @param int $log_gid
     *
     * @return bool
     */
    public function verify($uid, $log_gid)
    {
        $log_gid = intval($log_gid);
        $uid = intval($uid);
        $sql = "SELECT COUNT(*) from " . $this->table . " WHERE log_gid = " . $log_gid . " AND uid = " . $uid;
        $result = $this->db->query($sql);
        list($count) = $this->db->fetchRow($result);
        return $count ? true : false;
    }

    /**
     * @return array
     */
    public function getListReceivers()
    {
        $ret = array();
        $sql = "SELECT distinct(log_ruid) as uid FROM " . $this->table;
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] = $myrow['uid'];
        }
        return $ret;
    }

    /**
     * @return array
     */
    public function getListReceiversCount()
    {
        $ret = array();
        $sql = "SELECT count('log_gid') as cpt, log_ruid FROM " .
               $this->table . " GROUP BY log_ruid ORDER BY cpt DESC";
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[$myrow['log_ruid']] = $myrow['cpt'];
        }
        return $ret;
    }

    /**
     * @param int $days
     * @param int $limit
     * @param int $uid
     * @param int $favmin
     *
     * @return array
     */
    /*public function getRecentFavoriteStoriesByLimit($days = 30, $limit = 10, $uid = 0, $favmin = 2)
    {
        $ret= array();
        $days = intval($days);
        $uid = intval($uid);
        $limit = intval($limit);
        $favmin = intval($favmin);
        $storiesHandler = $this->news()->stories();
        $storiesTable = $this->db->prefix('publisher_stories');
        $topicsTable = $this->db->prefix('publisher_topics');
        $usersTable = $this->db->prefix('users');
        $time_criteria = time() - ($days * 24 * 60 * 60);
        $sql = "SELECT s.storyid, s.published, s.bodytext, s.title, s.favorites, s.authornote, t.topic_title, u.uname, u.uid";
        $sql .= " FROM {$storiesTable} s";
        $sql .= " INNER JOIN {$topicsTable} t";
        $sql .= " ON s.topicid=t.topic_id";
        $sql .= " INNER JOIN {$usersTable} u";
        $sql .= " ON s.uid=u.uid";
        $sql .= " WHERE s.status=1";
        $sql .= " AND s.published > {$time_criteria}";
        $sql .= " AND s.favorites >= {$favmin}";
        if ($uid) {
            $sql .= " AND u.uid = $uid";
        }
        $sql .= " ORDER BY s.published DESC";
        $result = $this->db->query($sql, $limit, 0);
        while ($row = $this->db->fetchArray($result)) {
            $obj = $storiesHandler->create();
            $obj->assignVars($row);
            $ret[] = $obj;
        }
        return $ret;
    }*/

    /**
     * @param $uid
     * @param $itemid
     *
     * @return bool|GiftsLogs
     */
    public function getObjectBySenderGift($uid, $itemid) {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('log_suid', intval($uid)));
        $criteria->add(new Criteria('log_gid', intval($itemid)));
        if ($objects = $this->getObjects($criteria)) {
            return $objects[0];
        }
        return false;
    }

    /**
     * @param int $uid
     * @param int $itemid
     *
     * @return int
     */
    public function getCountBySenderGift($uid, $itemid) {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('log_suid', intval($uid)));
        $criteria->add(new Criteria('log_gid', intval($itemid)));
        return $this->getCount($criteria);
    }

}
