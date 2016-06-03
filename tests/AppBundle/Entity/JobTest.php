<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Job;


class JobTest extends \PHPUnit_Framework_TestCase
{

    public function testCreation()
    {

        $job = Job::fromArray(['titre'=>'bonsoir']);
        $this->assertInstanceOf('AppBundle\Entity\Job', $job);

        $this->assertEquals('bonsoir', $job->getTitre());
    }


}