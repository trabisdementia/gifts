<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com
defined('XOOPS_ROOT_PATH') or die('XOOPS root path not defined');

$modversion['dirname'] = basename(dirname(__FILE__));
$modversion['name'] = ucfirst(basename(dirname(__FILE__)));
$modversion['version'] = '0.2.0';
$modversion['description'] = '';
$modversion['author'] = "Xuups";
$modversion['credits'] = "Trabis(www.xuups.com)";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/gifts.png";


// About stuff
$modversion['module_status'] = "Beta";
$modversion['status'] = "Beta";
$modversion['release_date'] = "10/02/2017";

$modversion['developer_lead'] = "trabis";
$modversion['developer_website_url'] = "https://www.xuups.com";
$modversion['developer_website_name'] = "Xuups";
$modversion['developer_email'] = "lusopoemas@gmail.com";

$modversion['people']['developers'][] = "trabis";

$modversion['demo_site_url'] = "https://www.xuups.com";
$modversion['demo_site_name'] = "XOOPS User Utilities";
$modversion['support_site_url'] = "https://www.xuups.com/modules/newbb";
$modversion['support_site_name'] = "Xuups Support Forums";

$modversion['min_xoops'] = "2.4.5";
$modversion['min_php'] = "5.2";

//Tables
$modversion['sqlfile']['mysql'] = "sql/tables.sql";
$i = 0;
$modversion['tables'][$i] = "gifts_logs";
$i++;
$modversion['tables'][$i] = "gifts_gifts";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu
$modversion['hasMain'] = 1;

// Templates
$i = 0;
$i++;
$modversion['templates'][$i]['file'] = "gifts_index.html";
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = "gifts_offer.html";
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = "gifts_message.html";
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = "gifts_mygifts.html";
$modversion['templates'][$i]['description'] = '';

// Configs
$i = 0;
$i++;
$modversion['config'][$i]['name'] = 'get_credits_url';
$modversion['config'][$i]['title'] = '_GIFTS_CREDITS_URL';
$modversion['config'][$i]['description'] = '_GIFTS_CREDITS_URL_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = "";