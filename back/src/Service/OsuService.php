<?php
namespace App\Service;
use App\Entity\OsuUser;
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
	public function getOsuToken(string $pseudo): array
	{
		$osuBaseUrl = $this->parameterBag->get('osu_base_url');
		$osuUserUrl = $this->parameterBag->get('osu_user_url');
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
		$osuUserPseudo = $pseudo;
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
			$userId = json_decode($responseUserId->getContent(), true)["id"];
			$userAvatar = json_decode($responseUserId->getContent(), true)["avatar_url"];
			$userBmapsCount = json_decode($responseUserId->getContent(), true)["beatmap_playcounts_count"];
			$musicRenderType = "most_played";
			$musicRenderLimit = 20;
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
		$returnTab = [];
		$musicInfos = json_decode($responseBeatmaps->getContent(), true);
		foreach ($musicInfos as $key => $v){
			$musicTitle = $v["beatmapset"]["title"];
			$musicTitle = str_replace(" (TV Size)", "", $musicTitle);
			$musicTitle = str_replace(" (TV edit)", "", $musicTitle);
			$musicTitle = str_replace(" [TV Size]", "", $musicTitle);
			$musicTitle = str_replace(" [Dictate Edit]", "", $musicTitle);
			$musicTitle = str_replace(" (Speed Up Ver.)", "", $musicTitle);
			$musicTitle = str_replace(" (Swing Arrangement)", "", $musicTitle);
			$musicTitle = str_replace(' (From "Kaguya-sama: Love is War")', "", $musicTitle);
			$musicTitle = str_replace('[ISORA arrange]', "", $musicTitle);
			// $musicTitle = str_replace(" 〜NARUTO OPENING MIX〜", "", $musicTitle);
			if (strpos($musicTitle, "feat")){
				$musicTitle = stristr($musicTitle, " feat", true);
			}
			$musicId = $musicInfos[$key]["beatmap"]["beatmapset_id"];
			array_push($musicTitleTab, ["id" => $musicId, "titre" => $musicTitle]);
		}
		array_push($returnTab, $musicTitleTab);
		array_push($returnTab, ["avatar" => $userAvatar, "baetmapsCount" => $userBmapsCount]);
		return $returnTab;
	}
}
