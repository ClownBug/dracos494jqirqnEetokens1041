<?php
/**
 * @Author: Eka Syahwan
 * @Date:   2018-08-29 08:57:41
 * @Last Modified by:   Eka Syahwan
 * @Last Modified time: 2018-10-19 22:18:08
*/
error_reporting(0);
session_start();
define('SENDINBOX_PATH', realpath(dirname(__FILE__)));

require_once('Modules/SendinboxMailer/sendinboxMailer.php');
require_once('Modules/sdata-master/sdata-modules.php');
require_once('sendinbox-config.php');

class Sendinbox extends Sendinbox_config
{
	function __construct(){

		if(file_exists("log.txt")){
			echo file_get_contents("log.txt");
			echo "\r\n";
			for ($i=0; $i <11; $i++) {
				echo $i." | ";
				sleep(1);
			}
			echo "\r\n";
		}


		$this->sdata 		= new Sdata;
		$this->SIBmodules 	= new SendinboxMailer( $this->sender() );
		$this->SIBmodules->cover();
		$this->Emailist 	= $this->SIBmodules->required();

		echo "\r\n[+] ".$this->SIBmodules->color("string","====================================================================")." [+]\r\n";
   		echo "[+] ".$this->SIBmodules->color("string","[ A W E S O M E - M A I L E R ]  - Emailist.org / www.awesome-exploits.info ")."\r\n";
   		echo "[+] ".$this->SIBmodules->color("string","--------------------------------------------------------------------")."\r\n";
   		echo "[+] ".$this->SIBmodules->color("red","Selalu gunakan sendinbox yang legal. jika kamu masih numpang")."\r\n";
   		echo "[+] ".$this->SIBmodules->color("white","kami akan kasih kamu 20% diskon produk sendinbox")."\r\n";
   		echo "[+] ".$this->SIBmodules->color("white","suruh kawan mu (pembeli legal) untuk membelikan sendinbox untuk mu.")."\r\n";
   		echo "[+] ".$this->SIBmodules->color("white","untuk mu. itu lebih baik dari pada joinan sungkan minta update.")."\r\n";
   		echo "[+] ".$this->SIBmodules->color("green","** 1 kawan hanya bisa request 1x untuk diskon 20% **")."\r\n";
   		echo "[+] ".$this->SIBmodules->color("string","====================================================================")." [+]\r\n\n";

   		echo $this->SIBmodules->color("string","[sendunbox][N] --------- [EMAIL] -----------|------[SUBJECT]------|--[Daily Quota]--|-----STATUS-----")."\r\n";

		$this->run();
	}
	function run(){
		$config_server 	= $this->server();
		$config_sender 	= $this->sender();
		$config_message = $this->message();

		$list_total 	= count($this->Emailist);
		$threads 		= $config_sender['config']['threads']; // don't change

		$emailist_split = array_chunk($this->Emailist, $threads);


		$_SESSION['smtp_list'] = $config_server['server']['multy'];

		foreach ($emailist_split as $key => $getEmailist) {


			foreach ($getEmailist as $key => $putEmail) {

					######################################################################################################################
					# [ Random SET] #
					$server 			= $_SESSION['smtp_list'][array_rand($_SESSION['smtp_list'])];

					$subject 			= $config_message['message']['multy']['subject'][array_rand($config_message['message']['multy']['subject'])];
					$letter 			= $config_message['message']['multy']['letter'][array_rand($config_message['message']['multy']['letter'])];
					$attachment 		= $config_message['message']['multy']['attachment_id'][array_rand($config_message['message']['multy']['attachment_id'])];
					$from 				= $config_message['message']['multy']['reaply_to'][array_rand($config_message['message']['multy']['reaply_to'])];

					######################################################################################################################
					# [ encode SET & Alias ] #

					$subject 		= $this->SIBmodules->alias($subject , $putEmail);
					$from['name'] 	= $this->SIBmodules->alias($from['name'] , $putEmail);
					$from['email'] 	= $this->SIBmodules->alias($from['email'] , $putEmail);

					$letter 		= file_get_contents(SENDINBOX_PATH.'/Letter/'.$letter);
					$letter 		= $this->SIBmodules->alias($letter , $putEmail);

					######################################################################################################################
					$post = array(
						'to' 			=> $putEmail,
						'subject' 		=> $subject,
						'letter' 		=> $letter,
						'methode' 		=> $config_sender['config']['methode'],
						'reaply_email' 	=> $from['email'],
						'reaply_name' 	=> $from['name'],
						'fileID' 		=> $attachment,
						'note' 		=> array(
							'line'  	=> array_search($putEmail, $this->Emailist ),
							'email' 	=> $putEmail,
							'server' 	=> substr(parse_url($server)['host'], 0,20)." ... ",
						),
					);
					$this->arrayData[] = array(
						'url' 	=> array(
						'url' => $server,
							'note' => array(
								'line'  	=> array_search($putEmail, $this->Emailist ),
								'email' 	=> $putEmail,
								'post' 		=> $post,
							),
						),
						'head' 	=> array(
							'post' => http_build_query( $post ),
						),
					);


					$list_total = ($list_total-1);
				}

			$respons = $this->send();
			$this->SIBmodules->extract_message($respons);

			unset($this->arrayData);
			if($list_total == 0){
				die($this->SIBmodules->color("bggreen","\r\n[AwesomeSender] Pengiriman email telah selesai.\r\n"));
			}
			echo "[+] ".$this->SIBmodules->color("string","=======================[ DELAY ".$config_sender['config']['delay']." ]===========================")." [+]\r\n";
			sleep($config_sender['config']['delay']);

		}
	}
	function send(){
		$fopn_success 	= fopen("Emailist/Success-send.txt", "a+");
		$fopn_failed 	= fopen("Emailist/Failed-send.txt", "a+");

		foreach ($this->arrayData as $key => $arrayData) {
			$url[] 	= $arrayData['url'];
			$head[] = $arrayData['head'];
		}

		$request = $this->sdata->sdata($url , $head);

		$this->sdata->session_remove($request);

		unset($url); unset($head);

		foreach ($request as $key => $value) {
			$arrayNumber[$key] 	= $value['data']['note']['line'];
			$json 				= json_decode($value['respons'],true);
			$result[] = array(
				'post' 	=> $value['data']['note']['post'],
				'email' => $value['data']['note']['email'],
				'line' 	=> $value['data']['note']['line'],
				'code' 	=> $value['info']['http_code'],
				'json' 	=> $json,
			);
			if($json['result']){
				fwrite($fopn_success, $value['data']['note']['email']."\r\n");
			}else{
				fwrite($fopn_failed, $value['data']['note']['email']."\r\n");
			}
		}

		fclose($fopn_success);
		fclose($fopn_failed);
		natsort($arrayNumber);
		sort($arrayNumber);

		return array($result);
	}
};
$Sendinbox = new Sendinbox;