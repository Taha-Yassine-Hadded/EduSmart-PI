<?php

// src/Controller/DefaultController.php
namespace App\Controller\EventControllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TeamTNT\TNTSearch\TNTSearch;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EventsRepository;
use App\Entity\Events;


class testSearchController extends AbstractController
{    
    /**
     * Returns an array with the configuration of TNTSearch with the
     * database used by the Symfony project.
     * 
     * @return type
     */
    private function getTNTSearchConfiguration(){
        $databaseURL = $_ENV['DATABASE_URL'];
        
        $databaseParameters = parse_url($databaseURL);
        
        $config = [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'pidev',
            'username'  => 'root',
            'password'  => '',
            'storage'   => 'C:\Users\GAMING\Desktop\pidevSymfony\PiSymfony\fuzzy_storage',  
            'stemmer'   => \TeamTNT\TNTSearch\Stemmer\PorterStemmer::class
        ];
        
        return $config;
    }
    /**
     * @Route("/generate-index", name="app_generate-index")
     */
    public function generate_index()
    {
        $tnt = new TNTSearch;

        // Obtain and load the configuration that can be generated with the previous described method
        $configuration = $this->getTNTSearchConfiguration();
        $tnt->loadConfig($configuration);

        // The index file will have the following name, feel free to change it as you want
        $indexer = $tnt->createIndex('artists.index');
        
       
        $indexer->query('SELECT id, event_name, description FROM events;');
        
        // Generate index file !
        $indexer->run();

        return new Response(
            '<html><body>Index succesfully generated !</body></html>'
        );
}
 /**
 * @Route("/search", name="app_search")
 */
public function search(Request $request)
{
    // Get the search term from the request query parameters
    $searchTerm = $request->query->get('searchTerm');

    $tnt = new TNTSearch;

    $configuration = $this->getTNTSearchConfiguration();
    $tnt->loadConfig($configuration);
    
    
    $tnt->selectIndex('artists.index');
    $tnt->fuzziness(true);
    $maxResults = 20;
    
    
    $results = $tnt->search($searchTerm, $maxResults);
    $entityManager = $this->getDoctrine()->getManager();
    $events = $entityManager->getRepository(Events::class);
    
    
    $rows = [];
    
    foreach($results["ids"] as $id){
        // You can optimize this by using the FIELD function of MySQL if you are using mysql
        // more info at: https://ourcodeworld.com/articles/read/1162/how-to-order-a-doctrine-2-query-result-by-a-specific-order-of-an-array-using-mysql-in-symfony-5
        $event = $events->find($id);
        if ($event){
            $rows[] = [
                'id' => $event->getId(),
                'name' => $event->getEventName()
            ];
        } else {
            // It's up to you whether to return an empty response or an error message here
            return new JsonResponse([]);
        }
    }
    
    
    return new JsonResponse($rows);
}
}