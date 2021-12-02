<?php

namespace App\Controller;


use App\Entity\NotionPage;
use App\Entity\User;
use App\Service\NotionService;
use App\Service\OsuService;
use App\Service\SpotifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DefaultController extends AbstractController
{
	// /**
	//  * @var NotionService
	//  */
	// private $notionService;

	// public function __construct(NotionService $notionService){
	//     $this->notionService = $notionService;
	// }

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
		OsuService $osuService,
		SpotifyService $spotifyService,
		HttpClientInterface $httpClient,
		ParameterBagInterface $paramaterBag
	){
		$this->osuService = $osuService;
		$this->spotifyService = $spotifyService;
		$this->httpClient = $httpClient;
		$this->parameterBag = $paramaterBag;
	}
	

	/**
	 * @Route("/", name="default")
	 */
	public function index(): Response
	{
		//$osuT = $this->osuService->getOsuToken();
		//return $this->json($osuT);
	}

	/**
	 * @Route("/login", name="login")
	 */
	public function login(Request $request): Response
	{
		$params = json_decode($request->getContent(), true);

		if(!isset($params['username']) || empty($params['username'])){
			throw new HttpException(400, "Missing username parameters");
		}
		if(!isset($params['email']) || empty($params['email'])){
			throw new HttpException(400, "Missing email parameters");
		}

		$entitymanager = $this->getDoctrine()->getManager();

		$user = $entitymanager->getRepository(User::class)->findOneByEmail($params['email']);

		if(null == $user){
			$user = new User();
		}

		$user = new User();
		$user->setUsername($params['username'])
		->setEmail($params['email']);

		$entitymanager->persist($user);
		$entitymanager->flush();

		$returnArray = [
			'id' => $user->getId(),
			'username' => $user->getUsername(),
			'email' => $user->getEmail(),
		];

		return $this->json($params);
	}

	/**
	 * @Route("/error", name="error")
	 */
	public function error(): Response
	{
		return $this->json('error.');
	}

	/**
	 * @Route("/oauth", name="oauth")
	 */
	public function oauth(): Response
	{
		return $this->redirect('https://accounts.spotify.com/authorize?client_id=29ced1155da2459f8e661f5beac00a74&response_type=code&redirect_uri=http://127.0.0.1:8081/exchange_token&scope=user-read-private');
	}

	/**
	 * @Route("/lol", name="lol")
	 */
	public function lol(): Response
	{
		return $this->json("heyo");
		// return $this->json($spotifyAccessToken);
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
        // $this->osuService->getOsuToken($params['pseudo']);
		return $this->json($this->osuService->getOsuToken($params['pseudo']));
		// return $this->json($spotifyAccessToken);
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
				'redirect_uri' => 'http://127.0.0.1:8081/exchange_token',
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
		// return $spotifyAccessToken = $this->json($json_response['access_token']);
		return $this->redirect("http://127.00.1:8081/lol");
	}
}