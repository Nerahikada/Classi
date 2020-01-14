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

namespace Classi\content;

use Classi\content\question\CheckboxQuestion;
use Classi\content\question\SelfCheckQuestion;

class ProgramContent extends Content{

	public function getType() : string{
		return 'program';
	}

	public function init() : void{}

	public function doHomework() : void{
		$response = $this->client->getHttpClient()->get($this->url);
		$body = (string) $response->getBody();

		if(strpos($body, '%%SELF_RATING_NOT_ANSWERED_YET%%') !== false){
			$question = new SelfCheckQuestion($this->client, $this->url);
		}else{
			if(strpos($body, 'type="checkbox"') !== false){
				$question = new CheckboxQuestion($this->client, $this->url);
			}
		}
	}
}