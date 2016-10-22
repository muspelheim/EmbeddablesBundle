<?php

namespace Muspelheim\EmbeddablesBundle\Embeddables\Web;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Email
{
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $address;

    /**
     * @param string $address
     */
    public function __construct($address = null)
    {
        if ($address && !filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Given e-mail address '.$address.' is not a valid');
        }

        $this->address = $address;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return explode('@', $this->address)[1];
    }

    /**
     * @return string
     */
    public function getLocalPart()
    {
        return explode('@', $this->address)[0];
    }
}
