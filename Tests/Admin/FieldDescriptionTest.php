<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Admin;

use PHPUnit\Framework\TestCase;
use Sonata\PropelAdminBundle\Admin\FieldDescription;

/**
 * FieldDescription tests.
 */
class FieldDescriptionTest extends TestCase
{
    public function testAssociationMapping(): void
    {
        $field = new FieldDescription();
        $field->setAssociationMapping(array(
            'type'      => 'integer',
            'fieldName' => 'position',
        ));

        self::assertEquals('integer', $field->getType());
        self::assertEquals('integer', $field->getMappingType());

        // cannot overwrite defined definition
        $field->setAssociationMapping(array(
            'type'      => 'overwrite?',
            'fieldName' => 'overwritten',
        ));

        self::assertEquals('integer', $field->getType());
        self::assertEquals('integer', $field->getMappingType());

        $field->setMappingType('string');
        self::assertEquals('string', $field->getMappingType());
        self::assertEquals('integer', $field->getType());
    }

    public function testSetParentAssociationMappings(): void
    {
        $field = new FieldDescription();
        $field->setParentAssociationMappings(array(array('test')));

        self::assertEquals(array(array('test')), $field->getParentAssociationMappings());
    }

    public function testSetParentAssociationMappingsAllowOnlyForArray(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('An association mapping must be an array');

        $field = new FieldDescription();
        $field->setParentAssociationMappings(array('test'));
    }

    public function testSetAssociationMappingAllowOnlyForArray(): void
    {
        $this->expectException(\RuntimeException::class);

        $field = new FieldDescription();
        $field->setAssociationMapping('test');
    }

    public function testSetFieldMappingAllowOnlyForArray(): void
    {
        $this->expectException(\RuntimeException::class);

        $field = new FieldDescription();
        $field->setFieldMapping('test');
    }

    public function testSetFieldMappingSetType(): void
    {
        $fieldMapping = array(
            'type'         => 'integer',
        );

        $field = new FieldDescription();
        $field->setFieldMapping($fieldMapping);

        self::assertEquals('integer', $field->getType());
    }

    public function testSetFieldMappingSetMappingType(): void
    {
        $fieldMapping = array(
            'type'         => 'integer',
        );

        $field = new FieldDescription();
        $field->setFieldMapping($fieldMapping);

        self::assertEquals('integer', $field->getMappingType());
    }

    public function testGetTargetEntity(): void
    {
        $associationMapping = array(
            'type'         => 'integer',
            'targetEntity' => 'someValue',
        );

        $field = new FieldDescription();

        self::assertNull($field->getTargetEntity());

        $field->setAssociationMapping($associationMapping);

        self::assertEquals('someValue', $field->getTargetEntity());
    }

    public function testGetValue(): void
    {
        $mockedObject = $this->getMockBuilder('stdClass')->addMethods(array('myMethod'))->getMock();
        $mockedObject->expects(self::once())
            ->method('myMethod')
            ->willReturn('myMethodValue');

        $field = new FieldDescription();
        $field->setName('irrelevant');
        $field->setOption('code', 'myMethod');

        self::assertEquals('myMethodValue', $field->getValue($mockedObject));
    }

    public function testGetValueWhenCannotRetrieve(): void
    {
        $this->expectException(\Sonata\AdminBundle\Exception\NoValueException::class);

        $mockedObject = $this->getMockBuilder('stdClass')->addMethods(array('myMethod'))->getMock();
        $mockedObject->expects($this->never())
            ->method('myMethod')
            ->willReturn('myMethodValue');

        $field = new FieldDescription();
        $field->setName('irrelevant');

        self::assertEquals('myMethodValue', $field->getValue($mockedObject));
    }

    public function testIsIdentifierFromFieldMapping(): void
    {
        $fieldMapping = array(
            'type'      => 'integer',
            'fieldName' => 'position',
            'id'        => 'someId',
        );

        $field = new FieldDescription();
        $field->setFieldMapping($fieldMapping);

        self::assertEquals('someId', $field->isIdentifier());
    }
}
