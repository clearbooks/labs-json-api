<?php
namespace Clearbooks\LabsApi\Toggle;

use Clearbooks\Labs\Toggle\Object\GetTogglesVisibleWithoutReleaseResponse;
use Clearbooks\Labs\Toggle\UseCase\GetGroupTogglesVisibleWithoutRelease;
use Clearbooks\Labs\Toggle\UseCase\GetUserTogglesVisibleWithoutRelease;

class GetVisibleTogglesWithoutReleaseSpy implements GetUserTogglesVisibleWithoutRelease, GetGroupTogglesVisibleWithoutRelease
{
    /**
     * @var bool
     */
    private $called = false;

    /**
     * @return GetTogglesVisibleWithoutReleaseResponse
     */
    public function execute()
    {
        $this->called = true;
        return new GetTogglesVisibleWithoutReleaseResponse( [ ] );
    }

    /**
     * @return bool
     */
    public function wasCalled()
    {
        return $this->called;
    }
}
