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


// If the file isn't called by SMF, it's bad!
if (!defined('SMF'))
	die('Hacking attempt...');

// During testing, caching was causing many problems. So, we try to disable the caching here
header('Expires: Fri, 1 Jun 1990 00:00:00 GMT'); // My birthday ;)
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: no-cache');

// Load the language file
loadLanguage('BBSCoin');
// Check if they're allowed here
isAllowedTo('bbscoin_admin');

function BBSCoinGeneral()
{
	global $smcFunc, $context, $db_prefix, $modSettings, $txt;

	// We haven't saved yet (this is for the 'Settings Saved' message' on the admin page)
	$context['bbscoin_saved'] = false;

	// If they've pressed the 'Save' button
	if (isset($_GET['save']))
	{
		// Put all the settings into an array, to save
		$newSettings = array(
			'bbscoinPayRatio' => (string)$_POST['bbscoinPayRatio'],
			'bbscoinPayToCoinRatio' => (string)$_POST['bbscoinPayToCoinRatio'],
			'bbscoinPayToBbscoin' => (string)$_POST['bbscoinPayToBbscoin'],
			'bbscoinWalletAddress' => (string)$_POST['bbscoinWalletAddress'],
			'bbscoinWalletd' => (string)$_POST['bbscoinWalletd'],
			'bbscoinConfirmedBlocks' => (string)$_POST['bbscoinConfirmedBlocks'],
			'bbscoinWithdrawFee' => (string)$_POST['bbscoinWithdrawFee'],
			'bbscoinSiteId' => (string)$_POST['bbscoinSiteId'],
			'bbscoinSiteKey' => (string)$_POST['bbscoinSiteKey'],
			'bbscoinApiMode' => (string)$_POST['bbscoinApiMode'],
			'bbscoinNoSecure' => (string)$_POST['bbscoinNoSecure'],
			);

		// Save all these settings
		//updateSettings($newSettings);

		// !!! DIRTY!
		foreach ($newSettings as $variable => $value)
		{
			$smcFunc['db_insert']('replace',  '{db_prefix}settings',
				array(
					'variable' => 'string',
					'value' => 'string',
					),
				array(
					'variable' => $variable,
					'value' => $value,
					),
				array());

			$modSettings[$variable] = $value;
		}
		// Kill the cache - it needs redoing now, but we won't bother ourselves with that here.
		cache_put_data('modSettings', null, 90);

		// We've saved, tell the user that it was successful
		$context['bbscoin_saved'] = true;
	}
	// Set the page title
	$context['page_title'] = $txt['bbscoin_admin'] . ' - ' . $txt['bbscoin_admin_setting'];
	// Load the template
	loadTemplate('BBSCoinAdmin');
}

?>
