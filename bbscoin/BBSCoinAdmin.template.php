<?php
/**********************************************************************************
* BBSCoinAdmin.template.php                                                          *
* Template file for BBSCoin Administration page                                   *
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

// Feel free to edit this template however you want, but be careful not to break anything.
// Make sure you have a backup handy.

// The main admin page
// TODO: Fix this code! Tables are ugly, and there's way too many :P
function template_main()
{
	global $modSettings, $scripturl, $context, $txt, $sourcedir;

	require_once($sourcedir . '/bbscoin/BBSCoinVersion.php');

	echo '
				<form action="', $scripturl, '?action=admin;area=bbscoin_general;save" method="post">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td valign="top"  colspan="3">
								<table width="100%" cellpadding="5" cellspacing="1" border="0" class="bordercolor">
									<tr>
										<td class="catbg">' .$txt['bbscoin_admin'].'</td>
									</tr><tr>
										<td class="windowbg2" valign="top" align="center">
											<b>', $bbscoinVersion['version'], '</b>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						</table>



								<table width="100%" cellpadding="5" cellspacing="1" border="0" class="bordercolor">
									<tr>
										<td class="catbg">', $txt['bbscoin_admin_setting'], '</td>
									</tr><tr>
										<td class="windowbg2" valign="top" style="height: 18ex;">
											<table>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinPayRatio'], ':</label></td>
													<td><input type="text" name="bbscoinPayRatio" id="bbscoinPayRatio" value="', $modSettings['bbscoinPayRatio'], '" size="55" /></td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinPayToCoinRatio'], ':</label></td>
													<td><input type="text" name="bbscoinPayToCoinRatio" id="bbscoinPayToCoinRatio" value="', $modSettings['bbscoinPayToCoinRatio'], '" size="55" />
                      									<input type="checkbox" name="bbscoinPayToBbscoin" id="bbscoinPayToBbscoin"', ($modSettings['bbscoinPayToBbscoin'] == '1' ? ' checked="checked"' : ''), ' value="1" /><label for="bbscoinPayToBbscoin">', $txt['bbscoin_setting_bbscoinPayToBbscoin'], '</label><br />
                                                    </td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinWalletAddress'], ':</label></td>
													<td><input type="text" name="bbscoinWalletAddress" id="bbscoinWalletAddress" value="', $modSettings['bbscoinWalletAddress'], '" size="55" /></td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinWalletd'], ':</label></td>
													<td><input type="text" name="bbscoinWalletd" id="bbscoinWalletd" value="', $modSettings['bbscoinWalletd'], '" size="55" /></td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinWithdrawFee'], ':</label></td>
													<td><input type="text" name="bbscoinWithdrawFee" id="bbscoinWithdrawFee" value="', $modSettings['bbscoinWithdrawFee'], '" size="55" /></td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinConfirmedBlocks'], ':</label></td>
													<td><input type="text" name="bbscoinConfirmedBlocks" id="bbscoinConfirmedBlocks" value="', $modSettings['bbscoinConfirmedBlocks'], '" size="55" /></td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinSiteId'], ':</label></td>
													<td><input type="text" name="bbscoinSiteId" id="bbscoinSiteId" value="', $modSettings['bbscoinSiteId'], '" size="55" /></td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinSiteKey'], ':</label></td>
													<td><input type="text" name="bbscoinSiteKey" id="bbscoinSiteKey" value="', $modSettings['bbscoinSiteKey'], '" size="55" /></td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinNoSecure'], ':</label></td>
													<td>
                      									<input type="checkbox" name="bbscoinNoSecure" id="bbscoinNoSecure"', ($modSettings['bbscoinNoSecure'] == '1' ? ' checked="checked"' : ''), ' value="1" /><label for="bbscoinNoSecure">', $txt['bbscoin_setting_bbscoinNoSecure'], '</label><br />
                                                    </td>
												</tr>
												<tr>
													<td align="right"><label for="interest">', $txt['bbscoin_setting_bbscoinApiMode'], ':</label></td>
													<td>
                                                        <input type="radio" name="bbscoinApiMode" id="bbscoin_setting_bbscoinWalletd"', (!$modSettings['bbscoinApiMode'] ? ' checked="checked"' : ''), ' value="0" /><label for="bbscoin_setting_bbscoinWalletd">', $txt['bbscoin_setting_bbscoinWalletdAPI'], '</label>
                      									<input type="radio" name="bbscoinApiMode" id="bbscoin_setting_bbscoinWebWalletAPI"', ($modSettings['bbscoinApiMode'] == '1' ? ' checked="checked"' : ''), ' value="1" /><label for="bbscoin_setting_bbscoinWebWalletAPI">', $txt['bbscoin_setting_bbscoinWebWalletAPI'], '</label>
                                                        <input type="radio" name="bbscoinApiMode" id="bbscoin_setting_bbscoinWebWalletWebhook"', ($modSettings['bbscoinApiMode'] == '2' ? ' checked="checked"' : ''), ' value="2" /><label for="bbscoin_setting_bbscoinWebWalletWebhook">', $txt['bbscoin_setting_bbscoinWebWalletWebhook'], '</label>
                                                        <br />
                                                    </td>
												</tr>
											</table>
                                            <br />
											<input type="submit" value="', $txt['bbscoin_save_changes'], '" /><br />
											', ($context['bbscoin_saved'] == true ? '<b>' . $txt['bbscoin_saved'] . '</b>' : ''), '
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</form>
';

}

?>
