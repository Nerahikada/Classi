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

class VideoCourse{

	/** @var string */
	private $url;

	/** @var string */
	private $name;

	/** @var string */
	private $id;

	/** @var VideoLecture[] */
	private $lectures;

	public function __construct(string $url, string $name, string $id, array $lectures){
		$this->url = $url;
		$this->name = $name;
		$this->id = $id;
		$this->lectures = $lectures;
	}

	public function getUrl() : string{
		return $this->url;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getId() : string{
		return $this->id;
	}

	public function getLectures : array{
		return $this->lectures;
	}
}