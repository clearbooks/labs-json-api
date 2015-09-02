<?php
namespace Clearbooks\LabsApi\Framework\BaseUrl;

class StaticBaseUrlProvider implements BaseUrlProvider
{
    /**
     * @var string
     */
    private $url;

    public function __construct( $url )
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->url;
    }
}