<?php

namespace App\Controller;

use App\Entity\Rubbish;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class RefreshDbController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    #[Route('/api/refresh-db/{pwd}', name: 'refresh-db', methods: ['GET'])]
    public function reloadDb(EntityManagerInterface $entityManager, string $pwd):Response
    {
        $currentDate = new \DateTimeImmutable("now");
        if($pwd === $_ENV['REFRESH_DB_PASSWORD']){
            $list = $this->fetchFromApi();
            // $list = $this->testApi();
            return $this->json($list);
            // foreach($list as $brewery){
            //     $toAdd = $this->newBrewery($brewery, $currentDate);
            //     $entityManager->persist($toAdd);

            // }
            // $entityManager->flush();
            // $nbItem = count($list);
            // return $this->json(["Success" => $nbItem . " add to the DB"]);
        }
        return $this->json(["Error" => "Wrong pwd"]);
    }

   
    public function fetchFromApi(): array
    {
        $response = $this->client->request(
            'GET',
            'https://data.metropole-rouen-normandie.fr/api/v2/catalog/datasets/donmetdec_pav/records?limit=10&offset=0&timezone=UTC',
            [
                'headers' => [
                    'accept' => 'application/json',
                    'charset' => 'utf-8',
                ],
            ]
        );
        $statusCode = $response->getStatusCode();
        return $statusCode;
        $httpLogs = $response->getInfo();
        return $httpLogs;
        $content = $response->toArray();
        return $content;

    }
    public function testApi()
    {
        $client = new CurlHttpClient();
        $url = "https://data.metropole-rouen-normandie.fr/api/v2/catalog/datasets/donmetdec_pav/records?limit=10&offset=0&timezone=UTC";
        $request = $client->request('GET', $url, [
            'headers' => [
                'accept' => 'application/json',
                'charset' => 'utf-8',
            ],
        ]);
        $request->getContent();
    }
}
