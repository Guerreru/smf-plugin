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


    try {
        $rsp_data = BBSCoinApiWebWallet::getTransactionDetails($modSettings['bbscoinWalletd'], $transaction_hash);
    } catch (Exception $e) {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));
    	fatal_error('Error '.$e->getCode().','.$e->getMessage());
    }

    if ($rsp_data['data']['status'] != 'normal' || $rsp_data['data']['confirmations'] <= $config['confirmed_blocks']) {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));
    	fatal_error(sprintf($txt['bbscoin_notconfirmed'], $modSettings['bbscoinConfirmedBlocks']));
    }

    $trans_amount = $rsp_data['data']['amount'];
    $amount = $trans_amount * $modSettings['bbscoinPayRatio'];

    $orderinfo = array(
    	'uid' => $context['user']['id'],
    	'amount' => $amount,
    	'price' => $trans_amount,
    );

    if ($paymentId == $_SERVER['bbscoin_paymentid'] && strtolower($rsp_data['data']['paymentId']) == strtolower($paymentId)) {
    	$smcFunc['db_insert']('insert', '{db_prefix}bbscoin_orders',
    		array(
    			'orderid' => 'string',
    			'transaction_hash' => 'string',
    			'address' => 'string',
    			'dateline' => 'int',
    			),
    		array(
    			'orderid' => $orderid,
    			'transaction_hash' => $transaction_hash,
    			'address' => '',
    			'dateline' => time(),
    			),
    		array()
    	);

		$smcFunc['db_query']('', "
			UPDATE {db_prefix}members
			SET money = money + {int:amount}
			WHERE id_member = {int:id}
			LIMIT 1",
			array(
				'amount' => $amount,
				'id' => $context['user']['id'],
				));

        log_error('Deposit From BBSCoin', 'Points:'.$orderinfo['amount'].', BBSCoin: '.$need_bbscoin.', transaction_hash:'.$transaction_hash);
        bbsCoinSendPM($txt['bbscoin_succ'], sprintf($txt['bbscoin_succ_desc'], (string)$orderinfo['amount'], (string)$transaction_hash), $context['user']['id']);

		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));

    	$context['template_layers'][] = 'bbscoin';
    	$context['page_title'] = $txt['bbscoin_usercp_nav_name'];
        $context['sub_template'] = 'message';
    	$context['bbscoin_message'] = $txt['bbscoin_succ'];
    } else {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));

        fatal_error($txt['bbscoin_paymentid_error']);
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

	$smcFunc['db_query']('', "
		UPDATE {db_prefix}members
		SET money = money - {int:amount}
		WHERE id_member = {int:id}
		LIMIT 1",
		array(
			'amount' => $need_point,
			'id' => $context['user']['id'],
			));

    log_error('Withdraw To BBSCoin Start', 'Points:'.$need_point.', BBSCoin:'.$amount.', address:'.$walletaddress);

    try {
        $rsp_data = BBSCoinApiWebWallet::send($modSettings['bbscoinWalletd'], $modSettings['bbscoinWalletAddress'], $real_price, $walletaddress, $orderid, $context['user']['id'], $need_point, $modSettings['bbscoinWithdrawFee'], false);
    } catch (Exception $e) {
		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));
    	fatal_error('Error '.$e->getCode().','.$e->getMessage());
    }

    $trans_amount = 0;
    if ($rsp_data['data']['hash']) {
    	$smcFunc['db_insert']('insert', '{db_prefix}bbscoin_orders',
    		array(
    			'orderid' => 'string',
    			'transaction_hash' => 'string',
    			'address' => 'string',
    			'dateline' => 'int',
    			),
    		array(
    			'orderid' => $orderid,
    			'transaction_hash' => $rsp_data['data']['hash'],
    			'address' => $walletaddress,
    			'dateline' => time(),
    			),
    		array()
    	);

		$smcFunc['db_query']('', "
			DELETE FROM {db_prefix}bbscoin_locks
			WHERE uid = {int:id}",
			array(
				'id' => $context['user']['id'],
				));

    	$context['template_layers'][] = 'bbscoin';
    	$context['page_title'] = $txt['bbscoin_usercp_nav_name'];
        $context['sub_template'] = 'message';
    	$context['bbscoin_message'] = sprintf($txt['bbscoin_withdraw_succ'], $rsp_data['data']['hash']);

        log_error('Withdraw To BBSCoin Success', 'Points:'.$need_point.', BBSCoin:'.$amount.', address:'.$walletaddress);
        bbsCoinSendPM($txt['bbscoin_withdraw_succ_title'], sprintf($txt['bbscoin_withdraw_succ'], (string)$rsp_data['data']['hash']), $context['user']['id']);
    } else {
    	$smcFunc['db_query']('', "
    		UPDATE {db_prefix}members
    		SET money = money + {int:amount}
    		WHERE id_member = {int:id}
    		LIMIT 1",
    		array(
    			'amount' => $need_point,
    			'id' => $context['user']['id'],
    			));

        log_error('Withdraw To BBSCoin Refund', 'Points:'.$need_point.', BBSCoin:'.$amount.', address:'.$walletaddress);

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
