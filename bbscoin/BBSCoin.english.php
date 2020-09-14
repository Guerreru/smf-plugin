<?php
/**********************************************************************************
* BBSCoin.english.php                                                                *
* Language file for BBSCoin                                                       *
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

global $modSettings, $scripturl;

$txt['bbscoin_admin'] = 'BBSCoin';
$txt['bbscoin_menu_button'] = 'BBSCoin Exchange';
$txt['bbscoin_admin_setting'] = 'General Settings';
$txt['cannot_bbscoin_admin'] = 'Sorry, you aren\'t allowed to access the BBSCoin administration! Only admins can do that!';
$txt['bbscoin_guest_message'] = 'Sorry, guests can\'t access the BBSCoin!<br />Please register to view this forum<br /><br />Powered by BBSCoin <br />&copy; 2018 <a href="https://bbscoin.xyz">BBSCoin</a><br /><br />';

$txt['bbscoin_usercp_nav_name'] = "BBSCoin Exchange";
$txt['bbscoin_no_address'] = "Your website admin has not set the wallet address";
$txt['bbscoin_topoint'] = "Deposit Points from BBSCoin";
$txt['bbscoin_topoint_desc'] = "You can buy Points with BBSCoin. <a href=\"https://bbscoin.xyz\" target=\"_blank\">What's BBSCoin? Click here.</a> The exchange rate is: 1:";
$txt['bbscoin_topoint_cacl'] = "You need transfer";
$txt['bbscoin_topoint_deposit'] = "Deposit";
$txt['bbscoin_topoint_transactionhash'] = "Transaction Hash";
$txt['bbscoin_topoint_transactiontips'] = "Please transfer required BBSCoin to the address below with the Payment ID above, then fill in the blank.";
$txt['bbscoin_points'] = "Points.";
$txt['bbscoin_tobbs'] = "Withdraw BBSCoin";
$txt['bbscoin_tobbs_desc'] = "You can withdraw BBSCoin with Points. The transaction fee will be paid from the withdrawal amount. The transaction will fail when the website's wallet don't have enough balance. Current exchange rate is: 1:";
$txt['bbscoin_tobbs_withdraw'] = "Withdraw";
$txt['bbscoin_tobbs_cacl'] = "You'll need ";
$txt['bbscoin_tobbs_address'] = "Wallet Address ";
$txt['bbscoin_tobbs_address_desc'] = "Please input your wallet address ";
$txt['bbscoin_least'] = "You need to have at least 1 Point";
$txt['bbscoin_cc'] = "Please don't submit too frequently";
$txt['bbscoin_used'] = "This transaction hash has been used.";
$txt['bbscoin_notconfirmed'] = "Your transaction should have at least %d confirmations. Please wait and try again.";
$txt['bbscoin_succ'] = "Your deposit is successful";
$txt['bbscoin_paymentid_error'] = "Deposit Payment ID is incorrect";
$txt['bbscoin_paymentid'] = "Payment ID";
$txt['bbscoin_paymentid_desc'] = "You need fill this Payment ID when you transfer BBSCoin to site wallet address.";
$txt['bbscoin_withdraw_too_low'] = "After deducting the transaction fee, the withdrawal amount is less than 0 BBS.";
$txt['bbscoin_no_enough'] = "Your Points are not enough.";
$txt['bbscoin_withdraw_succ'] = "Your withdraw is successful. The transaction hash is %s.";
$txt['bbscoin_fail'] = "Withdraw failed";
$txt['bbscoin_close_withdraw'] = "Withdraw is closed";
$txt['bbscoin_points_balance'] = "Your Points balance is: ";
$txt['bbscoin_save_changes'] = "Save Changes";
$txt['bbscoin_saved'] = "Saved";
$txt['bbscoin_withdraw_error'] = "Your wallet address is incorrect";
$txt['bbscoin_deposit_wait'] = "Your deposit will be processed when we confirm your transfer automatically";
$txt['bbscoin_deposit_failed'] = "Your deposit is failed, please try again.";
$txt['bbscoin_withdraw_failed'] = "Your withdraw is failed";
$txt['bbscoin_withdraw_failed_desc'] = "Your withdraw is failed. We returned your points. The order id is %s.";
$txt['bbscoin_succ_desc'] = "Your deposit is successful, deposited %s points, transaction hash is %s.";
$txt['bbscoin_withdraw_succ_title'] = "Your withdraw is successful";

$txt['bbscoin_setting_bbscoinPayRatio'] = "BBSCoin to Credits Exchange Rate: ";
$txt['bbscoin_setting_bbscoinPayToCoinRatio'] = "Credits To BBSCoin Exchange Rate: ";
$txt['bbscoin_setting_bbscoinPayToBbscoin'] = "Enable Withdrawal BBSCoin";
$txt['bbscoin_setting_bbscoinWalletAddress'] = "Site BBSCoin Wallet: ";
$txt['bbscoin_setting_bbscoinWalletd'] = "Walletd or Web Wallet URL: (Web Wallet is https://api.bbs.money)";
$txt['bbscoin_setting_bbscoinConfirmedBlocks'] = "Transfer Required Confirmed Blocks: ";
$txt['bbscoin_setting_bbscoinSiteId'] = "BBSCoin Web Wallet Site Id: (Walletd do not need to be filled in)";
$txt['bbscoin_setting_bbscoinSiteKey'] = "BBSCoin Web Wallet Site Key: (Walletd do not need to be filled in)";
$txt['bbscoin_setting_bbscoinWithdrawFee'] = "Withdraw Fee";
$txt['bbscoin_setting_bbscoinNoSecure'] = "Disable HTTPS";
$txt['bbscoin_setting_bbscoinApiMode'] = "Api Mode";
$txt['bbscoin_setting_bbscoinWalletdAPI'] = "Walletd";
$txt['bbscoin_setting_bbscoinWebWalletAPI'] = "Web Wallet API";
$txt['bbscoin_setting_bbscoinWebWalletWebhook'] = "Web Wallet Webhook";

?>