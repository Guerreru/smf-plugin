<?php
/*
***********************************************************************************
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
***********************************************************************************/

global $smcFunc, $db_prefix;

// New settings for the bbscoin mod
$newSettings = array(
	'bbscoinVersion' => '2.0',
	'bbscoinDate' => '2018-6-11',
	'bbscoinBuild' => '4',
	'bbscoinPayRatio' => '0.1',
	'bbscoinPayToCoinRatio' => '10',
	'bbscoinPayToBbscoin' => '1',
	'bbscoinWalletAddress' => '',
	'bbscoinWalletd' => 'http://127.0.0.1:8070/json_rpc',
	'bbscoinConfirmedBlocks' => '1',
	'bbscoinWithdrawFee' => '1',
	'bbscoinSiteId' => '',
	'bbscoinSiteKey' => '',
	'bbscoinApiMode' => 0,
	'bbscoinNoSecure' => 0,
	);

// Insert them into the database
// !!! This is done evily like this!
foreach ($newSettings as $variable => $value)
{
	$smcFunc['db_insert']('replace', '{db_prefix}settings',
		array(
			'variable' => 'string',
			'value' => 'string',
			),
		array(
			'variable' => $variable,
			'value' => $value,
			),
		array()
	);
}

// bbscoin_orders
$smcFunc['db_create_table']('{db_prefix}bbscoin_orders',
	array(
		array(
			'name' => 'orderid',
			'type' => 'char',
			'size' => 50,
			'auto' => false,
		),
		array(
			'name' => 'transaction_hash',
			'type' => 'char',
			'size' => 64,
		),
		array(
			'name' => 'address',
			'type' => 'char',
			'size' => 100,
		),
		array(
			'name' => 'dateline',
			'type' => 'int',
            'unsigned' => true,
			'size' => 10,
			'default' => 0,
        ),
	),
	array(
		array(
			'name' => 'orderid',
			'type' => 'primary',
			'columns' => array('orderid'),
		),
		array(
			'name' => 'transaction_hash',
			'type' => 'unique',
			'columns' => array('transaction_hash'),
		),
		array(
			'name' => 'address',
			'type' => 'key',
			'columns' => array('address', 'dateline'),
		),
	),
	array(),
	'overwrite');


// bbscoin_locks
$smcFunc['db_create_table']('{db_prefix}bbscoin_locks',
	array(
		array(
			'name' => 'uid',
			'type' => 'int',
            'unsigned' => true,
			'size' => 10,
			'auto' => false,
		),
		array(
			'name' => 'dateline',
			'type' => 'int',
            'unsigned' => true,
			'size' => 10,
			'default' => 0,
        ),
	),
	array(
		array(
			'name' => 'uid',
			'type' => 'primary',
			'columns' => array('uid'),
		),
	),
	array(),
	'overwrite');

?>