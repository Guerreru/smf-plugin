<?php
/***************************************************************************
 *
 *   BBSCoin Api for PHP
 *   Author: BBSCoin Foundation
 *   
 *   Website: https://bbscoin.xyz
 *
 ***************************************************************************/
 
/****************************************************************************
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/

// Class for site interface
class BBSCoinApiPartner {

    public static function callback($json_data) {
        global $modSettings, $txt, $smcFunc;

        if ($json_data['data']['action'] == 'deposit') {
            if ($json_data['callbackData']['amount'] > 0) {
                $trans_amount = $json_data['callbackData']['amount'];
                $amount = $trans_amount * $modSettings['bbscoinPayRatio'];

                $orderid = date('YmdHis').base_convert($json_data['data']['uin'], 10, 36);
                
                $orderinfo = array(
                	'uid' => $json_data['data']['uin'],
                	'amount' => $amount,
                	'price' => $trans_amount,
                );

            	$result = $smcFunc['db_query']('', "
            		SELECT * FROM {db_prefix}bbscoin_orders
            		WHERE transaction_hash = {string:transaction_hash}
            		LIMIT 1",
            		array(
            			'transaction_hash' => $json_data['callbackData']['hash'],
            			));
                $db_assoc = $smcFunc['db_fetch_assoc']($result);
            	if ($db_assoc) {
            		return array('success' => true);
            	}

            	$smcFunc['db_insert']('insert', '{db_prefix}bbscoin_orders',
            		array(
            			'orderid' => 'string',
            			'transaction_hash' => 'string',
            			'address' => 'string',
            			'dateline' => 'int',
            			),
            		array(
            			'orderid' => $orderid,
            			'transaction_hash' => $json_data['callbackData']['hash'],
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
        				'amount' => $orderinfo['amount'],
        				'id' => $json_data['data']['uin'],
        				));


                log_error('Deposit From BBSCoin', 'Points:'.$orderinfo['amount'].', BBSCoin: '.$trans_amount.', transaction_hash:'.$json_data['callbackData']['hash']);
                bbsCoinSendPM($txt['bbscoin_succ'], sprintf($txt['bbscoin_succ_desc'], (string)$orderinfo['amount'], (string)$json_data['callbackData']['hash']), $json_data['data']['uin']);
            }

            return array('success' => true);
        } elseif ($json_data['data']['action'] == 'withdraw') {
            if ($json_data['callbackData']['status'] != 'normal') {

            	$result = $smcFunc['db_query']('', "
            		SELECT * FROM {db_prefix}bbscoin_orders
            		WHERE orderid = {string:orderid}
            		LIMIT 1",
            		array(
            			'orderid' => $json_data['data']['orderid'].'_R',
            			));
                $db_assoc = $smcFunc['db_fetch_assoc']($result);
            	if ($db_assoc) {
            		return array('success' => true);
            	}

            	$smcFunc['db_insert']('insert', '{db_prefix}bbscoin_orders',
            		array(
            			'orderid' => 'string',
            			'transaction_hash' => 'string',
            			'address' => 'string',
            			'dateline' => 'int',
            			),
            		array(
            			'orderid' => $json_data['data']['orderid'].'_R',
            			'transaction_hash' => $json_data['data']['orderid'].'_R',
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
        				'amount' => $json_data['data']['points'],
        				'id' => $json_data['data']['uin'],
        				));

                log_error('Withdraw To BBSCoin Failed', 'Points:'.$json_data['data']['points'].', Order ID:'.$json_data['data']['orderid']);
                bbsCoinSendPM($txt['bbscoin_withdraw_failed'], sprintf($txt['bbscoin_withdraw_failed_desc'], (string)$json_data['data']['orderid']), $json_data['data']['uin']);
            }

            return array('success' => true);
        } else {
            return array('success' => false, 'message' => 'error action');
        }
    }
}
