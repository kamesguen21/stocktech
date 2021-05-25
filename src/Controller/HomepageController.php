<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\Stock;
use App\Repository\NewsRepository;
use App\Repository\StockRepository;
use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomepageController extends AbstractController
{

    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var StockRepository
     */
    private $stockRepository;

    public function __construct(
        HttpClientInterface $client,
        NewsRepository $newsRepository,
        StockRepository $stockRepository,
        LoggerInterface $logger
    )
    {
        $this->stockRepository = $stockRepository;
        $this->newsRepository = $newsRepository;
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="homepage")
     * return homepage page
     */
    public function index(): Response
    {
        $total = $this->stockRepository->count([]);
        $pages = ceil($total / 10);

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'stocks' => $this->stockRepository->getPaginated(1, 10),
            'total' => $total,
            'pages' => $pages,
            'page' => 1,
            'async' => true,
        ]);
    }

    /**
     * @Route("/search/{term}", name="stock_search_front")
     * @param string $term
     * @return Response
     * ajax search stocks
     */
    public function search(string $term = null): Response
    {

        return $this->json(["success" => true, "data" => $this->stockRepository->findAllBySymbolLike($term, true)]);
    }

    /**
     * @Route("/market/stocks/page", name="market_stocks_page")
     * @param Request $request
     * @return Response
     * ajax get stocks page
     */
    public function getPaginatedStocks(Request $request): Response
    {
        $page = $request->query->get('p');
        $pages = null;
        if (!$page || !is_numeric($page)) {
            $page = 0;
        }
        return $this->json(["success" => true, "data" => $this->stockRepository->getPaginatedArray($page, 10)]);
    }

    /**
     * @Route("stock/view/{id}", name="stock_page")
     * @param Request $request
     * @param Stock $stock
     * @return Response
     *  get stocks page
     */
    public function getStockPage(Request $request, Stock $stock = null): Response
    {
        if ($stock == null) {
            return $this->render('homepage/stock.html.twig', [
                'stock' => null,
                'tickers' => null
            ]);
        }
        $tickerss = $stock->getTickers();
        $data = [];
        foreach ($tickerss as $ticker) {
            $data[] = [
                'symbol' => $ticker->getSymbol(),
                'date' => $ticker->getDate()->format("Y-m-d"),
                'open' => $ticker->getOpen(),
                'hight' => $ticker->getHight(),
                'low' => $ticker->getLow(),
                'close' => $ticker->getClose(),
                'adjClose' => $ticker->getAdjClose(),
                'volume' => $ticker->getVolume()
            ];
        }
        return $this->render('homepage/stock.html.twig', [
            'stock' => $stock,
            'tickers' => json_encode($data),
            'total' => count($stock->getTickers()),
            'pages' => ceil(count($stock->getTickers()) / 10),
            'page' => 1,
            'async' => true
        ]);
    }

    /**
     * @Route("/market/now", name="market_now")
     * @throws TransportExceptionInterface
     * get Market data from api
     */
    public
    function getMarketData(): Response
    {
        $response = $this->client->request(
            'GET',
            "https://public.polygon.io/v2/market/now"
        );
        try {
            return $this->json($response->toArray());
        } catch (ClientExceptionInterface $e) {
        } catch (DecodingExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
        return $this->json(['success' => false]);
    }

    /**
     * @Route("/market/news", name="market_news")
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * get Market News from api
     */
    public
    function getMarketNews(): Response
    {
        $last = $this->newsRepository->findLast();
        $now = new DateTime();
        $interval = isset($last) ? $now->getTimestamp() - $last->getCreatedAt()->getTimestamp() : null;
        if (!$interval || $interval > 1500) {
            $response = $this->client->request(
                'GET',
                "https://newsapi.org/v2/top-headlines?country=us&apiKey=7801b8594b6f48ce83142764265fa244&category=business"
            );
            $data = $response->toArray();
            if (isset($data['status']) && $data['status'] == "ok" && isset($data['articles']) && is_array($data['articles'])) {
                $m = $this->getDoctrine()->getManager();
                foreach ($data['articles'] as $article) {
                    $news = new News();
                    $news->setSourceName(isset($article['source']) ? (isset($article['source']['name']) ? $article['source']['name'] : null) : null);
                    $news->setContent(isset($article['content']) ? substr($article['content'], 0, (strpos($article['content'], '…') - strlen($article['content']))) . "..." : null);
                    $news->setUrl(isset($article['url']) ? $article['url'] : null);
                    $news->setTitle(isset($article['title']) ? $article['title'] : null);
                    $news->setDescription(isset($article['description']) ? $article['description'] : null);
                    $news->setAuthor(isset($article['author']) ? $article['author'] : null);
                    $news->setUrlToImage(isset($article['urlToImage']) ? $article['urlToImage'] : null);
                    $news->setPublishedAt(isset($article['publishedAt']) ? new DateTime('@' . strtotime($article['publishedAt'])) : null);
                    $m->persist($news);
                }
                $m->flush();
            }
            return $this->json(['success' => true, 'news' => $this->newsRepository->getPaginated(0, 20)]);
        }

        return $this->json(['success' => true, 'news' => $this->newsRepository->getPaginated(0, 20)]);

    }
}
