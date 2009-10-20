<?php
define('ALM_MAX','maximize');
define('ALM_REC_LB','records');
define('ALM_DEL_LB','Delete');
define('ALM_VIEW_LB','View');
define('ALM_EDIT_LB','Edit');
define('ALM_CAN_LB','Cancel');
define('ALM_SAVE_LB','Save');
define('ALM_NEXT_LB','Next');
define('ALM_PREV_LB','Previous');
define('ALM_OPT_LB','Options');
define('ALM_ADD_LB','Add');
define('ALM_AL_MSG_DEL','Are you sure to want to delete this record?');
define('ALM_ADMIN_TITLE','Management');
define('ALM_WCOME','Welcome! You are connected as:');
define('ALM_SEARCH_LB','Search');
define('ALM_RESET_LB','Clear');
define('ALM_NODATA','No Data');
define('ALM_SHOWALL','Show All');
if(!defined('ALM_USERNAME')) define('ALM_USERNAME','Username');
if(!defined('ALM_PASSWORD')) define('ALM_PASSWORD','Password');
if(!defined('ALM_PASS_ERROR')) define('ALM_PASS_ERROR','Wrong username and/or password');
if(!defined('ALM_NO_COOKIE')) define('ALM_NO_COOKIE','No session support. You must enable cookies support before continuing.');
if(!defined('ALM_LOGIN')) define('ALM_LOGIN','Login');

# Set locale, for dates
setlocale(LC_TIME, 'en_US.UTF-8');
