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

use Analog\Analog;
use Analog\Handler\Stderr;
use Analog\Handler\Threshold;
use Analog\Logger;
use Classi\content\ProgramContent;
use Classi\content\VideoContent;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use PHPHtmlParser\Dom;

class Client{

	const TIMEZONE = 'Asia/Tokyo';

	/** @var Logger */
	private static $logger;

	/** @var GuzzleHttpClient */
	private $httpClient;

	public function __construct(string $username, string $password, int $loggingLevel = Analog::INFO){
		self::$logger = new Logger();
		self::$logger->handler(Threshold::init(
			function($info, $buffered = false){
				echo ($buffered ? $info : vsprintf(Analog::$format, $info));
			}, $loggingLevel));
		//self::$logger->format('[%2$s/%3$d] %4$s' . PHP_EOL);
		self::$logger->format('[%2$s] %4$s' . PHP_EOL);
		Analog::$timezone = self::TIMEZONE;

		if(strpos($username, 'SASSI') !== 0){
			throw new \RuntimeException('Invalid username');
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
		$this->getLogger()->info("Login successful");
	}

	public static function getLogger() : Logger{
		return self::$logger;
	}

	public function getHttpClient() : GuzzleHttpClient{
		return $this->httpClient;
	}

	public function getDom(string $url) : Dom{
		$response = $this->httpClient->get($url);
		return (new Dom())->load((string) $response->getBody());
	}

	/**
	 * @return VideoCourse[]
	 */
	public function getHomeworkList() : array{
		$result = [];
		$this->getLogger()->debug('Getting all delivered challenge history...');
		$dom = $this->getDom('https://video.classi.jp/student/challenge_delivery_history/challenge_delivery_history_school_in_studying');
		$array = $dom->find('.task-list')->find('a')->toArray();
		foreach(array_reverse($array) as $task){
			$delivery = new ChallengeDelivery(
				'https://video.classi.jp' . $task->href,
				$task->find('.name')->text,
				$task->find('.subject')->text
			);
			$this->getLogger()->debug('Delivered Challenge: ' . $delivery->getName());
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
			$this->getLogger()->debug('Challenge: ' . $challenge->getName());
			try{
				$courseDom = $this->getDom($challenge->getStartUrl());
			}catch(RequestException $e){
				// This challenge is not valid.
				continue;
			}

			$lectures = [];
			foreach($courseDom->find('.task-list')->find('a') as $task){
				$lectureDom = $this->getDom(($lectureUrl = 'https://video.classi.jp' . $task->href));
				$contents = [];
				foreach($lectureDom->find('.video_lecture_content') as $lecture){
					$dom = $this->getDom(($url = 'https://video.classi.jp' . $lecture->href));
					$type = $dom->find('#content_type')->value;
					$tmp = ['video' => VideoContent::class, 'program' => ProgramContent::class];
					$contents[] = new $tmp[$type]($this, $url, ($lecture->find('.check-mark')->count !== 0));
					$this->getLogger()->debug('Content: ' . $type);
				}
				$lectures[] = new VideoLecture($lectureUrl, $lectureDom->find('h1')->text, $lectureDom->find('h2')->text, $contents);
				$this->getLogger()->debug('Lecture: ' . $lectureDom->find('h2')->text);
			}
			$course = new VideoCourse(
				$challenge->getStartUrl(),
				$courseDom->find('.heading-text')->text,
				Utility::trimId($courseDom->find('.course-ID')->text),
				$lectures
			);
			$result[] = $course;
			$this->getLogger()->debug('Course: ' . $course->getName());
		}
		return $result;
	}
}