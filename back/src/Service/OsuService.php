<?php

namespace App\Service;

// use App\Entity\NotionPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class OsuService
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
	public function getOsuToken(): array
	{
		$osuBaseUrl = $this->parameterBag->get('osu_base_url');
		$osuUserUrl = $this->parameterBag->get('osu_user_url');
		// $osuMusicUrl = $this->parameterBag->get('osu_music_url');
		$osuSecret = $this->parameterBag->get('osu_secret');
		$osuClientId = $this->parameterBag->get('osu_client_id');

		
		$response = $this->httpClient->request('POST', $osuBaseUrl, [
			'headers'=>['Accept' => 'application/json'],
			'body'=>[
				'client_id' => $osuClientId,
				'client_secret' => $osuSecret,
				'grant_type' => 'client_credentials',
				'scope' => 'public',
			],
		]);
		$token = json_decode($response->getContent(), true)["access_token"];
		$authorizationHeader = sprintf('Bearer %s', $token);
		// A DEFINIR EN FRONT
		$osuUserPseudo = "yosh1ko";
		$osuUserIdUrl = $osuUserUrl . $osuUserPseudo;
		$responseUserId = $this->httpClient->request('GET', $osuUserIdUrl, [
					'headers'=>[
						'Accept' => 'application/json',
						'Authorization' => $authorizationHeader,
					],
					'body'=>[
						'client_id' => $osuClientId,
						'client_secret' => $osuSecret,
						'grant_type' => 'client_credentials',
						'scope' => 'public',
					],
				]);

				// A DEFINIR EN FRONT
				$userId = json_decode($responseUserId->getContent(), true)["id"];
				$musicRenderType = "most_played";
				$musicRenderLimit = 4;

				$osuMusicSearchUrl = sprintf("https://osu.ppy.sh/api/v2/users/%s/beatmapsets/%s?limit=%s", $userId, $musicRenderType, $musicRenderLimit);
				$responseBeatmaps = $this->httpClient->request('GET', $osuMusicSearchUrl, [
					'headers'=>[
						'Accept' => 'application/json',
						'Authorization' => $authorizationHeader,
					],
					'body'=>[
						'client_id' => $osuClientId,
						'client_secret' => $osuSecret,
						'grant_type' => 'client_credentials',
						'scope' => 'public',
					],
				]);

		$musicTitleTab = [];
		$musicInfos = json_decode($responseBeatmaps->getContent(), true);
		foreach ($musicInfos as $key => $v){
			$musicTitle = $musicInfos[$key]["beatmapset"]["title"];
			$musicTitle = str_replace(" (TV Size)", "", $musicTitle);
			$musicTitle = str_replace(" [Dictate Edit]", "", $musicTitle);
			var_dump($musicTitle);
			array_push($musicTitleTab, $musicTitle);
		}
		return $musicTitleTab;
	}
}