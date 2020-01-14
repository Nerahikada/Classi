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
use Psr\Http\Message\ResponseInterface;

abstract class Question{

	/** @var string */
	protected $postUrl;

	/** @var array */
	protected $postData;

	/** @var Client */
	protected $client;

	/** @var string */
	protected $url;

	public function __construct(Client $client, string $url){
		$this->client = $client;
		$this->url = $url;

		$dom = $client->getDom($url);
		$this->postUrl = 'https://video.classi.jp' . $dom->find('form')->action;
		foreach($dom->find('form')->find('input') as $input){
			$this->postData[$input->name] = $input->value;
		}
	}

	protected function sendAnswer() : ResponseInterface{
		return $this->client->getHttpClient()->post($this->postUrl, [
			'form_params' => $this->postData
		]);
	}

	/**
	 * 問題を解きます。返り値は成功したかどうかを表します。
	 */
	abstract function solve() : bool;
}