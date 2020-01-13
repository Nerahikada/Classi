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

class Challenge{

	/** @var string */
	private $url;

	/** @var string */
	private $title;

	/** @var string */
	private $name;

	/** @var string */
	private $teacherName;

	/** @var string */
	private $teacherMessage;

	/** @var \DateTimeImmutable */
	private $deliveryDate;
	
	/** @var ?\DateTimeImmutable */
	private $deadline = null;

	/** @var ?\DateTimeImmutable */
	private $lastUpdate = null;

	/** @var int */
	private $progress;

	/** @var string */
	private $startUrl;

	public function __construct(string $url, string $title, string $name, string $teacherName, string $teacherMessage, $deliveryDate, $deadline = null, $lastUpdate = null, int $progress, string $startUrl){
		$this->url = $url;
		$this->title = $title;
		$this->name = $name;
		$this->teacherName = $teacherName;
		$this->teacherMessage = $teacherMessage;
		$this->deliveryDate = $this->convertDate($deliveryDate);
		$this->deadline = $this->convertDate($deadline);
		$this->lastUpdate = $this->convertDate($lastUpdate);
		if($progress < 0 || $progress > 100){
			throw new \RuntimeException('Progress must be in the range 1-100');
		}
		$this->progress = $progress;
		$this->startUrl = $startUrl;
	}

	private function convertDate($date) : ?\DateTimeImmutable{
		if($date instanceof \DateTimeImmutable){
			return $date;
		}
		if($date instanceof \DateTime){
			return \DateTimeImmutable::createFromMutable($date);
		}
		if(empty($date) || $date === '-'){
			return null;
		}
		try{
			return new \DateTimeImmutable($date, new \DateTimeZone('Asia/Tokyo'));
		}catch(\Exception $e){
			throw new \InvalidArgumentException('Invalid value');
		}
	}

	public function getUrl() : string{
		return $this->url;
	}

	public function getTitle() : string{
		return $this->title;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getTeacherName() : string{
		return $this->teacherName;
	}

	public function getTeacherMessage() : string{
		return $this->teacherMessage;
	}

	public function getDeliveryDate() : ?\DateTimeImmutable{
		return $this->deliveryDate;
	}

	public function getDeadline() : ?\DateTimeImmutable{
		return $this->deadline;
	}

	public function getLastUpdate() : ?\DateTimeImmutable{
		return $this->lastUpdate;
	}

	public function getProgress() : int{
		return $this->progress;
	}

	public function getStartUrl() : string{
		return $this->startUrl;
	}
}