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

use Classi\content\Content;

class VideoLecture{

	/** @var string */
	private $url;

	/** @var string */
	private $title;

	/** @var string */
	private $name;

	/** @var Content[] */
	private $contents;

	public function __construct(string $url, string $title, string $name, array $contents){
		$this->url = $url;
		$this->title = $title;
		$this->name = $name;
		$this->contents = $contents;
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

	public function getContents() : array{
		return $this->contents;
	}
}