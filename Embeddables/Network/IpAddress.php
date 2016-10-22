<?php

namespace Muspelheim\EmbeddablesBundle\Embeddables\Network;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class IpAddress
{
    /**
     * IPv4 or IPv6
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $address;

    /**
     * @throws \InvalidArgumentException
     * @param string $address
     */
    public function __construct($address = null)
    {
        if (!$address) {
            return;
        }

        $filtered = filter_var($address, FILTER_VALIDATE_IP);
        if ($filtered === false) {
            throw new \InvalidArgumentException('Given IP '.$address.' is not a valid IP address');
        }

        $this->address = $filtered;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->address ?: '';
    }
}
