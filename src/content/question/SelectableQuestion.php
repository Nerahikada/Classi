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

abstract class SelectableQuestion extends Question{

	/** @var array */
	protected $choices;

	/** @var array */
	protected $choiceNames;

	/** @var array */
	protected $answers;

	abstract protected function parseChoices() : void;

	abstract protected function parseAnswers() : void;

	/**
	 * 選択肢と答えから送信用のデータを作成し、返します。
	 */
	abstract protected function prepare() : array;

	public function solve() : bool{
		$this->parseChoices();
		$this->parseAnswers();
		$this->postData = array_merge($this->postData, $this->prepare());
		$response = $this->sendAnswer();
		$correct = strpos((string) $response->getBody(), 'answer-correct') !== false;
		if($correct){
			$this->sendAnswer(true);
		}
		return $correct;
	}
}