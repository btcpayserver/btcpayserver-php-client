<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/php-bitpay-client/blob/master/LICENSE
 */

namespace BTCPayServer;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUsers()
    {
        $application = new Application();

        $this->assertNotNull($application);

        $this->assertInternalType('array', $application->getUsers());
        $this->assertEmpty($application->getUsers());
    }

    /**
     * @depends testGetUsers
     */
    public function testAddUser()
    {
        $application = new Application();

        $this->assertNotNull($application);

        $application->addUser($this->getMockUser());

        $this->assertInternalType('array', $application->getUsers());
        $this->assertCount(1, $application->getUsers());
    }

    public function testGetOrgs()
    {
        $application = new Application();

        $this->assertNotNull($application);

        $this->assertInternalType('array', $application->getOrgs());
        $this->assertEmpty($application->getOrgs());
    }

    /**
     * @depends testGetOrgs
     */
    public function testAddOrg()
    {
        $application = new Application();

        $this->assertNotNull($application);

        $application->addOrg($this->getMockOrg());

        $this->assertInternalType('array', $application->getOrgs());
        $this->assertCount(1, $application->getOrgs());
    }

    private function getMockUser()
    {
        return $this->getMock('BTCPayServer\UserInterface');
    }

    private function getMockOrg()
    {
        return $this->getMock('BTCPayServer\OrgInterface');
    }
}
