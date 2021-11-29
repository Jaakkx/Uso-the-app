<?php

namespace App\Controller;


use App\Entity\NotionPage;
use App\Entity\User;
use App\Service\NotionService;
use App\Service\OsuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct (OsuService $osuService){
        $this->osuService = $osuService;
    }


    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        // $pages = $this->notionService->getNotionPages();
        $osuT = $this->osuService->getOsuToken();
        // return $this->json('hello world');
        return $this->json($osuT);
        // return $this->json($osuT);
    }

        /**
     * @Route("/savepages", name="savePages")
     */
    public function savePages(): Response
    {
        $this->notionService->storeNotionPages();

        return $this->json("Notion pages saved.");
    }

        /**
     * @Route("/notionpages", name="notionPages")
     */
    public function getNotionPages(): Response
    {
        $pages = $this->getDoctrine()->getRepository(NotionPage::class)->findAll();

        $returnArray = [];

        /** @var NotionPage $page */
        foreach($pages as $page){
            $returnArray[] = [
                'id' => $page -> getId(),
                'notionId' => $page->getNotionId(),
                'title' => $page->getTitle(),
                'creationDate' => $page->getCreationDate()->format(DATE_ATOM),
            ];
        }
        return $this->json($returnArray);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request): Response
    {
        $params = json_decode($request->getContent(), true);

        if (!isset($params['email']) || empty($params['email'])) {
            throw new HttpException(400, 'Missing email parameter.');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->findOneByEmail($params['email']);

        if (null === $user) {
            $user = new User();
        }

        $user->setEmail($params['email']);

        $entityManager->persist($user);
        $entityManager->flush();

        $returnArray = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ];

        return $this->json($returnArray);
    }

    /**
     * @Route("/error", name="error")
     */
    public function error(): Response
    {
        return $this->json('error.');
    }
}
