<?php

namespace Muspelheim\EmbeddablesBundle\Embeddables\Network;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class IpAddressRange implements JsonSerializable
{
    /**
     * IPv4 or IPv6
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $startIp;

    /**
     * IPv4 or IPv6
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $endIp;

    /**
     * @param IpAddress $startIp
     * @param IpAddress $endIp
     */
    public function __construct(IpAddress $startIp = null, IpAddress $endIp = null)
    {
        $this->startIp = $startIp ? $startIp : $this->filter($startIp);
        $this->endIp   = $endIp ? $endIp :$this->filter($endIp);
    }

    private function filter($ip)
    {
        $filtered = filter_var($ip, FILTER_VALIDATE_IP);
        if ($filtered === false) {
            throw new \InvalidArgumentException('Given IP ' . $ip . ' is not a valid IP address');
        }

        return $filtered;
    }

    /**
     * @return IpAddress
     */
    public function getStartIp()
    {
        return $this->startIp;
    }

    /**
     * @return IpAddress
     */
    public function getEndIp()
    {
        return $this->endIp;
    }

    /**
     * Create a new range from CIDR notation.
     * CIDR notation is a compact representation of an IP address(es)
     * and its associated routing prefix.
     *
     * @static
     * @param string $cidr
     * @return self
     */
    public static function createFromCidrNotation($cidr)
    {
        list($subnet, $bits) = explode('/', $cidr);
        $start               = long2ip((ip2long($subnet)) & ((-1 << (32 - (int)$bits))));
        $end                 = long2ip((ip2long($subnet)) + pow(2, (32 - (int)$bits))-1);
        return new IpAddressRange(new IpAddress($start), new IpAddress($end));
    }

    /**
     * String representation of a range.
     *
     * Example output: "192.168.0.10 - 192.168.0.255"
     *
     * @return string
     */
    public function __toString()
    {
        return $this->isEmpty() ? '' : (string) $this->startIp.' - '. $this->endIp;
    }

    /**
     * Array representation of the ip range
     *
     * @return array
     */
    public function toArray()
    {
        if ($this->isEmpty()) {
            return [];
        }

        return [
            'startIp' => (string) $this->getStartIp(),
            'endIp'   => (string) $this->getEndIp(),
        ];
    }

    /**
     * Returns boolean TRUE if the range is empty, false otherwise.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->startIp === null || $this->endIp === null;
    }

    /**
     * Implement json serializable interface.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
