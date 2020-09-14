<?php
/**********************************************************************************
* BBSCoin: a new cryptocurrency built for forums                                  *
***********************************************************************************
* This program is free software; you may redistribute it and/or modify it under   *
* the terms of the provided license as published by Simple Machines LLC.          *
*                                                                                 *
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
*                                                                                 *
* See the "license.txt" file for details of the Simple Machines license.          *
* The latest version of the license can always be found at                        *
* http://www.simplemachines.org.                                                  *
**********************************************************************************/

// If file is not called by SMF, don't let them get anywhere!
if (!defined('SMF'))
	die('Hacking attempt...');

function BBSCoin()
{
	global $context, $modSettings, $scripturl, $smcFunc;
	global $txt, $item_info, $boardurl, $sourcedir, $user_info;
	
	// Various things we need
	include_once($sourcedir . '/Subs-Post.php');       // Sending PM's 
	include_once($sourcedir . '/Subs-Auth.php');       // 'Find Members' stuff
	
	// During testing, caching was causing many problems. So, we try to disable the caching here.
	header("Expires: Fri, 1 Jun 1990 00:00:00 GMT"); // My birthday ;)
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Pragma: no-cache");

    require_once $sourcedir."/bbscoin/bbscoinapi.php";
    require_once $sourcedir."/bbscoin/bbscoinapi_partner.php";

    $_SERVER['bbscoin_paymentid'] = hash('sha256', $_SERVER['HTTP_HOST'].$context['user']['id']);

    if ($modSettings['bbscoinApiMode'] == 1) {
        BBSCoinApiWebWallet::setSiteInfo($modSettings['bbscoinSiteId'], $modSettings['bbscoinSiteKey'], $modSettings['bbscoinNoSecure']);
        require_once $sourcedir."/bbscoin/webapi.inc.php";
    } elseif ($modSettings['bbscoinApiMode'] == 2) {
        BBSCoinApiWebWallet::setSiteInfo($modSettings['bbscoinSiteId'], $modSettings['bbscoinSiteKey'], $modSettings['bbscoinNoSecure']);
        BBSCoinApiWebWallet::recvCallback();
        require_once $sourcedir."/bbscoin/webwallet.inc.php";
    } else {
        require_once $sourcedir."/bbscoin/walletd.inc.php";
    }
}

function bbsCoinSendPM($subject, $message, $to) {
	// Who is sending the IM
	$pmfrom = array(
		'id' => 1,
		'name' => 'admin',
		'username' => 'admin'
	);

	// Who is receiving the IM		
	$pmto = array(
		'to' => array($to),
		'bcc' => array()
	);
	// Send the PM
	return sendpm($pmto, $subject, $message, 0, $pmfrom);
}

function bbsCoinFormatMoney($money)
{
	global $modSettings;

	// Cast to float
	$money = (float) $money;
	// Return amount with prefix and suffix added
	return $modSettings['shopCurrencyPrefix'] . $money . $modSettings['shopCurrencySuffix'];
}
