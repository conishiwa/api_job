<?php
namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


use AppBundle\Annotation\Link;


/**
 * Job
 *
 * @ORM\Table(name="job",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="slug_unique",columns={"slug"}),@ORM\UniqueConstraint(name="uid_unique",columns={"uid"})},
 *  indexes={@ORM\Index(name="datetime_idx", columns={"datetime"})}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JobRepository")
 * @ExclusionPolicy("all")
 * @Link(
 *  "self",
 *  route = "api_jobs_show",
 *  params = { "uid": "object.getUid()" }
 * )
 */
class Job
{
    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param string $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return array
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param array $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param array $skills
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * @param string $entreprise
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;
    }

    /**
     * @return string
     */
    public function getEntrepriseUrl()
    {
        return $this->entrepriseUrl;
    }

    /**
     * @param string $entrepriseUrl
     */
    public function setEntrepriseUrl($entrepriseUrl)
    {
        $this->entrepriseUrl = $entrepriseUrl;
    }

    /**
     * @return string
     */
    public function getEntrepriseDescription()
    {
        return $this->entrepriseDescription;
    }

    /**
     * @param string $entrepriseDescription
     */
    public function setEntrepriseDescription($entrepriseDescription)
    {
        $this->entrepriseDescription = $entrepriseDescription;
    }

    /**
     * @return string
     */
    public function getEntrepriseLocation()
    {
        return $this->entrepriseLocation;
    }

    /**
     * @param string $entrepriseLocation
     */
    public function setEntrepriseLocation($entrepriseLocation)
    {
        $this->entrepriseLocation = $entrepriseLocation;
    }

    /**
     * @return array
     */
    public function getEntrepriseGps()
    {
        return $this->entrepriseGps;
    }

    /**
     * @param array $entrepriseGps
     */
    public function setEntrepriseGps($entrepriseGps)
    {
        $this->entrepriseGps = $entrepriseGps;
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
    }

    /**
     * @return string
     */
    public function getContactTelephone()
    {
        return $this->contactTelephone;
    }

    /**
     * @param string $contactTelephone
     */
    public function setContactTelephone($contactTelephone)
    {
        $this->contactTelephone = $contactTelephone;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOriginalId()
    {
        return $this->originalId;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }



    /**
     * @return DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param DateTime $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $uid;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $originalId;


    /**
     * @ORM\Column(type="string")
     * @Expose
     */
    private $titre;


    /**
     * @Gedmo\Slug(fields={"titre"}, updatable=false)
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Expose
     *
     */
    private $slug;


    /**
     * @ORM\Column(type="text", nullable=true)
     * @Expose
     */
    private $description;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     */
    private $datetime;


    /**
     * @ORM\Column(type="json_array", nullable=true)
     * @Expose
     */
    private $status;


    /**
     * @ORM\Column(type="json_array", nullable=true)
     * @Expose
     */
    private $skills;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $location;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $entreprise;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $entrepriseUrl;


    /**
     * @ORM\Column(type="text", nullable=true)
     * @Expose
     */
    private $entrepriseDescription;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $entrepriseLocation;


    /**
     * @ORM\Column(type="json_array", nullable=true)
     * @Expose
     */
    private $entrepriseGps;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $contactEmail;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $contactName;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $contactTelephone;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     */
    private $source;


    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;


    /**
     * Permet de créer une instance de Job à partir d'un tableau
     * @param $data
     * @return AppBundle\Entity\Job
     */
    public static function fromArray($data)
    {
        $job = new static;
        foreach($data as $key=>$value){
            if (property_exists($job,$key)){
                $job->$key = $value;
            }
        }
        return $job;
    }



    /**
     * Set originalId
     *
     * @param string $originalId
     *
     * @return Job
     */
    public function setOriginalId($originalId)
    {
        $this->originalId = $originalId;

        return $this;
    }
}
