<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Service\OsuService;
use App\Service\SpotifyService;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DefaultController extends AbstractController
{
	/**
	 * @var UserService
	 */
	private $userService;

	/**
	 * @var OsuService
	 */
	private $osuService;

	/**
	 * @var SpotifyService
	 */
	private $spotifyService;

	/**
	 * @var HttpClientInterface
	 */
	private $httpClient;
	/**
	 * @var ParameterBagInterface
	 */
	private $parameterBag;

	public function __construct (
		UserService $userService,
		OsuService $osuService,
		SpotifyService $spotifyService,
		HttpClientInterface $httpClient,
		ParameterBagInterface $paramaterBag
	){
		$this->userService = $userService;
		$this->osuService = $osuService;
		$this->spotifyService = $spotifyService;
		$this->httpClient = $httpClient;
		$this->parameterBag = $paramaterBag;
	}

	/**
	 * @Route("/", name="default")
	 */
	public function index(Request $request): Response
	{
		$entityManager = $this->getDoctrine()->getManager();
		$userDb = $entityManager->getRepository(User::class)->findAll();
		/** @var User $user */
		$user = $this->userService->getUserFromRequest($request);
		return $user;
		if (null === $user) {
			return new Response('Unauthorized', 401);
		}
		$spotifyToken = $user->getTokenSpotify();
		$musicFromSpotify = $this->spotifyService->getOsuMusic($spotifyToken, $userDb);
		return $this->json($musicFromSpotify);
	}

	/**
	 * @Route("/oauth", name="oauth")
	 */
	public function oauth(): Response
	{
		return $this->redirect(
			'https://accounts.spotify.com/authorize?
			client_id=29ced1155da2459f8e661f5beac00a74&
			response_type=code&
			redirect_uri=http://uso-api.jael-beining.fr/exchange_token&scope=user-read-private,playlist-modify-private,playlist-modify-public'
			);
	}

	/**
	 * @Route("/pseudo", name="pseudo")
	 */
	public function pseudo(Request $request): Response
	{
		$params = json_decode($request->getContent(), true);
		if(!isset($params["pseudo"]) || empty($params['pseudo'])){
			throw new HttpException(400, 'Missing pseudo parameter.');
		}
		$osuT = $this->osuService->getOsuToken($params['pseudo']);
		$entityManager = $this->getDoctrine()->getManager();
		$userDb = $entityManager->getRepository(User::class)->findAll();
		$musicFromSpotify = $this->spotifyService->getOsuMusic($osuT, $userDb);
		return $this->json($musicFromSpotify);
	}
	
	/**
	 * @Route("/exchange_token", name="exchange_token")
	 */
	public function token(Request $request): Response
	{
		$authorization_code = $request->get('code');
		$spotifyClientId = $this->parameterBag->get('spotify_client_id');
		$spotifySecret = $this->parameterBag->get('spotify_secret');
		try {
			$body = [
				'redirect_uri' => 'http://uso-api.jael-beining.fr/exchange_token',
				'code' => $authorization_code,
				'grant_type' => 'authorization_code'
			];
			$basicAuth = base64_encode(sprintf('%s:%s', $spotifyClientId, $spotifySecret));
			$header = [
				'Authorization' => sprintf('Basic %s', $basicAuth),
				'Content-Type' => 'application/x-www-form-urlencoded'
			];
			$response = $this->httpClient->request(
				'POST',
				'https://accounts.spotify.com/api/token',
				[
					'body' => $body,
					'headers' => $header,
				]
			);
			$json_response = json_decode($response->getContent(), true);
		} catch (\Exception $e) {
			return $this->json($e->getMessage());
		}
		/** @var User $user */
		$entityManager = $this->getDoctrine()->getManager();
		$spotifyAccessToken = $json_response['access_token'];
		$bytes = random_bytes(20);
		$tokenUser = bin2hex($bytes);
		$newUser = new User();
		$newUser->setTokenSpotify($spotifyAccessToken);
		$newUser->setTokenUser($tokenUser);
		$entityManager->persist($newUser);
		$entityManager->flush();
		// return $this->redirect("http://127.00.1:8081/");
		return $this->redirect("http://uso.jael-beining.fr/getToken?token=" . $tokenUser);
	}

	/**
	 * @Route("/pseudo", name="pseudo")
	 */
	public function pseudo(Request $request): Response
	{
		$params = json_decode($request->getContent(), true);
		if(!isset($params["pseudo"]) || empty($params['pseudo'])){
			throw new HttpException(400, 'Missing pseudo parameter.');
		}
		$osuT = $this->osuService->getOsuToken($params['pseudo']);
		$entityManager = $this->getDoctrine()->getManager();
		$userDb = $entityManager->getRepository(User::class)->findAll();
		$lastToken = $this->spotifyService->getLastToken($userDb);
		$musicFromSpotify = $this->spotifyService->getOsuMusic($osuT, $lastToken);
		return $this->json($musicFromSpotify);
	}

	/**
	 * @Route("/createPlaylist", name="createPlaylist")
	 */
	public function createPlaylist(Request $request): Response
	{
		$data = json_decode($request->getContent(), true);
		/** @var User $user */
		$entityManager = $this->getDoctrine()->getManager();
		$userDb = $entityManager->getRepository(User::class)->findAll();
		$lastToken = $this->spotifyService->getLastToken($userDb);
		$rSpotify = $this->spotifyService->createPlaylist($lastToken, $data);
		return $this->json($rSpotify);
	}
	
	/**
	 * @Route("/error", name="error")
	 */
	public function error(): Response
	{
		return $this->json('error.');
	}
	
}

// ---------- Routes inutilisées ----------

	// /**
	//  * @Route("/userToken", name="userToken")
	//  */
	// public function userToken(Request $request): Response
	// {
	// 	/** @var User $user */
	// 	$user = $this->userService->getUserFromRequest($request);
	// 	// return $user;
	// 	if (null === $user) {
	// 		return new Response('Unauthorized', 401);
	// 	}
	// 	var_dump($user->getTokenSpotify());
	// 	$spotifyToken = $user->getTokenSpotify();
	// }

	// /**
	//  * @Route("/update", name="update")
	//  */
	// public function update(): Response
	// {
	// 	/** @var User $user */
	// 	$entityManager = $this->getDoctrine()->getManager();
	// 	$userDb = $entityManager->getRepository(User::class)->findAll();
	// 	$osuT = $this->osuService->getOsuToken();
	// 	$rSpotify = $this->spotifyService->updateSpotify($userDb, $osuT);
	// 	// var_dump($rSpotify);
	// 	return $this->json($rSpotify);
	// }
		
	// /**
	//  * @Route("/addMusic", name="addMusic")
	//  */
	// public function addMusic(): Response
	// {
	// 		/** @var User $user */
	// 		$entityManager = $this->getDoctrine()->getManager();
	// 		$userDb = $entityManager->getRepository(User::class)->findAll();

	// 	$aa = $this->spotifyService->addMusicToPlaylist($userDb);
	// 	return $this->json($aa);
	// }
	
	// /**
	//  * @Route("/removeMusic", name="removeMusic")
	//  */
	// public function removeMusic(): Response
	// {
	// 	/** @var User $user */
	// 	$entityManager = $this->getDoctrine()->getManager();
	// 	$userDb = $entityManager->getRepository(User::class)->findAll();
	// 	$aa = $this->spotifyService->removeMusicFromPlaylist($userDb);
	// 	return $this->json($aa);
	// }