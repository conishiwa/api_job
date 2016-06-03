<?php
namespace AppBundle\Scrapper;


interface JobScrapperInterface
{

    /**
     * Permet d'extraire les informations détaillés d'un job
     * @param string $jobId  : id du job à récupérer (propre au site sur lequel on le récupére)
     * @param string $slug   : partie dynamique dans l'url qui peut être nécessaire pour récupérer le job
     * @return AppBundle\Entity\Job
     */
    public function scrapJob($jobId, $slug="");


    /**
     * Permet d'extraire un listing de jobs, avec des informations sommaires
     * @param bool $reverse  pour inverser l'ordre d'affichage des jobs
     * @return array
     */
    public function scrapJobListing($reverse=false);


}