<?php

namespace App\Controller;

use App\Entity\Description;
use App\Form\DescriptionType;
use App\Repository\DescriptionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/description")
 */
class DescriptionController extends AbstractController
{
    /**
     * @var DescriptionRepository
     */
    private $descriptionRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(DescriptionRepository $descriptionRepository, LoggerInterface $logger)
    {
        $this->descriptionRepository=$descriptionRepository;
        $this->logger = $logger;

    }

    /**
     * @Route("/", name="description_index", methods={"GET"})
     * @param Request $request
     * @return Response
     * description admin page
     */
    public function index(Request $request): Response
    {
        $page = $request->query->get('p');
        $pages = null;
        if (!$page || !is_numeric($page)) {
            $page = 0;
        }
        $total = $this->descriptionRepository->count([]);
        $pages = ceil($total / 10);
        return $this->render('description/index.html.twig', [
            'descriptions' =>   $this->descriptionRepository->getPaginated($page),
            'total' => $total,
            'pages' => $pages,
            'page' => $page,
            'symbol' => '',
        ]);
    }
    /**
     * @Route("/search", name="description_search", methods={"POST"})
     * @param Request $request
     * @return Response
     * search descriptions return descriptions with symbol like request[symbol]
     */
    public function search(Request $request): Response
    {
        $symbol = $request->request->get('symbol');
        if(!$symbol || !isset($symbol)){
            $page = 0;
            $total = $this->descriptionRepository->count([]);
            $pages = ceil($total / 10);
            return $this->render('description/index.html.twig', [
                'descriptions' => $this->descriptionRepository->getPaginated($page),
                'total' => $total,
                'pages' => $pages,
                'page' => $page,
                'symbol' => '',
            ]);
        }
        return $this->render('description/index.html.twig', [
            'descriptions' => $this->descriptionRepository->findAllBySymbolLike($symbol),
            'pages' => 0,
            'symbol' => $symbol,
        ]);
    }
    /**
     * @Route("/new", name="description_new", methods={"GET","POST"})
     * create new description
     *
     */
    public function new(Request $request): Response
    {
        $description = new Description();
        $form = $this->createForm(DescriptionType::class, $description);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->descriptionRepository->findBySymbol($description->getSymbol())){
                $form->addError(new FormError("Entries with the same Date and Stock cannot exist "));
                return $this->render('description/new.html.twig', [
                    'description' => $description,
                    'form' => $form->createView(),
                ]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($description);
            $entityManager->flush();

            return $this->redirectToRoute('description_index');
        }

        return $this->render('description/new.html.twig', [
            'description' => $description,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="description_show", methods={"GET"})
     * view description
     */
    public function show(Description $description): Response
    {
        return $this->render('description/show.html.twig', [
            'description' => $description,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="description_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Description $description
     * @return Response
     * update description
     */
    public function edit(Request $request, Description $description): Response
    {
        $form = $this->createForm(DescriptionType::class, $description);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->descriptionRepository->findOneBySymbolAndNotId($description->getSymbol(),$description->getId())){
                $form->addError(new FormError("Entries with the same Date and Stock cannot exist "));
                return $this->render('description/new.html.twig', [
                    'description' => $description,
                    'form' => $form->createView(),
                ]);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('description_index');
        }

        return $this->render('description/edit.html.twig', [
            'description' => $description,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="description_delete", methods={"POST"})
     * delete description
     */
    public function delete(Request $request, Description $description): Response
    {
        if ($this->isCsrfTokenValid('', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($description);
            $entityManager->flush();
        }

        return $this->redirectToRoute('description_index');
    }
}
