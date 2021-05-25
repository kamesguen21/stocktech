<?php

namespace App\Command;

use App\Entity\Description;
use App\Entity\Stock;
use App\Entity\Ticker;
use App\Repository\DescriptionRepository;
use App\Repository\StockRepository;
use App\Repository\TickerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportDataCommand extends Command
{
    const BASE_URL = "https://cdn.jsdelivr.net/gh/kamesguen21/StockData@master/api/";
    const STOCKS_PATH = "stocks.json";
    const HISTORICAL_PATH = "historical/{SYMBOL}.json";
    const DESCRIPTION_PATH = "description/{SYMBOL}.json";
    protected static $defaultName = 'import-data';
    protected static $defaultDescription = 'Import data from api';
    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var StockRepository
     */
    private $stockRepository;
    /**
     * @var DescriptionRepository
     */
    private $descriptionRepository;
    /**
     * @var TickerRepository
     */
    private $tickerRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        StockRepository $stockRepository,
        DescriptionRepository $descriptionRepository,
        TickerRepository $tickerRepository,
        EntityManagerInterface $entityManager,
        HttpClientInterface $client,
        LoggerInterface $logger,
        string $name = null)
    {
        $this->entityManager = $entityManager;
        $this->stockRepository = $stockRepository;
        $this->descriptionRepository = $descriptionRepository;
        $this->tickerRepository = $tickerRepository;
        $this->client = $client;
        $this->logger = $logger;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * import stock data from api
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $helper = $this->getHelper('question');
            $io->warning('this action will truncate the following table (stock, description,ticker), are you sure you want to continue,');
            $question = new ConfirmationQuestion('Continue with this action? (yes,no) [no]', false);
            if (!$helper->ask($input, $output, $question)) {
                return Command::SUCCESS;
            }
            $stocks = $this->stockRepository->findAll();
            foreach ($stocks as $stock) {
                $this->entityManager->remove($stock);
            }
            $this->entityManager->flush();
            $response = $this->client->request(
                'GET',
                self::BASE_URL . self::STOCKS_PATH
            );
            $statusCode = $response->getStatusCode();
            $content = $response->getContent();
            $stockIndex = 0;
            $tickerIndex = 0;
            $descriptionIndex = 0;
            if ($statusCode != 200) {
                $io->error('error while requesting api');
                $io->error('message');
                $io->error($content);
                return Command::SUCCESS;
            } else {
                $symbols = json_decode($content, true);
                $io->success('Got Symbols');
                $progressBar = new ProgressBar($output, count($symbols));
                $progressBar->start();
                foreach ($symbols as $symbol) {
                    $stock = new Stock();
                    $stock->setSymbol($symbol['SYMBOL']);
                    $this->entityManager->persist($stock);
                    $this->entityManager->flush();
                    $stockIndex++;
                    $stock = $this->stockRepository->findOneBy(["symbol" => $symbol['SYMBOL']]);
                    $response = $this->client->request(
                        'GET',
                        self::BASE_URL . str_replace("{SYMBOL}", $symbol['SYMBOL'], self::HISTORICAL_PATH)
                    );
                    $statusCode = $response->getStatusCode();
                    $content = $response->getContent();
                    if ($statusCode != 200) {
                        $progressBar->setMessage('error while requesting historical api for symbol ' . $symbol['SYMBOL']);
                        $progressBar->setMessage($content);
                    } else {
                        $progressBar->setMessage('Got historical data for ' . $symbol['SYMBOL']);
                        $data = $response->toArray();
                        foreach ($data as $key => $item) {
                            $ticker = new Ticker();
                            $ticker->setStock($stock);
                            $ticker->setSymbol($symbol['SYMBOL']);
                            $ticker->setDate(new DateTime('@' . strtotime($item['date'])));
                            $ticker->setOpen($item['open']);
                            $ticker->setHight($item['high']);
                            $ticker->setClose($item['Close']);
                            $ticker->setLow($item['Low']);
                            $ticker->setAdjClose($item['AdjClose']);
                            $ticker->setVolume($item['volume']);
                            $this->entityManager->persist($ticker);
                            $tickerIndex++;
                        }
                    }
                    $response = $this->client->request(
                        'GET',
                        self::BASE_URL . str_replace("{SYMBOL}", $symbol['SYMBOL'], self::DESCRIPTION_PATH)
                    );

                    if ($statusCode != 200) {
                        $progressBar->setMessage('error while requesting historical api for symbol ' . $symbol['SYMBOL']);
                        $progressBar->setMessage($response->getContent());
                    } else {
                        $descriptionRes = $response->toArray();
                        $description = new Description();
                        $description->setStock($stock);
                        $description->setSymbol($symbol['SYMBOL']);
                        $description->setLogo(isset($descriptionRes['logo'])?$descriptionRes['logo']:null);
                        $description->setListdate(isset($descriptionRes['listdate'])?$descriptionRes['listdate']:null);
                        $description->setCik(isset($descriptionRes['cik'])?$descriptionRes['cik']:null);
                        $description->setBloomberg(isset($descriptionRes['bloomberg'])?$descriptionRes['bloomberg']:null);
                        $description->setFigi(isset($descriptionRes['figi'])?$descriptionRes['figi']:null);
                        $description->setLei(isset($descriptionRes['lei'])?$descriptionRes['lei']:null);
                        $description->setSic(isset($descriptionRes['sic'])?$descriptionRes['sic']:null);
                        $description->setCountry(isset($descriptionRes['country'])?$descriptionRes['country']:null);
                        $description->setIndustry(isset($descriptionRes['industry'])?$descriptionRes['industry']:null);
                        $description->setSector(isset($descriptionRes['sector'])?$descriptionRes['sector']:null);
                        $description->setMarketcap(isset($descriptionRes['marketcap'])?$descriptionRes['marketcap']:null);
                        $description->setEmployees(isset($descriptionRes['employees'])?$descriptionRes['employees']:null);
                        $description->setPhone(isset($descriptionRes['phone'])?$descriptionRes['phone']:null);
                        $description->setCeo(isset($descriptionRes['ceo'])?$descriptionRes['ceo']:null);
                        $description->setUrl(isset($descriptionRes['url'])?$descriptionRes['url']:null);
                        $description->setDescription(isset($descriptionRes['description'])?$descriptionRes['description']:null);
                        $description->setExchange(isset($descriptionRes['exchange'])?$descriptionRes['exchange']:null);
                        $description->setName(isset($descriptionRes['name'])?$descriptionRes['name']:null);
                        $description->setExchangeSymbol(isset($descriptionRes['exchangeSymbol'])?$descriptionRes['exchangeSymbol']:null);
                        $description->setHqState(isset($descriptionRes['hq_state'])?$descriptionRes['hq_state']:null);
                        $description->setHqAddress(isset($descriptionRes['hq_address'])?$descriptionRes['hq_address']:null);
                        $description->setHqCountry(isset($descriptionRes['hq_country'])?$descriptionRes['hq_country']:null);
                        $description->setType(isset($descriptionRes['type'])?$descriptionRes['type']:null);
                        $description->setUpdated(isset($descriptionRes['updated']) ? new DateTime('@' . strtotime($descriptionRes['updated'])) : null);
                        $description->setTags(isset($descriptionRes['tags'])?implode(",", $descriptionRes['tags']):null);
                        $description->setSimilar(isset($descriptionRes['similar'])?implode(",", $descriptionRes['similar']):null);
                        $description->setActive(isset($descriptionRes['active'])?$descriptionRes['active']:null);
                        $this->entityManager->persist($description);
                    }
                    $this->entityManager->flush();
                    $progressBar->advance();
                }
                $progressBar->finish();
                return Command::SUCCESS;
            }
        } catch (TransportExceptionInterface $e) {
            $io->error('error while requesting api');
            $io->error('message');
            $io->error($e->getMessage());
            $io->error('trace');
            $io->error($e->getTraceAsString());
            return Command::FAILURE;
        } catch (ClientExceptionInterface $e) {
            $io->error('error while requesting api');
            $io->error('message');
            $io->error($e->getMessage());
            $io->error('trace');
            $io->error($e->getTraceAsString());
            return Command::FAILURE;
        } catch (RedirectionExceptionInterface $e) {
            $io->error('error while requesting api');
            $io->error('message');
            $io->error($e->getMessage());
            $io->error('trace');
            $io->error($e->getTraceAsString());
            return Command::FAILURE;
        } catch (ServerExceptionInterface $e) {
            $io->error('error while requesting api');
            $io->error('message');
            $io->error($e->getMessage());
            $io->error('trace');
            $io->error($e->getTraceAsString());
            return Command::FAILURE;
        } catch (DecodingExceptionInterface $e) {
            $io->error('error while requesting api');
            $io->error('message');
            $io->error($e->getMessage());
            $io->error('trace');
            $io->error($e->getTraceAsString());
            return Command::FAILURE;
        } catch (Exception $e) {
            $io->error('error while requesting api');
            $io->error('message');
            $io->error($e->getMessage());
            $io->error('trace');
            $io->error($e->getTraceAsString());
            return Command::FAILURE;
        }

    }
}
