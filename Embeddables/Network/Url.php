<?php
namespace Muspelheim\EmbeddablesBundle\Embeddables\Network;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Url
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * Url constructor.
     * @param null $url
     */
    public function __construct($url = null)
    {
        if ($url && false === filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid URL.');
        }
        $this->url = (string) $url;
    }

    /**
     * @param $url
     * @return Url
     */
    public static function fromString($url)
    {
        return new self($url);
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->getPartOfUrl(PHP_URL_SCHEME);
    }

    /**
     * @return string|null
     */
    public function getUser()
    {
        return $this->getPartOfUrl(PHP_URL_USER);
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->getPartOfUrl(PHP_URL_PASS);
    }

    /**
     * @return string|null
     */
    public function getHost()
    {
        return $this->getPartOfUrl(PHP_URL_HOST);
    }

    /**
     * Returns the port.
     *
     * @return string|null
     */
    public function getPort()
    {
        return $this->getPartOfUrl(PHP_URL_PORT);
    }

    /**
     * Returns the path.
     *
     * @return string|null
     */
    public function getPath()
    {
        return $this->getPartOfUrl(PHP_URL_PATH);
    }

    /**
     * Returns the query.
     *
     * @return string|null
     */
    public function getQuery()
    {
        return $this->getPartOfUrl(PHP_URL_QUERY);
    }

    /**
     * @return string|null
     */
    public function getFragment()
    {
        return $this->getPartOfUrl(PHP_URL_FRAGMENT);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->url;
    }

    /**
     * @param $component
     * @return string|null
     */
    private function getPartOfUrl($component)
    {
        return $this->url ? parse_url($this->url, $component) : null;
    }
}
