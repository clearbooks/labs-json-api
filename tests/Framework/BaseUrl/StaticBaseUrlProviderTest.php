<?php
use Clearbooks\LabsApi\Framework\BaseUrl\StaticBaseUrlProvider;

class StaticBaseUrlProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function givenConstructorArgument_returnArgumentAsBaseUrl()
    {
        $provider = new StaticBaseUrlProvider( 'cats' );
        $this->assertEquals( 'cats', $provider->getBaseUrl() );
    }
}
