<?php

namespace Muspelheim\EmbeddablesBundle\Embeddables\Person;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class Name implements JsonSerializable
{
    const TITLES = [
        'Mr.',
        'Ms.',
        'Mrs.',
        'Miss',
        'Dr.',
        'Prof.',
    ];

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @var string
     */
    private $middleName;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @var string
     */
    private $lastName;

    /**
     * @param string $fullname
     * @param string $title
     */
    public function __construct($fullname = null, $title = null)
    {
        if($fullname) {
            $this->buildFromString($fullname);
        }

        if ($title) {
            $this->setTitle($title);
        }
    }

    /**
     * @throws \InvalidArgumentException
     * @param string $title
     */
    private function setTitle($title)
    {
        if (substr($title, -1) !== '.') {
            // Allow titles without dots too
            $title = $title . '.';
        }

        if (!in_array($title, self::TITLES)) {
            throw new \InvalidArgumentException('Given title is invalid: ' . $title);
        }

        $this->title = $title;
    }

    /**
     * @param string $fullname
     * @return self
     */
    private function buildFromString($fullname)
    {
        $names = array_filter(explode(' ', $fullname)); // explode from spaces
        $i     = 1;
        $total = count($names);

        foreach ($names as $word) {
            if ($i === 1) {
                $this->firstName = $word;
            } elseif ($i >= 2) {
                if ($i === $total) {
                    $this->lastName = $word;
                } else {
                    $this->middleName = trim($this->middleName.' '.$word);
                }
            }
            ++$i;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->title,
            'firstName' => $this->firstName,
            'middleName' => $this->middleName,
            'lastName' => $this->lastName,
        ];
    }

    /**
     * String representation of a full name.
     *
     * @return string
     */
    public function __toString()
    {
        $result = [$this->title, $this->firstName, $this->middleName, $this->lastName];

        return implode(' ', array_filter($result));
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
