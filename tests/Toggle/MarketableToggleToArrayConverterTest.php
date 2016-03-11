<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Toggle\Entity\MarketableToggle;
use Clearbooks\LabsMysql\Toggle\Entity\Toggle;
use Clearbooks\Labs\Db\Table\Toggle as ToggleTable;

class MarketableToggleToArrayConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MarketableToggleToArrayConverter
     */
    private $marketableToggleToArrayConverter;

    public function setUp()
    {
        parent::setUp();
        $this->marketableToggleToArrayConverter = new MarketableToggleToArrayConverter();
    }

    /**
     * @test
     */
    public function GivenEmptyArray_ReturnEmptyArray()
    {
        $this->assertEquals( [ ], $this->marketableToggleToArrayConverter->getArrayFromToggles( [ ] ) );
    }

    /**
     * @test
     */
    public function GivenMarketableToggle_ReturnArrayForm()
    {
        $toggle = new Toggle( 1, "test", null, true, ToggleTable::TYPE_SIMPLE, "http://screenshot.url", "summary", "", "", "", "http://guide.url" );

        $this->assertEquals(
                [
                        "id"         => $toggle->getId(),
                        "name"       => $toggle->getMarketingToggleTitle(),
                        "summary"    => $toggle->getDescriptionOfToggle(),
                        "url"        => $toggle->getGuideUrl(),
                        "screenshot" => $toggle->getScreenshotUrl(),
                        "type"       => $toggle->getType()
                ],
                $this->marketableToggleToArrayConverter->getArrayFromToggle( $toggle )
        );
    }

    /**
     * @test
     */
    public function GivenMarketableToggles_ReturnArrayForms()
    {
        /** @var MarketableToggle[] $toggles */
        $toggles = [ ];
        $toggles[] = new Toggle( 1, "test", null, true, ToggleTable::TYPE_SIMPLE, "http://screenshot.url", "summary", "", "", "", "http://guide.url" );
        $toggles[] = new Toggle( 2, "test2", null, true, ToggleTable::TYPE_SIMPLE, "http://screenshot2.url", "summary2", "", "", "", "http://guide2.url" );

        $this->assertEquals(
                [
                        [
                                "id"         => $toggles[0]->getId(),
                                "name"       => $toggles[0]->getMarketingToggleTitle(),
                                "summary"    => $toggles[0]->getDescriptionOfToggle(),
                                "url"        => $toggles[0]->getGuideUrl(),
                                "screenshot" => $toggles[0]->getScreenshotUrl(),
                                "type"       => $toggles[0]->getType()
                        ],
                        [
                                "id"         => $toggles[1]->getId(),
                                "name"       => $toggles[1]->getMarketingToggleTitle(),
                                "summary"    => $toggles[1]->getDescriptionOfToggle(),
                                "url"        => $toggles[1]->getGuideUrl(),
                                "screenshot" => $toggles[1]->getScreenshotUrl(),
                                "type"       => $toggles[1]->getType()
                        ]
                ],
                $this->marketableToggleToArrayConverter->getArrayFromToggles( $toggles )
        );
    }
}
