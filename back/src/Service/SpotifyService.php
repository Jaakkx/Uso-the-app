<?php
namespace App\Service;
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

	public function getSpotifyPlaylists($token): array
	{
		$SpotifyDataTab = [];
		$spotifyBaseUrl = "https://api.spotify.com/v1/me/playlists";
		$response = $this->httpClient->request('GET', $spotifyBaseUrl, [
			'headers'=>[
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Authorization' => 'Bearer '. $token,
			],
		]);
		$response = json_decode($response->getContent(), true);
		$playlists = $response["items"];
		foreach($playlists as $key => $value){
			$playlistName = $playlists[$key]["name"];
			$playlistId = $playlists[$key]["id"];
			$playlistTrackUrl = sprintf("https://api.spotify.com/v1/playlists/%s/tracks?limit=100", $playlistId);
			$playlistTrack = $this->httpClient->request('GET', $playlistTrackUrl, [
				'headers'=>[
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => 'Bearer '. $token,
				],
				'body'=>[
				],
			]);
			$playlistTrack = json_decode($playlistTrack->getContent(), true);
			$playlistTrackMusics = $playlistTrack["items"];
			$MusicsTab = [];
			foreach($playlistTrackMusics as $key => $value){
				array_push($MusicsTab, $playlistTrackMusics[$key]["track"]["name"]);
			}
			array_push($SpotifyDataTab, [$playlistName => $MusicsTab]);
		}
		return $SpotifyDataTab;
	}

	// public function updateSpotify($userDb, $osuT):string
	// // public function updateSpotify($userDb):string
	// {
	// 	// EN FAIRE UNE FONCTION A PART ENTIERE
	// 	$spotify_base_url = $this->parameterBag->get('spotify_base_url');
	// 	foreach($userDb as $data){
	// 		$return [] = [
	// 			'id' => $data->getId(),
	// 			'tokenSpotify' => $data->getTokenSpotify(),
	// 		];
	// 	}
	// 	$lastToken = $return[sizeof($return) - 1]["tokenSpotify"];
	// 	$headers = [
	// 		'Authorization' => 'Bearer ' . $lastToken,
	// 		'Content-Type' => 'application/json',
	// 	];
	// 	$body = '{}';
	// 	$tracksUrl = "";
	// 	$tracksUrl = urlencode("spotify:track:4iV5W9uYEdYUVa79Axb7Rh");
	// 	// $tracksUrl = substr_replace($tracksUrl, $newStr, -0);
	// 	return $tracksUrl;
	// 	$url = "playlists/5kKpUnOPEWvnmDMMULBo9Y/tracks?uris=" . $tracksUrl;
	// 	$searchUrl = $spotify_base_url . $url;
	// 		$response = $this->httpClient->request('POST', $searchUrl, [
	// 			'headers'=> $headers,
	// 			'body' => json_encode($body),
	// 		]);
	// 	$json_r = json_decode($response->getContent(), true);
	// 	return $json_r;
	// }

	public function createPlaylist($userDb):array
	{
		// EN FAIRE UNE FONCTION A PART ENTIERE
		$spotify_base_url = $this->parameterBag->get('spotify_base_url');
		foreach($userDb as $data){
			$return [] = [
				'id' => $data->getId(),
				'tokenSpotify' => $data->getTokenSpotify(),
			];
		}
		$lastToken = $return[sizeof($return) - 1]["tokenSpotify"];
		$newPlaylistName = "Nvlle playlist";
		$newPlaylistDesc = "Playlist test API";
		$newPlaylistState = 'true';
		$playlistData = sprintf('{"name": "%s", "description": "%s", "public": %s}', $newPlaylistName, $newPlaylistDesc, $newPlaylistState);
		$userId = "a1ex-ksb";
		$urlCreatePlaylist = sprintf('%susers/%s/playlists',$spotify_base_url, $userId);
		$createPlaylist = $this->httpClient->request('POST', $urlCreatePlaylist,[
			'headers' =>[
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer '. $lastToken,
			],
			'body' => $playlistData
		]);
		return json_decode($createPlaylist->getContent(),true);
	}

	public function getOsuMusic($osuT, $userDb){
		$tab = [];
		foreach($userDb as $data){
			$return [] = [
				'id' => $data->getId(),
				'tokenSpotify' => $data->getTokenSpotify(),
			];
		}
		$lastToken = $return[sizeof($return) - 1]["tokenSpotify"];
		foreach($osuT as $tt => $rr){
			// var_dump($rr["titre"] . "=>" . $rr["id"]);
			$title = $rr["titre"];
			// var_dump($title);
			$urlMusics = sprintf("https://api.spotify.com/v1/search?q=track:%s&type=track&market=FR&limit=1", $title);
			$osuTrack = $this->httpClient->request('GET', $urlMusics, [
			// $osuTrack = $this->httpClient->request('GET', "https://api.spotify.com/v1/search?q=track:manifeste&type=track&market=FR&limit=1", [
				'headers'=>[
					'Accept' => 'application/json',
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => 'Bearer '. $lastToken,
				],
				'body'=>[
				],
			]);
			$music = json_decode($osuTrack->getContent(), true);
			// return $music["tracks"]["items"][0];
			if (isset($music["tracks"]["items"][0]["name"])){
				$musicId = $music["tracks"]["items"][0]["id"];
				$musicTitle = $music["tracks"]["items"][0]["name"];
				array_push($tab, ["id" => $musicId, "title" => $musicTitle]);
			}
			// var_dump($music["tracks"]["items"][0]["name"]);
			// array_push($tab, $music["tracks"]["items"][0]["name"]);
		}
		return $tab;
	}

	public function addMusicToPlaylist($userDb){
		$spotify_base_url = $this->parameterBag->get('spotify_base_url');
		foreach($userDb as $data){
			$return [] = [
				'id' => $data->getId(),
				'tokenSpotify' => $data->getTokenSpotify(),
			];
		}
		$lastToken = $return[sizeof($return) - 1]["tokenSpotify"];
		$headers = [
			'Authorization' => 'Bearer ' . $lastToken,
			'Content-Type' => 'application/json',
		];
		$body = '{}';

		// A RECUPERER DEPUIS LE FRONT
		$musicId = "5nF4iejReWqvsplMp6eer0";
		
		$tracksUrl = "";
		$tracksUrl = sprintf("spotify:track:%s", $musicId);
		$tracksUrl = urlencode($tracksUrl);

		// A RECUPERER DEPUIS LE FRONT
		$playlistId = "7MfKBPKQI0hPwuKo2oIA0D";

		$url = sprintf("playlists/%s/tracks?uris=%s", $playlistId, $tracksUrl);
		$searchUrl = $spotify_base_url . $url;
			$response = $this->httpClient->request('POST', $searchUrl, [
				'headers'=> $headers,
				'body' => json_encode($body),
			]);
		$json_r = json_decode($response->getContent(), true);
	}

	public function removeMusicFromPlaylist($userDb){
		$spotify_base_url = $this->parameterBag->get('spotify_base_url');
		foreach($userDb as $data){
			$return [] = [
				'id' => $data->getId(),
				'tokenSpotify' => $data->getTokenSpotify(),
			];
		}
		$lastToken = $return[sizeof($return) - 1]["tokenSpotify"];
		$headers = [
			'Authorization' => 'Bearer ' . $lastToken,
			'Content-Type' => 'application/json',
		];



		$body = '{"tracks": [{"uri": "spotify:track:5nF4iejReWqvsplMp6eer0",},]}';



// { "tracks": [{ "uri": "spotify:track:4iV5W9uYEdYUVa79Axb7Rh" },{ "uri": "spotify:track:1301WleyT98MSxVHPZCA6M" }] }


		// A RECUPERER DEPUIS LE FRONT
		$musicId = "5nF4iejReWqvsplMp6eer0";
		
		$tracksUrl = "";
		$tracksUrl = sprintf("spotify:track:%s", $musicId);
		$tracksUrl = urlencode($tracksUrl);

		// A RECUPERER DEPUIS LE FRONT
		$playlistId = "7MfKBPKQI0hPwuKo2oIA0D";

		$url = sprintf("playlists/%s/tracks", $playlistId);
		$searchUrl = $spotify_base_url . $url;
			$response = $this->httpClient->request('DELETE', $searchUrl, [
				'headers'=> $headers,
				// 'body' => $body,
				'body' => json_encode($body),
			]);
		$json_r = json_decode($response->getContent(), true);
	}

}
