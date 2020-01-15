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

class TrueFalseQuestion extends SelectableQuestion{

	protected function parseChoices() : void{
		$dom = $this->client->getDom($this->url);
		foreach($dom->find('.spen-mod-true-false-radio-box') as $dom2){
			$this->choices[] = [
				'o' => 0, 
				'x' => 1
			];
			$this->choiceNames[] = $dom2->find('input')->name;
		}
	}

	protected function parseAnswers() : void{
		$dom = $this->client->responseToDom($this->sendAnswer());
		foreach($dom->find('.answer-inner')->find('.select-substance')->find('i') as $i){
			if(strpos($i->class, 'fa-circle-o') !== false){
				$answer = 'o';
			}elseif(strpos($i->class, 'fa-times') !== false){
				$answer = 'x';
			}else{
				throw new \RuntimeException('Unknown answer');
			}
			$this->answers[] = $answer;
		}
	}

	protected function prepare() : array{
		if(count($this->choices) !== count($this->answers)){
			throw new \RuntimeException('Different number of choices and answers');
		}

		$result = [];
		foreach($this->answers as $key => $value){
			$result[$this->choiceNames[$key]][] = $this->choices[$key][$value];
		}
		if(empty($result)){
			throw new \RuntimeException('Final result is empty');
		}
		return $result;
	}
}