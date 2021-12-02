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

	public function updateSpotify($userDb, $osuT):string
	// public function updateSpotify($userDb):string
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
		$headers = [
			'Authorization' => 'Bearer ' . $lastToken,
			'Content-Type' => 'application/json',
		];
		$body = '{}';
		$tracksUrl = "";
		// $newStr = sprintf("spotify:track:%s", $osuT[2]);
		$tracksUrl = urlencode("spotify:track:4iV5W9uYEdYUVa79Axb7Rh");
		// $tracksUrl = substr_replace($tracksUrl, $newStr, -0);
		return $tracksUrl;
		$url = "playlists/5kKpUnOPEWvnmDMMULBo9Y/tracks?uris=" . $tracksUrl;
		$searchUrl = $spotify_base_url . $url;
			$response = $this->httpClient->request('POST', $searchUrl, [
				'headers'=> $headers,
				'body' => json_encode($body),
			]);
		$json_r = json_decode($response->getContent(), true);
		return $json_r;
	}

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
		var_dump("test");
		foreach($userDb as $data){
			$return [] = [
				'id' => $data->getId(),
				'tokenSpotify' => $data->getTokenSpotify(),
			];
		}
		$lastToken = $return[sizeof($return) - 1]["tokenSpotify"];
		// foreach($osuT as $tt => $rr){
			// var_dump($rr["titre"] . "=>" . $rr["id"]);
			$osuTrack = $this->httpClient->request('GET', "https://api.spotify.com/v1/search?q=track:manifeste&type=track&market=FR&limit=2", [
				'headers'=>[
					'Accept' => 'application/json',
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => 'Bearer '. $lastToken,
				],
				'body'=>[
				],
			]);
			return $osuTrack;
			array_push($tab, $osuTrack);
		// }
		// return $tab;
	}



}
