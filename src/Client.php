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

namespace Classi;

use Analog\Handler\Ignore;
use Analog\Handler\Threshold;
use Analog\Logger;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use PHPHtmlParser\Dom;
use Psr\Log\LogLevel;

class Client{

	/** @var Logger */
	private $logger;

	/** @var GuzzleHttpClient */
	private $httpClient;

	public function __construct(string $username, string $password, int $loggingLevel = LogLevel::INFO){
		$this->logger = new Logger();
		$this->logger->handler(Threshold::init($loggingLevel, Ignore::init()));

		$this->logger->info("Logging testing.");
		$this->logger->debug("It is debugging message.");
		$this->logger->alert("Wow! THIS IS AN ALERT!!!");

		if(strpos($username, "SASSI") !== 0){
			throw new \RuntimeException("Invalid username");
		}

		$this->httpClient = new GuzzleHttpClient(['cookies' => true]);

		$response = $this->httpClient->post('https://login.benesse.ne.jp/beam/login', [
			'form_params' => [
				'usr_name' => $username,
				'usr_password' => $password,
				'next' => 'https://sas.benesse.ne.jp/schoolas/user/student/ASG302/'
			]
		]);
		if($response->getBody()->getSize() === null){
			throw new \RuntimeException("Failed to login");
		}
	}

	public function getDom(string $url) : Dom{
		$response = $this->httpClient->get($url);
		return (new Dom())->load((string) $response->getBody());
	}

	public function getHomeworkList() : array{
		$result = [];
		$dom = $this->getDom('https://video.classi.jp/student/challenge_delivery_history/challenge_delivery_history_school_in_studying');
		$array = $dom->find('.task-list')->find('a')->toArray();
		foreach(array_reverse($array) as $task){
			$delivery = new ChallengeDelivery(
				'https://video.classi.jp' . $task->href,
				$task->find('.name')->text,
				$task->find('.subject')->text
			);
			$dom = $this->getDom($delivery->getUrl());
			$tmp = $dom->find('.inner-block')->innerHtml();
			$challenge = new Challenge(
				$delivery->getUrl(),
				$dom->find('.heading-text')->text,
				$dom->find('.task-user-name')->text,
				$dom->find('.teacher-name')->text,
				$dom->find('.message_box')->text,
				Utility::getStringBetween($tmp, '配信日時</th><td>', '</td>'),
				Utility::getStringBetween($tmp, '取組期限</th><td>', '</td>'),
				Utility::getStringBetween($tmp, '最終取組</th><td>', '</td>'),
				(int) $dom->find('.myStat')->{'data-percent'},
				'https://video.classi.jp' . $dom->find('.navy-btn')->href
			);
			try{
				$dom = $this->getDom($challenge->getStartUrl());
			}catch(RequestException $e){
				// This challenge is not valid.
				continue;
			}
			$lectures = [];
			foreach($dom->find('.task-list')->find('a') as $task){
				//$lecture = new VideoLecture();
			}
		}
		return $list;
	}
}