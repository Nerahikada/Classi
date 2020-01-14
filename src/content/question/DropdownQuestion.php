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

class DropdownQuestion extends SelectableQuestion{

	protected function parseChoices() : void{
		$dom = $this->client->getDom($this->url);
		foreach($dom->find('.spen-mod-select') as $select){
			$choice = [];
			foreach($select->find('.select-substance') as $s){
				$choice[] = $s->text;
			}
			$this->choices[] = $choice;
			$this->choiceNames[] = $select->find('input')->name;
		}
	}

	protected function parseAnswers() : void{
		$dom = $this->client->responseToDom($this->sendAnswer());
		foreach($dom->find('.answer-inner') as $answerDom){
			foreach($answerDom->find('dd') as $key => $dd){
				$answer = $dd->text;

				// 一応括弧を取り除く
				$answer = preg_replace('/\(.+?\)/', '', $answer);
				$answer = preg_replace('/（.+?）/', '', $answer);
				$answer = trim($answer);

				// たまーに余計な文字が一緒にくっついてくることがある。クソ。
				// 大体はマルチバイトなのでテキトーに処理する。 (大体は)
				$tmp = mb_substr($answer, 0, 1);
				if(strlen($tmp) !== mb_strlen($tmp)){
					$tmp2 = mb_substr($answer, 1, mb_strlen($answer) - 1);
					$search = array_search($tmp2, $this->choices[$key], true);
					if($search !== false){
						$answer = $tmp2;
					}
				}

				$this->answers[] = $answer;
			}
		}
	}

	protected function prepare() : array{
		if(count($this->choices) !== count($this->answers)){
			throw new \RuntimeException('Different number of choices and answers');
		}

		$result = [];
		foreach($this->choices as $key => $choices){
			$answer = $this->answers[$key];
			foreach($choices as $key2 => $choice){
				if($answer === $choice){
					$result[$this->choiceNames[$key]][] = $key2;
					break;
				}
			}
		}
		if(empty($result)){
			throw new \RuntimeException('Final result is empty');
		}
		return $result;
	}
}