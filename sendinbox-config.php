<?php
/**
 * @Author: Eka Syahwan
 * @Date:   2018-08-30 16:54:08
 * @Last Modified by:   Eka Syahwan
 * @Last Modified time: 2018-10-19 03:05:10
 */
class Sendinbox_config
{
	public function server(){

		$config['server']['multy'][] = 'https://script.google.com/macros/s/AKfycbwpWJ-XBaoKelzKhleMhywE2XLHODWsDYmVlIgzwHIMq76BI8w/exec';

		return $config;

	}
	public function sender(){

		$config['config']['threads'] 				= 1;  				// kirim [value] dalam satu permintaan.
		$config['config']['delay'] 					= 5;  				// [value] delay dalam detik.
		$config['config']['color'] 					= true;				// aktifkan color atau tidak.
		$config['config']['methode'] 				= 'gmailapp';		// mailapp atau gmailapp

		return $config;
	}
	public function message(){

		/*
		 	.==========[>- How To Attachment File. -<]==========.

			1. upload to google drive
			2. get attachment id  (https://www.jeffreyeverhart.com/wp-content/uploads/2015/01/DriveFileID.png)
			3. permission : public / don't private

			** not use attachment ? blank value. **

		*/

		$config['message']['multy']['reaply_to'][] 		= array('name' => 'Apple', 'email' => 'noreply-access-verify5001286184@notifications.icloud.com');
		$config['message']['multy']['subject'][] 		= 'Re: [Summary of News Report] [Statement Report] We have detected that your account has vioIated: {numrandom,9,3,6,7}';
		$config['message']['multy']['letter'][] 		= 'letter1.html';
		$config['message']['multy']['attachment_id'][] 	= '';

		return $config;
	}
}