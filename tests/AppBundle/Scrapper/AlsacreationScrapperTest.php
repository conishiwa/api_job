<?php
namespace test\AppBundle\Scrapper;

use AppBundle\Scrapper\AlsacreationScrapper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlsacreationScrapperTest extends WebTestCase
{

    /**
     * @var AlsacreationScrapper $scrapper
     */
    private $scrapper;


    /**
     * array
     */
    private $listing;

    /**
     * @before
     */
    public function setup()
    {
        $this->scrapper = new AlsacreationScrapper();
        $this->listing = $this->scrapper->scrapJobListing();
    }


    public function testScrapJobListing()
    {
        $listingReverse = $this->scrapper->scrapJobListing(true);

        $this->assertGreaterThan(20, $this->listing);
        $this->assertEquals(count($this->listing), count($listingReverse));
        $this->assertEquals($this->listing[0],$listingReverse[count($listingReverse)-1]);

        $this->assertArrayHasKey('url', $this->listing[0]);
        $this->assertArrayHasKey('libelle', $this->listing[0]);
        $this->assertArrayHasKey('date', $this->listing[0]);
        $this->assertArrayHasKey('id', $this->listing[0]);
        $this->assertArrayHasKey('slug', $this->listing[0]);
    }




    /**
     * @depends testScrapJobListing
     * @expectedException \Exception
     */
    public function testScrapJobFailed()
    {
        $this->scrapper->scrapJob("454");

    }


    /**
     * @depends testScrapJobFailed
     */
    public function testScrapJob()
    {
        $job = $this->scrapper->scrapJob("id_invalide");
        $this->assertEmpty($job->getTitre());

        // on teste les 5 premiers résultats
        for($i=0;$i<min(5,count($this->listing));$i++) {
            $job = $this->scrapper->scrapJob($this->listing[$i]['id']);
            $this->assertNotEmpty($job->getTitre());
            $this->assertInstanceOf('AppBundle\Entity\Job', $job);
        }
    }




}