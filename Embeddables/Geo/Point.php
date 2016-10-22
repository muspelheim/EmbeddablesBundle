<?php

namespace Muspelheim\EmbeddablesBundle\Embeddables\Geo;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class Point implements JsonSerializable
{
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $point;

    /**
     * @param float $lat Latitude
     * @param float $lng Longitude
     */
    public function __construct($lat = null, $lng = null)
    {
        if ($lat && $lng) {
            if ($lat < -90.0 || $lat > 90.0 || $lng < -180.0 || $lng > 180.0) {
                throw new \InvalidArgumentException('Given latitude longitude pair is invalid.');
            }

            $this->point = [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
            ];
        }
    }

    /**
     * @return array
     */
    public function toElastic()
    {
        if (empty($this->point)) {
            return [];
        }

        return [
            'lat' => $this->point['lat'],
            'lon' => $this->point['lng'],
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->point ?: [];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (empty($this->point)) {
            return '';
        }

        return $this->point['lat'].' '.$this->point['lng'];
    }
}
