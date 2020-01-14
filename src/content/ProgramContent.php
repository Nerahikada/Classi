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
use Classi\content\question\DropdownQuestion;
use Classi\content\question\InputQuestion;
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
			}elseif(strpos($body, 'type="text"') !== false){
				$question = new InputQuestion($this->client, $this->url);
			}elseif(strpos($body, 'spen-mod-select') !== false){
				$question = new DropdownQuestion($this->client, $this->url);
			}else{
				throw new \RuntimeException('Not found question type');
			}
		}
		$result = $question->solve();
		if($result){
			Client::getLogger()->debug('Solved!');
		}else{
			Client::getLogger()->notice('Cannot solve - ' . get_class($question));
		}
	}
}