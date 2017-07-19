<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tags
 *
 * @ORM\Table(name="imported")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImportedRepository")
 */
class Imported
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $field_one;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $field_two;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $field_three;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $field_four;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fieldOne
     *
     * @param string $fieldOne
     *
     * @return Imported
     */
    public function setFieldOne($fieldOne)
    {
        $this->field_one = $fieldOne;

        return $this;
    }

    /**
     * Get fieldOne
     *
     * @return string
     */
    public function getFieldOne()
    {
        return $this->field_one;
    }

    /**
     * Set fieldTwo
     *
     * @param string $fieldTwo
     *
     * @return Imported
     */
    public function setFieldTwo($fieldTwo)
    {
        $this->field_two = $fieldTwo;

        return $this;
    }

    /**
     * Get fieldTwo
     *
     * @return string
     */
    public function getFieldTwo()
    {
        return $this->field_two;
    }

    /**
     * Set fieldThree
     *
     * @param string $fieldThree
     *
     * @return Imported
     */
    public function setFieldThree($fieldThree)
    {
        $this->field_three = $fieldThree;

        return $this;
    }

    /**
     * Get fieldThree
     *
     * @return string
     */
    public function getFieldThree()
    {
        return $this->field_three;
    }

    /**
     * Set fieldFour
     *
     * @param string $fieldFour
     *
     * @return Imported
     */
    public function setFieldFour($fieldFour)
    {
        $this->field_four = $fieldFour;

        return $this;
    }

    /**
     * Get fieldFour
     *
     * @return string
     */
    public function getFieldFour()
    {
        return $this->field_four;
    }
}
