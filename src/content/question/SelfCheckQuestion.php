<?php

/**
 *
 *  $$$$$$\  $$\                               $$\ 
 * $$  __$$\ $$ |                              \__|
 * $$ /  \__|$$ | $$$$$$\   $$$$$$$\  $$$$$$$\ $$\ 
 * $$ |      $$ | \____$$\ $$  _____|$$  _____|$$ |
 * $$ |      $$ | $$$$$$$ |\$$$$$$\  \$$$$$$\  $$ |
 * $$ |  $$\ $$ |$$  __$$ | \____$$\  \____$$\ $$ |
 * \$$$$$$  |$$ |\$$$$$$$ |$$$$$$$  |$$$$$$$  |$$ |
 *  \______/ \__| \_______|\_______/ \_______/ \__|
 *
 *
 * @author Nerahikada
 * @link https://twitter.com/Nerahikada
 *
 */

declare(strict_types=1);

namespace Classi\content\question;

use Classi\Client;

class SelfCheckQuestion{

	public function solve() : bool{
		$response = $this->sendAnswer();
		$dom = $this->client->responseToDom($response);
		$postData = [];
		foreach($dom->find('form')->find('input') as $input){
			if($input->type === 'radio' && $input->value == '0'){
				continue;
			}
			$postData[$input->name] = $input->value;
		}
		$this->client->getHttpClient()->post('https://video.classi.jp' . $dom->find('form')->action, [
			'form_params' => $postData
		]);

		// この後の挙動は未調査

		return true;
	}
}