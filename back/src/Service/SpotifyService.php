<?php

namespace App\Service;

// use App\Entity\NotionPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class SpotifyService
{
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var HttpClientInterface
	 */
	private $httpClient;

	/**
	 * @var ParameterBagInterface
	 */
	private $parameterBag;


	public function __construct(
		EntityManagerInterface $entityManager, 
		HttpClientInterface $httpClient, 
		ParameterBagInterface $paramaterBag)
	{
		$this->entityManager = $entityManager;
		$this->httpClient = $httpClient;
		$this->parameterBag = $paramaterBag;
	}
	public function getSpotifyToken()
	{
		// $osuBaseUrl = $this->parameterBag->get('osu_base_url');
		// $osuSecret = $this->parameterBag->get('osu_secret');
		// $osuClientId = $this->parameterBag->get('osu_client_id');

		$currentUrl = $_SERVER["REQUEST_URI"];
		// var_dump($currentUrl);
		if (strpos($currentUrl, "code")){
			$spotifyCode = str_replace("/?code=", "", $currentUrl);
			// var_dump($spotifyCode);
		}

		$spotifyBaseUrl = "https://accounts.spotify.com/api/token";

		$response = $this->httpClient->request('POST', $spotifyBaseUrl, [
			'headers'=>[
				
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Authorization' => 'Basic ' . base64_encode("29ced1155da2459f8e661f5beac00a74" . ":" . "aff5a5f6a4a342d5b9daaa02119eac56"),
				// 'Authorization' => "Basic MjljZWQxMTU1ZGEyNDU5ZjhlNjYxZjViZWFjMDBhNzQ6YWZmNWE1ZjZhNGEzNDJkNWI5ZGFhYTAyMTE5ZWFjNTY=",
			],
			'body'=>[
				'grant_type' => "authorization_code",
				'redirect_uri' => "http://127.0.0.1:8081/",
				'code' => $spotifyCode,
			],
		]);
		return; $response;
	}

	// public function blabla(): array
	// {
	// 	$token = $this->getOsuToken();

	// 	// $this->entityManager->flush();

	// 	return [];
	// }

}
