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
		// $osuToken = $this->parameterBag->get('osu_token');
		$osuSecret = $this->parameterBag->get('osu_secret');
		$osuClientId = $this->parameterBag->get('osu_client_id');

		$osuSearchUrl = "https://osu.ppy.sh/api/v2/users/yosh1ko";
		
		$response = $this->httpClient->request('POST', $osuBaseUrl, [
			'headers'=>[
				
				'Accept' => 'application/json',
			],
			'body'=>[
				'client_id' => $osuClientId,
				'client_secret' => $osuSecret,
				'grant_type' => 'client_credentials',
				'scope' => 'public',
			],
		]);
		$token = json_decode($response->getContent(), true)["access_token"];
		$authorizationHeader = sprintf('Bearer %s', $token);
				$responseUserId = $this->httpClient->request('GET', $osuSearchUrl, [
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
				$userId = json_decode($responseUserId->getContent(), true)["id"];

				$osuSearchUserUrl = sprintf("https://osu.ppy.sh/api/v2/users/%s/beatmapsets/most_played?limit=20", $userId);

				$responseBeatmaps = $this->httpClient->request('GET', $osuSearchUserUrl, [
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


		// return json_decode($responseUserId->getContent(), true);
		return json_decode($responseBeatmaps->getContent(), true);
	}

	// public function blabla(): array
	// {
	// 	$token = $this->getOsuToken();

	// 	// $this->entityManager->flush();

	// 	return [];
	// }

}
