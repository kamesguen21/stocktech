<?php

namespace App\Controller;

use App\Entity\Ticker;
use App\Form\TickerType;
use App\Repository\TickerRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/ticker")
 */
class TickerController extends AbstractController
{
    /**
     * @var TickerRepository
     */
    private $tickerRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(TickerRepository $tickerRepository, LoggerInterface $logger)
    {
        $this->tickerRepository = $tickerRepository;
        $this->logger = $logger;

    }

    /**
     * @Route("/", name="ticker_index", methods={"GET"})
     * @param Request $request
     * @return Response
     * return ticker page with pagination
     */
    public function index(Request $request): Response
    {
        $page = $request->query->get('p');
        $pages = null;
        if (!$page || !is_numeric($page)) {
            $page = 0;
        }
        $total = $this->tickerRepository->count([]);
        $pages = ceil($total / 10);
        return $this->render('ticker/index.html.twig', [
            'tickers' => $this->tickerRepository->getPaginated($page),
            'total' => $total,
            'pages' => $pages,
            'page' => $page,
            'symbol' => '',
        ]);
    }

    /**
     * @Route("/search", name="ticker_search", methods={"POST"})
     * @param Request $request
     * @return Response
     * search ticker page
     */
    public function search(Request $request): Response
    {
        $symbol = $request->request->get('symbol');
        if (!$symbol || !isset($symbol)) {
            $page = 0;
            $total = $this->tickerRepository->count([]);
            $pages = ceil($total / 10);
            return $this->render('ticker/index.html.twig', [
                'tickers' => $this->tickerRepository->getPaginated($page),
                'total' => $total,
                'pages' => $pages,
                'page' => $page,
                'symbol' => '',
            ]);
        }
        return $this->render('ticker/index.html.twig', [
            'tickers' => $this->tickerRepository->findAllBySymbolLike($symbol),
            'pages' => 0,
            'symbol' => $symbol,
        ]);
    }

    /**
     * @Route("/new", name="ticker_new", methods={"GET","POST"})
     * create new ticker page
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $ticker = new Ticker();
        $form = $this->createForm(TickerType::class, $ticker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->tickerRepository->findByDateAndSymbol($ticker->getDate(), $ticker->getSymbol())) {
                $form->addError(new FormError("Entries with the same Date and Stock cannot exist "));
                return $this->render('ticker/new.html.twig', [
                    'ticker' => $ticker,
                    'form' => $form->createView(),
                ]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticker);
            $entityManager->flush();

            return $this->redirectToRoute('ticker_index');
        }

        return $this->render('ticker/new.html.twig', [
            'ticker' => $ticker,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ticker_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Ticker $ticker
     * @return Response
     * edit ticker page
     */
    public function edit(Request $request, Ticker $ticker): Response
    {
        $form = $this->createForm(TickerType::class, $ticker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->tickerRepository->findByDateAndSymbolAndNotId($ticker->getDate(), $ticker->getSymbol(), $ticker->getId())) {
                $form->addError(new FormError("Entries with the same Date and Stock cannot exist "));
                return $this->render('ticker/new.html.twig', [
                    'ticker' => $ticker,
                    'form' => $form->createView(),
                ]);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticker_index');
        }

        return $this->render('ticker/edit.html.twig', [
            'ticker' => $ticker,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticker_delete", methods={"POST"})
     * @param Request $request
     * @param Ticker $ticker
     * @return Response
     * delete ticker
     */
    public function delete(Request $request, Ticker $ticker): Response
    {
        if ($this->isCsrfTokenValid('', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticker);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ticker_index');
    }
}
