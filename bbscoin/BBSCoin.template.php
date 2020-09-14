<?php
/**********************************************************************************
* BBSCoin.template.php                                                               *
* Template file for BBSCoin                                                       *
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

function template_bbscoin_above()
{
	global $txt, $context, $modSettings, $scripturl;

	echo '
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-top: 1ex;"><tr>
			<td valign="top">';

}

function template_bbscoin_below()
{
	global $sourcedir;
	
	echo '
			</td>
		</tr>
	</table>';
}

function template_main()
{
	global $txt, $context, $modSettings, $scripturl, $settings;
	
	echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder" style="margin-top: 1.5ex;">
			<tr class="titlebg">
				<td align="center" class="largetext headerpadding">
					', $txt['bbscoin_topoint'], '
				</td>
			</tr>
			<tr valign="top" class="windowbg2">
				<td style="padding-bottom: 2ex;" width="20%">
					'. $txt['bbscoin_topoint_desc'].$modSettings['bbscoinPayRatio']. '
					<form action="', $scripturl, '?action=bbscoin;do=deposit" method="post">
						<table>
							<tr>
								<td width="50%" align="left"><label for="addfundamount"><strong>', $txt['bbscoin_topoint_deposit'], ':</strong></label></td>
								<td>', $modSettings['shopCurrencyPrefix'], '<input type="text" name="amount"  onkeyup="addcalcredit()"  id="addfundamount" size="50" />'. $modSettings['shopCurrencySuffix'].' '.$txt['bbscoin_topoint_cacl']. ' <span id="desamount">0</span> BBS</td>
							</tr>
							<tr>
								<td align="left"><label for="paymentId"><strong>', $txt['bbscoin_paymentid'], ':</strong></label><br />'.$txt['bbscoin_paymentid_desc'].'</td>
								<td><input type="text" name="paymentId" readonly="readonly" id="paymentId" size="85" value="'.$_SERVER['bbscoin_paymentid'].'" /></td>
							</tr>
							<tr>
								<td align="left"><label for="transaction_hash"><strong>', $txt['bbscoin_topoint_transactionhash'], ':</strong></label><br />'.$txt['bbscoin_topoint_transactiontips'].'<br />'. $modSettings['bbscoinWalletAddress'].'</td>
								<td><input type="text" name="transaction_hash" id="transaction_hash" size="85" /></td>
							</tr>
						</table>
        				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
        				<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
						<input type="submit" value="', $txt['bbscoin_topoint_deposit'], '" />
					</form>
				</td>
			</tr>
		</table>
';
    if ($modSettings['bbscoinPayToBbscoin']) {
echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder" style="margin-top: 1.5ex;">
			<tr class="titlebg">
				<td align="center" class="largetext headerpadding">
					', $txt['bbscoin_tobbs'], '
				</td>
			</tr>
			<tr valign="top" class="windowbg2">
				<td style="padding-bottom: 2ex;" width="20%">
					'. $txt['bbscoin_tobbs_desc'].$modSettings['bbscoinPayToCoinRatio']. '
					<form action="', $scripturl, '?action=bbscoin;do=withdraw" method="post">
						<table>
							<tr>
								<td width="50%" align="left"><label for="addcoinamount"><strong>', $txt['bbscoin_tobbs_withdraw'], ':</strong><br />'.$txt['bbscoin_points_balance'].bbsCoinFormatMoney($context['user']['money']).'</label></td>
								<td>', $modSettings['shopCurrencyPrefix'], '<input type="text" name="amount"  onkeyup="addcalcoin()"  id="addcoinamount" size="50" />BBS '.$txt['bbscoin_tobbs_cacl'].' '.$modSettings['shopCurrencyPrefix'].' <span id="coin_desamount">0</span> '.$modSettings['shopCurrencySuffix'].'</td>
							</tr>
							<tr>
								<td align="left"><label for="walletaddress"><strong>', $txt['bbscoin_tobbs_address'], ':</strong></label><br />'.$txt['bbscoin_tobbs_address_desc'].'</td>
								<td><input type="text" name="walletaddress" id="walletaddress" size="50" /></td>
							</tr>
						</table>
        				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
        				<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
						<input type="submit" value="', $txt['bbscoin_tobbs_withdraw'], '" />
					</form>
				</td>
			</tr>
		</table>
    ';
}
echo '
<script type="text/javascript">
function random_paymentid(len) {
    len = len || 64;
    var xchars = \'ABCDEF0123456789\'; 
    var maxPos = xchars.length;
    var pwd = \'\';
    for (i = 0; i < len; i++) {
        pwd += xchars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}
function addcalcredit() {
var addfundamount = document.getElementById(\'addfundamount\').value.replace(/^0/, \'\');
var addfundamount = parseInt(addfundamount);
document.getElementById(\'desamount\').innerText = !isNaN(addfundamount) ? Math.ceil(((addfundamount / '.$modSettings['bbscoinPayRatio'].') * 100)) / 100 : 0;
}

function addcalcoin() {
var addcoinamount = document.getElementById(\'addcoinamount\').value.replace(/^0/, \'\');
var addcoinamount = parseInt(addcoinamount);
document.getElementById(\'coin_desamount\').innerText = !isNaN(addcoinamount) ? Math.ceil(((addcoinamount / '.$modSettings['bbscoinPayToCoinRatio'].') * 100)) / 100 : 0;
}
</script>
    ';
}

function template_message()
{
	global $context;

	echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder" style="margin-top: 1.5ex;">
			<tr valign="top" class="windowbg2">
				<td style="padding-bottom: 2ex;" width="20%">
					', $context['bbscoin_message'], '
				</td>
			</tr>
		</table>';

}
?>
