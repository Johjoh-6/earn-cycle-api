<?php

namespace App\Controller;

use App\Entity\Category;
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
    private $_entityManager;

    public function __construct(HttpClientInterface $client,   EntityManagerInterface $entityManager,)
    {
        $this->client = $client;
        $this->_entityManager = $entityManager;
    }
    #[Route('/api/refresh-db/{pwd}', name: 'refresh-db', methods: ['GET'])]
    public function reloadDb(EntityManagerInterface $entityManager, string $pwd):Response
    {
        if($pwd === $_ENV['REFRESH_DB_PASSWORD']){
            try{
                $donwload = $this->donwloadJsonFile();
                if($donwload){
                    $list = $this->getJson();
                        foreach($list as $rubbish){
                            $toAdd = $this->createNewRubbish($rubbish);
                            $entityManager->persist($toAdd);

                        }
                        $entityManager->flush();
                        $nbItem = count($list);
                        return $this->json(["Success" => $nbItem . " add to the DB"]);
                    }
                    else{
                        return $this->json(["Error" => "Can't download the file"]);
                    }
            }
            catch(\Exception $e){
                return new Response($e->getMessage());
            }
        }
        return $this->json(["Error" => "Wrong pwd"]);
    }

    //TODO  made a change to php curl 
    protected function donwloadJsonFile(){
        $url = "https://data.metropole-rouen-normandie.fr/explore/dataset/donmetdec_pav/download/?format=json&timezone=Europe/Berlin&lang=fr";
        $savePath = "../public/data/rouen.json";
        $file = file_get_contents($url);
        if(file_put_contents($savePath, $file)){
            echo 'File downloaded successfully';
            return true;
        } else {
            echo 'File failed to download';
            return false;
        }
    }

    protected function getJson(){
        $path = "../public/data/rouen.json";
        $json = file_get_contents($path);
        $list = json_decode($json, true);
        return $list;
    }

    protected function createNewRubbish($data): Rubbish
    {
        $rubbish = new Rubbish();
        $rubbish->setCountry("France");
        $rubbish->setCity($data['fields']['commune']);
        $rubbish->setLatitude($data['fields']['geo_point_2d'][0]);
        $rubbish->setLongitude($data['fields']['geo_point_2d'][1]);
        $rubbish->setPostalCode($this->postalCodeByCity($data['fields']['commune']));
        $adress = array_key_exists('adresse', $data['fields']) ? $data['fields']['adresse'] : "No adress";
        $rubbish->setStreetName($adress);
        $rubbish->setNbStreet('0');
        $rubbish->setCertified(true);
        $rubbish->setCategory($this->getCategoryBy($data['fields']['pavtyp']));
        return $rubbish;
    }

    private function getCategoryBy($type): Category
    {
        $typeDecode = utf8_decode($type);
        return match($typeDecode){
            'Emballages en verre' => $this->_entityManager->getRepository(Category::class)->findOneBy(['name' => 'Glass']),
            'Ordures ménagères' => $this->_entityManager->getRepository(Category::class)->findOneBy(['name' => 'Waste']),
            'Emballages recyclables' => $this->_entityManager->getRepository(Category::class)->findOneBy(['name' => 'Recycling']),
            'Textile' => $this->_entityManager->getRepository(Category::class)->findOneBy(['name' => 'Clothes']),
            default => $this->_entityManager->getRepository(Category::class)->findOneBy(['name' => 'Other']),
        };
    }
    private function postalCodeByCity($city): string
    {
        $cityDecode = utf8_decode($city);
        return match($cityDecode){
            'Le Grand-Quevilly' => '76120',
            'Rouen' => '76000',
            'Mont-Saint-Aignan' => '76130',
            'Amfreville la Mivoie' => '76920',
            'Belbeuf'=> '76240',
            'Berville' => '76560',
            'Bihorel' => '76420',
            'Bois Guillaume' => '76230',
            'Boos' => '76520',
            'Caudebec-lès-Elbeuf' => '764320',
            'Canteleu'=> '76380',
            'Darnétal'=> '76160',
            'Déville-lès-Rouen' => '76250',
            'Duclair'=> '76480',
            'Elbeuf'=> '76500',
            'Gouy'=> '76520',
            'Grand-Couronne'=> '76530',
            'Houppeville'=> '76770',
            'Isneauville'=> '76230',
            'Jumièges' => '76480',
            'La Londe'=> '76500',
            'Le Trait'=> '76580',
            'Maromme'=> '76150',
            'Petit Couronne'=> '76650',
            'Roncherolles-sur-le-Vivier'=> '76160',
            'Saint Aubin lès Elbeuf'=> '76410',
            'Saint-Etienne-du Rouvray'=> '76800',
            'Saint-Léger-du-Bourg-Denis' => '76160',
            'Saint-Pierre-de-Varengeville'=> '76480',
            'Sotteville-lès-Rouen' => '76300',
            'Tourville-la-Rivière'=> '76410',
            default => '76000',
        };
    }
}
