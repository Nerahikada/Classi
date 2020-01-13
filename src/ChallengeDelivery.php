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

class ChallengeDelivery{

	/** @var string */
	private $url;

	/** @var string */
	private $name;

	/** @var string */
	private $subject;

	public function __construct(string $url, string $name, string $subject){
		$this->url = $url;
		$this->name = $name;
		$this->subject = $subject;
	}

	public function getUrl() : string{
		return $this->url;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getSubject() : string{
		return $this->subject;
	}
}