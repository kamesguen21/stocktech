<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\StockRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/stock")
 */
class StockController extends AbstractController
{
    /**
     * @var StockRepository
     */
    private $stockRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(StockRepository $stockRepository, LoggerInterface $logger)
    {
        $this->stockRepository = $stockRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="stock_index", methods={"GET"})
     * @param Request $request
     * @param StockRepository $stockRepository
     * @return Response
     * get admin stock page with pagination
     */
    public function index(Request $request): Response
    {
        $page = $request->query->get('p');
        $pages = null;
        if (!$page || !is_numeric($page)) {
            $page = 0;
        }
        $total = $this->stockRepository->count([]);
        $pages = ceil($total / 10);
        return $this->render('stock/index.html.twig', [
            'stocks' => $this->stockRepository->getPaginated($page),
            'total' => $total,
            'pages' => $pages,
            'page' => $page,
            'symbol' => '',

        ]);
    }

    /**
     * @Route("/search", name="stock_search", methods={"POST"})
     * @param Request $request
     * @return Response
     * search stocks
     */
    public function search(Request $request): Response
    {
        $this->logger->info("tes ". json_encode($request->request->get("symbol")));
        $symbol = $request->request->get('symbol');
        if(!$symbol || !isset($symbol)){
            $page = 0;
            $total = $this->stockRepository->count([]);
            $pages = ceil($total / 10);
            return $this->render('stock/index.html.twig', [
                'stocks' => $this->stockRepository->getPaginated($page),
                'total' => $total,
                'pages' => $pages,
                'page' => $page,
                'symbol' => '',
            ]);
        }
        return $this->render('stock/index.html.twig', [
            'stocks' => $this->stockRepository->findAllBySymbolLike($symbol),
            'pages' => 0,
            'symbol' => $symbol,
        ]);
    }

    /**
     * @Route("/save", name="stock_save", methods={"POST"})
     * @param Request $request
     * @return Response
     * ajax save  stock
     */
    public function save(Request $request): Response
    {
        $stock = new Stock();
        // $form = $this->createForm(StockType::class, $stock);
        //  $form->handleRequest($request->request->get('stock'));
        $data = $request->request->get("stock");
        if ($data && isset($data['symbol'])) {
            $stock->setSymbol($data['symbol']);
            if ($stock->getSymbol()) {
                if (!$this->stockRepository->findOneBySymbol($stock->getSymbol())) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($stock);
                    $entityManager->flush();
                    return $this->json(['success' => true]);
                } else {
                    return $this->json(['success' => false, 'msg' => 'symbol already exists']);
                }

            } else {
                return $this->json(['success' => false, 'msg' => 'symbol required']);
            }
        } else {
            return $this->json(['success' => false, 'msg' => 'symbol required']);
        }

    }

    /**
     * @Route("/{id}", name="stock_delete", methods={"POST"})
     * @param Request $request
     * @param Stock $stock
     * @return Response
     * ajax delete  stock
     */
    public function delete(Request $request,Stock $stock): Response
    {
        $data = $request->request->get("stock");
        if (!(isset($data['_token']) && $this->isCsrfTokenValid("", $data['_token']))) {
            return $this->json(['success' => false, 'msg' => 'invalid form key']);
        }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stock);
            $entityManager->flush();
        return $this->json(['success' => true]);

    }
    /**
     * @Route("/update", name="stock_update", methods={"PUT"})
     * @param Request $request
     * @return Response
     * ajax update stock
     */
    public function update(Request $request): Response
    {
        // $form = $this->createForm(StockType::class, $stock);
        //  $form->handleRequest($request->request->get('stock'));
        $data = $request->request->get("stock");
        if (!(isset($data['_token']) && $this->isCsrfTokenValid("", $data['_token']))) {
            return $this->json(['success' => false, 'msg' => 'invalid form key']);
        }
        if ($data && isset($data['symbol']) && isset($data['id'])) {
            $stock = $this->stockRepository->find($data['id']);
            if ($stock) {
                if (!$this->stockRepository->findOneBySymbolAndNotId($data['symbol'], $data['id'])) {
                    $stock->setSymbol($data['symbol']);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($stock);
                    $entityManager->flush();
                    return $this->json(['success' => true]);
                } else {
                    return $this->json(['success' => false, 'msg' => 'symbol already exist in another items']);
                }

            } else {
                return $this->json(['success' => false, 'msg' => 'symbol does not exist exists']);
            }


        } else {
            return $this->json(['success' => false, 'msg' => 'symbol required']);
        }
    }
}
