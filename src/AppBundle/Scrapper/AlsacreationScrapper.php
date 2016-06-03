<?php
namespace AppBundle\Scrapper;

use AppBundle\Entity\Job;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use RuntimeException;

class AlsacreationScrapper implements JobScrapperInterface
{
    const listPath = "http://emploi.alsacreations.com/offres.html";
    const jobPath  = "http://emploi.alsacreations.com/offre-{id}-{slug}.html";

    private $client;

    public function  __construct()
    {
        $this->client = new Client();
    }

    /**
     * Permet d'extraire les informations détaillés d'un job
     * @param string $jobId  : id du job à récupérer (propre au site sur lequel on le récupére)
     * @param string $slug   : partie dynamique dans l'url qui peut être nécessaire pour récupérer le job
     * @return AppBundle\Entity\Job
     */
    public function scrapJob($jobId, $slug ="poste")
    {

        // prépare la requête http
        $jobUrl = str_replace('{id}',$jobId, AlsacreationScrapper::jobPath);
        $jobUrl = str_replace('{slug}',$slug, $jobUrl);
        $response  = $this->client->request('GET', $jobUrl);
        // forcer le cast en string de la requête http
        $pageContent = strval($response->getBody());

        // création d'un crawler pour parser la page web
        $crawler = new Crawler($pageContent);

        // parsing des skills nécessaires
        $skills = [];
        $skillNodes = $crawler->filter('.vmid[itemprop=skills] b');
        foreach ($skillNodes as $i => $node) {
            $skills[] = trim($node->textContent);
        }

        // parsing du ou des types de contrats
        $status =[];
        $statusNodes = $crawler->filter('.typec span');
        foreach ($statusNodes as $i => $node) {
            $status[] = trim($node->textContent);
        }

        // récupération de la position gps de l'entreprise via le script google map (s'il existe)
        $gps = [];
        $mapNode = $crawler->filter('#mapmini');
        if ($mapNode->count()>0) {
            $scriptNode = $crawler->filter('#second script')->getNode(1);
            if (preg_match('/LatLng\(([0-9\.]+)\,([0-9\.]+)\)\;/', $scriptNode->nodeValue, $matches)) {
                $gps = [
                    'lat'=>$matches[1],
                    'long'=>$matches[2]
                ];
            }
        }

        // si une annonce est pourvue un div .awesome.yellow est présent dans la page


        // création d'un tableau
        $jobData = [
            'originalId'=>$jobId,
            'titre' => trim($this->_getContent($crawler,'.titre')),
            'description' => trim($this->_getContent($crawler, '#emploi .presentation', 'html')),
            'skills'=>$skills,
            'status'=>$status,
            'datetime'=> new \DateTime($this->_getContent($crawler,'.navinfo time','attr','datetime')),
            'location'=> $this->_getContent($crawler,'.vmid [itemprop=jobLocation]'),
            'entreprise'=> trim($this->_getContent($crawler,'[itemprop="hiringOrganization"] [itemprop="name"]')),
            'entrepriseUrl'=> trim($this->_getContent($crawler, '[itemprop="hiringOrganization"] [itemprop="url"]','attr','href')),
            'entrepriseDescription'=> $this->_getContent($crawler, '[itemprop="hiringOrganization"] [itemprop="description"]', 'html'),
            'entrepriseLocation'=>trim($this->_getContent($crawler, '[itemprop="hiringOrganization"] [itemprop="location"]')),
            'entrepriseGps'=> $gps,
            'contactEmail'=> trim(str_replace('**at**','@',$this->_getContent($crawler, '.coord [itemprop="email"]'))),
            'contactName'=> trim($this->_getContent($crawler, '.coord [itemprop="employees"]')),
            'contactTelephone'=> trim($this->_getContent($crawler,'.coord [itemprop="telephone"]')),
            'source'=>'alsacreation'
        ];

        return Job::fromArray($jobData);
    }


    /**
     * @param bool $reverse
     * @return array
     */
    public function scrapJobListing($reverse=false)
    {
        $response = $this->client->request('GET', AlsacreationScrapper::listPath);
        $pageContent = strval($response->getBody());

        $crawler = new Crawler($pageContent);
        $jobsListing = $crawler->filter('.offre > tr');

        if (iterator_count($jobsListing) == 0 ) {
            throw new RuntimeException('No job found');
        }
        $jobs = [];
        foreach ($jobsListing as $i => $lineContent) {
            $jobCrawler = new Crawler($lineContent);
            $libelleNode = $jobCrawler->filter('.intitule');
            $url = $libelleNode->attr('href');
            $urlParts = $this->extractUrl($url);
            if (!$urlParts) {
                continue;
            }

            $cols = $jobCrawler->filter('td');
            $jobData = [
                'url'=> $url,
                'libelle'=>$libelleNode->text(),
                'date'=>$cols->eq(2)->text(),
                'id'=>$urlParts['id'],
                'slug'=>$urlParts['slug'],
            ];
            $jobs[] = $jobData;
        }
        if ($reverse) {
            $jobs = array_reverse($jobs);
        }
        return $jobs;
    }


    private function _getContent(Crawler $crawler, $filter, $nodeType="text", $param="")
    {
        try {
            $node = $crawler->filter($filter);
            if ($node->count()==0) {
                return "";
            }
            switch($nodeType) {
                case "html":
                    return $node->html();
                case "attr":
                    return $node->attr($param);
                case "text":
                default:
                    return $node->text();
            }
        } catch(\Exception $e) {

        }
        return "";
    }

    /**
     * @param $url
     * @return array
     */
    private function extractUrl($url)
    {
        if (preg_match ('/[a-z\-]+([0-9]+)-([0-9a-z\-]+).html/i',$url,$matches)) {
            return [
              'id'=>  $matches[1],
              'slug'=>  $matches[2]
            ];
        }
        return false;
    }


}