<?php
/**
 * Created by PhpStorm.
 * User: lukaskammerling
 * Date: 11.07.18
 * Time: 18:31
 */

namespace Tests\tests\Servers;

use LKDev\HetznerCloud\Models\Servers\Servers;
use Tests\TestCase;

/**
 *
 */
class ServersTest extends TestCase
{
    /**
     * @var Servers
     */
    protected $servers;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->servers = new Servers();
    }

    /**
     *
     */
    public function testGet()
    {
        $server = $this->servers->get(1235);
        $this->assertEquals($server->id, 1235);
        $this->assertEquals($server->name, 'my-server');
        $this->assertEquals($server->status, 'running');
    }

    /**
     *
     */
    public function testAll()
    {
        $servers = $this->servers->all();

        $this->assertEquals(count($servers), 2);
        $this->assertEquals($servers[0]->id, 42);
        $this->assertEquals($servers[0]->name, 'my-server');
        $this->assertEquals($servers[1]->id, 466);
        $this->assertEquals($servers[1]->name, 'my-server-2');
    }
}