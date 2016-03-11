<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Toggle\Entity\MarketableToggle;

class MarketableToggleToArrayConverter
{
    /**
     * @param MarketableToggle $toggle
     * @return array|null
     */
    public function getArrayFromToggle( MarketableToggle $toggle )
    {
        if ( empty( $toggle ) ) {
            return null;
        }

        return [
                "id"         => $toggle->getId(),
                "name"       => $toggle->getMarketingToggleTitle(),
                "summary"    => $toggle->getDescriptionOfToggle(),
                "url"        => $toggle->getGuideUrl(),
                "screenshot" => $toggle->getScreenshotUrl(),
                "type"       => $toggle->getType()
        ];
    }

    /**
     * @param MarketableToggle[] $toggles
     * @return array
     */
    public function getArrayFromToggles( array $toggles )
    {
        $results = [ ];
        foreach ( $toggles as $toggle ) {
            $results[] = $this->getArrayFromToggle( $toggle );
        }

        return $results;
    }
}
