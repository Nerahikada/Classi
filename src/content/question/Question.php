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
			if($input->name === 'answer_data[sections][][questions][][user_answer][]'){
				continue;
			}
			$this->postData[$input->name] = $input->value;
		}
	}

	protected function sendAnswer(bool $complete = false) : ResponseInterface{
		/**
		 * これはひどい。
		 * https://github.com/guzzle/guzzle/issues/1196#issuecomment-343624484
		 */
		$query = \GuzzleHttp\Psr7\build_query($this->postData, PHP_QUERY_RFC1738);


		if($complete){
			$query .= '&commit=完了する';
		}
		return $this->client->getHttpClient()->post($this->postUrl, [
			'body' => $query,
			'headers' => [
				'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8'
			]
		]);
	}

	/**
	 * 問題を解きます。返り値は成功したかどうかを表します。
	 */
	abstract function solve() : bool;
}