<?php
namespace AppBundle\UseCase;


use AppBundle\Scrapper\AlsacreationScrapper;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class ImportJobs
{

    // Entity Manager doctrine injecté
    private $em;

    // Chemin vers le cache symfony
    private $cachePath;

    // Instance vers le scrapper voulu
    private $jobScrapper;


    // permet de stocker les id originaux du site sur lequel on scrappe
    // pour éviter d'insérer en double des jobs
    // et éviter de faire une requête doctrine
    private $originalIdList = [];


    function __construct($entityManager, $cachePath) {
        $this->em = $entityManager;
        $this->jobScrapper = new AlsacreationScrapper();
        $this->cachePath = $cachePath.'/job_api/inserted.json';
        $this->getJobsAlreadyInserted();
    }




    /**
     * Permet d'importer un ou plusieurs jobs en base
     * @param $id
     * si $id est null, on importe tous les jobs
     */
    public function handle($id=null)
    {
        $total = 0;
        $ok = 0;
        $skipped = 0;
        $jobList = [];
        if (is_null($id)){
            $jobList = $this->jobScrapper->scrapJobListing(true);
        }else {
            $jobList[] = [ 'id'=>$id, 'slug'=>'poste'];
        }
        $total = count($jobList);
        foreach($jobList as $simpleInfo){
            try {
                if ($this->insertJob($simpleInfo)) {
                    $ok++;
                    continue;
                }
                $skipped++;
            }catch(\Exception $e){
                // une erreur est survenue
                // le job n'a pas été inséré
                // il serait bien de loguer l'erreur
                continue;
            }
        }
        return [
            'total' => $total,
            'ok'=> $ok,
            'skipped'=> $skipped
        ];
    }

    private function getJobsAlreadyInserted()
    {
        $fs = new Filesystem();
        try {
            $fs->mkdir(dirname($this->cachePath));
        } catch (IOException $e) {
            return;
        }
        if (file_exists($this->cachePath)){
            $this->originalIdList = json_decode(file_get_contents($this->cachePath),true);
            return;
        }
        $this->originalIdList = [];
    }

    /**
     *
     * @param $jobSimpleInfo
     * @return bool
     */
    private function insertJob($jobSimpleInfo)
    {

        if (in_array($jobSimpleInfo['id'], $this->originalIdList)){
            return false;
        }
        $job = $this->jobScrapper->scrapJob($jobSimpleInfo['id'], $jobSimpleInfo['slug']);
        if ($job) {
            $this->em->persist($job);
            $this->em->flush();
            array_push($this->originalIdList,$jobSimpleInfo['id']);
            return true;
        }

    }


    public function __destruct()
    {
        file_put_contents($this->cachePath, json_encode($this->originalIdList));
    }

}