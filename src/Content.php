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

abstract class Content{

	/** @var Client */
	protected $client;

	/** @var string */
	protected $url;

	/** @var bool */
	protected $finished;

	public function __construct(Client $client, string $url, bool $finished){
		$this->client = $client;
		$this->url = $url;
		$this->finished = $finished;
		$this->init();
	}

	abstract public function getType() : string;

	// for other properties
	abstract protected function init() : void;

	abstract public function doHomework() : void;

	public function getUrl() : string{
		return $this->getUrl();
	}

	public function isFinished() : bool{
		return $this->finished;
	}
}