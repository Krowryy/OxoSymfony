<?php

namespace OXO\TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdvertSkill
 *
 * @ORM\Table(name="advert_skill")
 * @ORM\Entity(repositoryClass="OXO\TrainingBundle\Repository\AdvertSkillRepository")
 */
class AdvertSkill
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255)
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity="OXO\TrainingBundle\Entity\Advert")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;

    /**
     * @ORM\ManyToOne(targetEntity="OXO\TrainingBundle\Entity\Skill")
     * @ORM\JoinColumn(nullable=false)
     */
    private $skill;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set level.
     *
     * @param string $level
     *
     * @return AdvertSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }


    /**
     * Set advert.
     *
     * @param \OXO\TrainingBundle\Entity\Advert $advert
     *
     * @return AdvertSkill
     */
    public function setAdvert(\OXO\TrainingBundle\Entity\Advert $advert)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert.
     *
     * @return \OXO\TrainingBundle\Entity\Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set skill.
     *
     * @param \OXO\TrainingBundle\Entity\Skill $skill
     *
     * @return AdvertSkill
     */
    public function setSkill(\OXO\TrainingBundle\Entity\Skill $skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill.
     *
     * @return \OXO\TrainingBundle\Entity\Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
