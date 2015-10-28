<?php namespace E1Stuff\LayeredContainer\Test;

use E1Stuff\LayeredContainer\LayeredContainer;
use E1Stuff\LayeredContainer\Test\Fake\Helper;
use E1Stuff\LayeredContainer\Test\Fake\Service;
use E1Stuff\LayeredContainer\Test\Fake\Storage;
use Illuminate\Container\Container;

/**
 * Date: 25.10.15
 * Time: 18:21
 * Author: Ivan Voskoboinyk
 * Email: ivan.voskoboinyk@gmail.com
 */
class LayeredContainerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Container */
    private $parent;
    /** @var LayeredContainer */
    private $local;

    protected function setUp()
    {
        parent::setUp();

        $this->parent = new Container();
        $this->local = new LayeredContainer($this->parent);
    }

    /**
     * @test
     */
    public function it_should_bind_services()
    {
        $this->local->bind('a', 1);
        $this->local->singleton('b', function() {
            return 2;
        });

        $this->assertTrue($this->local->bound('a'));
        $this->assertTrue($this->local->bound('b'));
    }

    /**
     * @test
     */
    public function it_should_make_services()
    {
        $this->local->instance('a', 1);
        $this->local->singleton('b', function() {
            return 2;
        });

        $this->assertEquals(1, $this->local->make('a'));
        $this->assertEquals(2, $this->local->make('b'));
    }

    /**
     * @test
     */
    public function it_should_inherit_parent_services()
    {
        $this->parent->instance('a', 1);
        $this->assertTrue($this->local->bound('a'));

        $this->assertEquals(1, $this->local->make('a'));
    }

    /**
     * @test
     */
    public function it_should_not_affect_parent_container()
    {
        $this->parent->instance('a', 1);
        $this->local->instance('a', 2);
        $this->local->instance('b', 3);

        $this->assertTrue($this->parent->bound('a'));
        $this->assertEquals(1, $this->parent->make('a'));

        $this->assertFalse($this->parent->bound('b'));
        $this->assertEquals(2, $this->local->make('a'));
        $this->assertEquals(3, $this->local->make('b'));
    }

    /**
     * @test
     */
    public function it_should_auto_inject_dependencies_from_parent_container()
    {
        $this->parent->singleton(Helper::class, function() {
            return new Helper(uniqid());
        });

        /** @var Service $service */
        $service = $this->local->make(Service::class);
        $this->assertInstanceOf(Service::class, $service);

        $this->assertEquals($this->parent->make(Helper::class)->key, $service->helper->key);
    }

    /**
     * @test
     */
    public function it_should_auto_inject_dependencies_from_local_container()
    {
        $this->local->singleton(Helper::class, function() {
            return new Helper(uniqid());
        });

        $this->parent->singleton(Service::class, function(Container $c) {
            return $c->build(Service::class);
        });

        /** @var Service $service */
        $service = $this->local->make(Service::class);
        $this->assertInstanceOf(Service::class, $service);

        $this->assertEquals($this->local->make(Helper::class)->key, $service->helper->key);
    }
}