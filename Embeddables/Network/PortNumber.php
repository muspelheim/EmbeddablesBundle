<?php

namespace Muspelheim\EmbeddablesBundle\Embeddables\Network;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class PortNumber
{
    /**
     * IPv4 or IPv6
     * @ORM\Column(type="integer", length=5, nullable=true)
     */
    public $port;

    /**
     * @param int $port
     */
    public function __construct($port)
    {
        $options = array(
            'options' => array(
                'min_range' => 0,
                'max_range' => 65535
            )
        );
        $port = filter_var($port, FILTER_VALIDATE_INT, $options);
        if (false === $port) {
            throw new \InvalidArgumentException('Given port ' . $port . ' is not a valid port int (>=0, <=65535)');
        }
        
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->port;
    }
}