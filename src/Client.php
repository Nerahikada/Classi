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

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use PHPHtmlParser\Dom;

class Client{

	/** @var GuzzleHttpClient */
	private $httpClient;

	/** @var CookieJar */
	private $cookieJar;

	public function __construct(string $username, string $password){
		if(strpos($username, "SASSI") !== 0){
			throw new \RuntimeException("Invalid username");
		}

		echo "{$username} : {$password}\n";
	}
}