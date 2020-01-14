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

class InputQuestion extends SelectableQuestion{

	protected function parseChoices() : void{
		$dom = $this->client->getDom($this->url);
		foreach($dom->find('.spen-mod-input-text') as $input){
			$this->choices[] = null;
			$this->choiceNames[] = $input->name;
		}
	}

	protected function parseAnswers() : void{
		$dom = $this->client->getDom($this->url);
		foreach($dom->find('.answer-inner')->find('.clearfix') as $answerDom){
			// Input は dd の中に入ってる。例外はまだ見たことがない(かも)
			$answer = $answerDom->find('dd')->text;

			// 国語の問題などは () の中に別解が入ってたりするのでクソ
			$answer = preg_replace('/\(.+?\)/', '', $answer);
			$answer = preg_replace('/（.+?）/', '', $answer);

			$this->answers[] = trim($answer);
		}
	}

	protected function prepare() : array{
		if(count($this->choices) !== count($this->answers)){
			throw new \RuntimeException('Different number of choices and answers');
		}

		$result = [];
		foreach($this->choices as $key => $value){
			$result[$this->choiceNames[$key]][] = $this->answers[$key];
		}
		if(empty($result)){
			throw new \RuntimeException('Final result is empty');
		}
		return $result;
	}
}