<?php

namespace App\Service;

use App\Entity\NotionPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class NotionService
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
	public function getNotionPages(): array
	{
		$notionBaseUrl = $this->parameterBag->get('notion_base_url');
		$notionToken = $this->parameterBag->get('notion_token');

		$notionSearchUrl = sprintf('%s/search', $notionBaseUrl);
		$authorizationHeader = sprintf('Bearer %s', $notionToken);

		$response = $this->httpClient->request('POST', $notionSearchUrl, [
			'body'=>[
				'query'=>'',
			],
			'headers'=>[
				'Authorization'=>$authorizationHeader,
				'Notion-Version'=>'2021-08-16',
			],
		]);


		return json_decode($response->getContent(), true);
	}

	public function storeNotionPages(): array
	{
		$pages = $this->getNotionPages();
		$notionsPages = [];

		foreach($pages['results'] as $page){
			$existingNotionPage = $this->entityManager->getRepository(NotionPage::class)->findOneByNotionId($page['id']);

			if(null !== $existingNotionPage){
				continue;
			}
		}

		foreach ($pages['results'] as $page)
		{
			if (isset($page['properties']['title'])){
				$title = substr($page['properties']['title']['title'][0]['plain_text'], 0, 255);
			} else{
				$title = 'No title';
			}
			$creationDate = new \DateTime($page['created_time']);

			$notionPage = new NotionPage();
			$notionPage->setNotionId($page['id']);
			$notionPage->setTitle($page['properties']['title']['title'][0]['plain_text']);
			$notionPage->setCreationDate($creationDate);

			$notionsPages[] = $notionPage;
			$this->entityManager->persist($notionPage);
		}
		$this->entityManager->flush();

		return [];
	}

}
