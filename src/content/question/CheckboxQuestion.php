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

class CheckboxQuestion extends SelectableQuestion{

	protected function parseChoices() : void{
		$dom = $this->client->getDom($this->url);
		foreach($dom->find('.selectors-preview-list')->find('li') as $list){
			$input = $list->find('.checkbox');
			$choice = trim($list->find('.select-substance')->text);

			// なんでかよく分からないけど、選択肢に画像が使われているんよ、うん。
			$img = $list->find('.select-substance')->find('img');
			if($img->count() > 0){
				$choice = $img->src;
			}

			$this->choices[(int) $input->value] = $choice;
			/**
			 * answer_data[sections][][questions][][user_answer][] を
			 * answer_data[sections][][questions][][user_answer] にするために
			 * ごり押し！ゴリラ！！！！
			 */
			$this->choiceNames[(int) $input->value] = substr($input->name, 0, strlen($input->name) - 2);
		}
	}

	protected function parseAnswers() : void{
		$dom = $this->client->responseToDom($this->sendAnswer());
		// foreach 使ってるけど、Checkboxの答え一つしかなかったような希ガス
		// そもそも answers って名前でよかったのかも謎
		foreach($dom->find('.answer-inner')->find('.clearfix') as $answerDom){
			$answer = ($dd = $answerDom->find('dd'))->count() > 0 ? $dd->text : $answerDom->text;
			$answer = trim($answer);
			if(empty($answer)){
				$img = $answerDom->find('.img');
				if($img->count() === 0){
					throw new \RuntimeException('Answer is empty');
				}
				$answers = $img->src;
			}
			$this->answers[] = $answer;
		}
	}

	protected function prepare() : array{
		$result = [];
		foreach($this->answers as $answer){
			foreach($this->choices as $key => $value){
				if($answer == $value){	// ここは適度改修が必要かも…？
					$result[$this->choiceNames[$key]][] = $key;	// こここれでいいのかな…？
				}
			}
		}
		if(empty($result)){
			throw new \RuntimeException('Cannot find answer matched choice');
		}
		return $result;
	}
}