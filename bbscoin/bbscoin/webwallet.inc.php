<?php
if (!defined('SMF'))
	die('Hacking attempt...');

loadLanguage('BBSCoin');
loadTemplate('BBSCoin');
is_not_guest($txt['bbscoin_guest_message']);
isAllowedTo('bbscoin_main');

if (!$modSettings['bbscoinWalletAddress']) {
    fatal_error($txt['bbscoin_no_address']);
}

if ($_GET['do'] == "deposit") 
{
    checkSubmitOnce('check');

	$result = $smcFunc['db_query']('', "
		SELECT * FROM {db_prefix}bbscoin_locks
		WHERE uid = {int:id}
		LIMIT 1",
		array(
			'id' => $context['user']['id'],
			));
    $lockinfo = $smcFunc['db_fetch_assoc']($result);
	if ($lockinfo) {
        if (time() - $lockinfo['dateline'] > 10) {
    		$smcFunc['db_query']('', "
    			DELETE FROM {db_prefix}bbscoin_locks
    			WHERE uid = {int:id}",
    			array(
    				'id' => $context['user']['id'],
    				));
        }
        fatal_error($txt['bbscoin_cc']);
	} else {
    	$smcFunc['db_insert']('insert', '{db_prefix}bbscoin_locks',
    		array(
    			'uid' => 'int',
    			'dateline' => 'int',
    			),
    		array(
    			'variable' => $context['user']['id'],
    			'value' => time(),
    			),
    		array()
    	);
    }

    $orderid = date('YmdHis').base_convert($context['user']['id'], 10, 36);
    $transaction_hash = trim($_POST['transaction_hash']);
    $paymentId = trim($_POST['paymentId']);

	$result = $smcFunc['db_query']('', "
		SELECT * FROM {db_prefix}bbscoin_orders
		WHERE transaction_hash = {string:transaction_hash}
		LIMIT 1",
		array(
			'transaction_hash' => $transaction_hash,
			));
    $db_assoc = $smcFunc['db_fetch_assoc']($result);
	if ($db_assoc) {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));
    	fatal_error($txt['bbscoin_used']);
	}

    if ($paymentId != $_SERVER['bbscoin_paymentid']) {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));
    	fatal_error($txt['bbscoin_deposit_failed']);
    }

    // online wallet
    try {
        $rsp_data = BBSCoinApiWebWallet::checkTransaction($modSettings['bbscoinWalletd'], $transaction_hash, $paymentId, $context['user']['id']);
    } catch (Exception $e) {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));
    	fatal_error('Error '.$e->getCode().','.$e->getMessage());
    }

	$smcFunc['db_query']('', "
		DELETE FROM {db_prefix}bbscoin_locks
		WHERE uid = {int:id}",
		array(
			'id' => $context['user']['id'],
			));


    if ($rsp_data['success']) {
    	$context['template_layers'][] = 'bbscoin';
    	$context['page_title'] = $txt['bbscoin_usercp_nav_name'];
        $context['sub_template'] = 'message';
    	$context['bbscoin_message'] = $txt['bbscoin_deposit_wait'];
    } else {
        fatal_error($txt['bbscoin_deposit_failed']);
    }

} elseif ($_GET['do'] == "withdraw") 
{
    checkSubmitOnce('check');

    if(!$modSettings['bbscoinPayToBbscoin']) {
    	fatal_error($txt['bbscoin_close_withdraw']);
    }

    $amount = $_POST['amount'];
    $need_point = ceil((($amount / $modSettings['bbscoinPayToCoinRatio']) * 100)) / 100;

    if ($need_point < 1) {
    	fatal_error($txt['bbscoin_least']);
    }

    $walletaddress = trim($_POST['walletaddress']);

    if ($modSettings['bbscoinWalletAddress'] == $walletaddress) {
        fatal_error($txt['bbscoin_withdraw_error']);
    }

    $real_price = $amount - $modSettings['bbscoinWithdrawFee'];

    if ($real_price <= 0) {
        fatal_error($txt['bbscoin_withdraw_too_low']);
    }

	$result = $smcFunc['db_query']('', "
		SELECT * FROM {db_prefix}bbscoin_locks
		WHERE uid = {int:id}
		LIMIT 1",
		array(
			'id' => $context['user']['id'],
			));
    $lockinfo = $smcFunc['db_fetch_assoc']($result);
	if ($lockinfo) {
        if (time() - $lockinfo['dateline'] > 10) {
    		$smcFunc['db_query']('', "
    			DELETE FROM {db_prefix}bbscoin_locks
    			WHERE uid = {int:id}",
    			array(
    				'id' => $context['user']['id'],
    				));
        }
        fatal_error($txt['bbscoin_cc']);
	} else {
    	$smcFunc['db_insert']('insert', '{db_prefix}bbscoin_locks',
    		array(
    			'uid' => 'int',
    			'dateline' => 'int',
    			),
    		array(
    			'variable' => $context['user']['id'],
    			'value' => time(),
    			),
    		array()
    	);
    }

    if ($need_point > $context['user']['money']) {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));
    	fatal_error($txt['bbscoin_no_enough']);
    }

    $orderid = date('YmdHis').base_convert($context['user']['id'], 10, 36);

    try {
        $rsp_data = BBSCoinApiWebWallet::send($modSettings['bbscoinWalletd'], $modSettings['bbscoinWalletAddress'], $real_price, $walletaddress, $orderid, $context['user']['id'], $need_point, $modSettings['bbscoinWithdrawFee']);
    } catch (Exception $e) {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));
    	fatal_error('Error '.$e->getCode().','.$e->getMessage());
    }

    if ($rsp_data['success'] == true) {
    	$smcFunc['db_insert']('insert', '{db_prefix}bbscoin_orders',
    		array(
    			'orderid' => 'string',
    			'transaction_hash' => 'string',
    			'address' => 'string',
    			'dateline' => 'int',
    			),
    		array(
    			'orderid' => $orderid,
    			'transaction_hash' => $rsp_data['result']['transactionHash'],
    			'address' => $walletaddress,
    			'dateline' => time(),
    			),
    		array()
    	);

		$smcFunc['db_query']('', "
			UPDATE {db_prefix}members
			SET money = money - {int:amount}
			WHERE id_member = {int:id}
			LIMIT 1",
			array(
				'amount' => $need_point,
				'id' => $context['user']['id'],
				));

		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));

    	$context['template_layers'][] = 'bbscoin';
    	$context['page_title'] = $txt['bbscoin_usercp_nav_name'];
        $context['sub_template'] = 'message';
    	$context['bbscoin_message'] = sprintf($txt['bbscoin_withdraw_succ'], $rsp_data['result']['transactionHash']);

        log_error('Withdraw To BBSCoin', 'Points:'.$need_point.', BBSCoin:'.$amount.', address:'.$walletaddress);
        bbsCoinSendPM($txt['bbscoin_withdraw_succ_title'], sprintf($txt['bbscoin_withdraw_succ'], (string)$rsp_data['result']['transactionHash']), $context['user']['id']);
    } else {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));

        fatal_error($txt['bbscoin_fail']);
    }

} else {
	checkSubmitOnce('register');
	
	$context['template_layers'][] = 'bbscoin';

	// Set the page title
	$context['page_title'] = $txt['bbscoin_usercp_nav_name'];
	// Main template for the main page :)	
	$context['sub_template'] = 'main';
}
