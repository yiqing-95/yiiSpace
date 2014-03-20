<?php

class SluggableBehaviorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testProperties()
    {
        $sluggable = new SluggableBehavior();
        $this->assertTrue(property_exists($sluggable, 'columns'));
        $this->assertTrue(property_exists($sluggable, 'unique'));
        $this->assertTrue(property_exists($sluggable, 'update'));
        $this->assertTrue(property_exists($sluggable, 'slugColumn'));
        $this->assertTrue(property_exists($sluggable, 'useInflector'));
        $this->assertTrue(property_exists($sluggable, 'toLower'));
        $this->assertTrue(property_exists($sluggable, 'toLower'));
        $this->assertTrue(property_exists($sluggable, '_defaultColumnsToCheck'));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage setColumns() must be of the type array, string given
     */
    public function testColumnsPropertyHasToBeAnArray()
    {
        $sluggable = new SluggableBehavior();
        $sluggable->columns = 'invalid';
    }

    public function testUniquePropertyHasToBeBoolean()
    {
        $sluggable = new SluggableBehavior();
        $sluggable->unique = 0;
        $this->assertFalse($sluggable->unique);
        $sluggable->unique = 'I will be converted to true';
        $this->assertTrue($sluggable->unique);
    }

    public function testUpdatePropertyHasToBeBoolean()
    {
        $sluggable = new SluggableBehavior();
        $sluggable->update = 0;
        $this->assertFalse($sluggable->update);
        $sluggable->update = 'I will be converted to true';
        $this->assertTrue($sluggable->update);
    }

    public function testSlugColumnIsString()
    {
        $sluggable = new SluggableBehavior();
        $sluggable->slugColumn = 1;
        $this->assertSame("1", $sluggable->slugColumn);
    }

    public function testUseInflectorPropertyHasToBeBoolean()
    {
        $sluggable = new SluggableBehavior();
        $sluggable->useInflector = 0;
        $this->assertFalse($sluggable->useInflector);
        $sluggable->useInflector = 'I will be converted to true';
        $this->assertTrue($sluggable->useInflector);
    }

    public function testToLowerPropertyHasToBeBoolean()
    {
        $sluggable = new SluggableBehavior();
        $sluggable->toLower = 0;
        $this->assertFalse($sluggable->toLower);
        $sluggable->toLower = 'I will be converted to true';
        $this->assertTrue($sluggable->toLower);
    }
}
