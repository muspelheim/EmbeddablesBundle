<?php

namespace Muspelheim\EmbeddablesBundle\Embeddables\Network;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class MacAddress
{
    /**
     * IPv4 or IPv6
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mac;

    /**
     * Constructor
     *
     * Accepts an EUI-48 (MAC) address in any valid format.
     *
     * @param string $eui48
     */
    public function __construct($eui48 = null)
    {
        if ($eui48) {
            $this->mac = self::normalize($eui48);
        }
    }

    /**
     * @param  string $input
     * @return string
     * @throws \InvalidArgumentException on invalid MAC addresses
     */
    private static function normalize($input)
    {
        $nonHexRemoved = preg_replace('/[^[:xdigit:]]/', '', $input);
        if (strlen($nonHexRemoved) !== 12) {
            throw new \InvalidArgumentException('Invalid MAC address.');
        }
        return strtolower($nonHexRemoved);
    }

    /**
     * Formats the MAC address in a configurable way
     *
     * @param  bool $upper          Whether or not to uppercase the formatted address
     * @param  string $delimiter    The delimiter to use between groups
     * @param  int $groupLength     The length of delimited hex digit groups (0 for none)
     * @return string
     */
    public function format($upper = false, $delimiter = ':', $groupLength = 2)
    {
        $formatted = $this->mac;
        if ($upper) {
            $formatted = strtoupper($formatted);
        }
        if ($groupLength > 0) {
            $formatted = implode($delimiter, str_split($formatted, $groupLength));
        }
        return $formatted;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->mac;
    }
}