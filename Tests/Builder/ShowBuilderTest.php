<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Sonata\PropelAdminBundle\Admin\FieldDescription;
use Sonata\PropelAdminBundle\Builder\ShowBuilder;
use Symfony\Component\Form\Guess\TypeGuess;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Guesser\TypeGuesserInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Model\ModelManagerInterface;

/**
 * ShowBuilder tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ShowBuilderTest extends TestCase
{
    protected $admin;
    protected $typeGuesser;
    protected $list;

    public function setUp(): void
    {
        // configure the admin
        $this->admin = $this->createMock(AdminInterface::class);

        // configure the typeGuesser
        $this->typeGuesser = $this->createMock(TypeGuesserInterface::class);

        // configure the fields list
        $this->list = $this->createMock(FieldDescriptionCollection::class);
    }

    public function testCantAddFieldWithoutType(): void
    {
        $this->expectException(\RuntimeException::class);

        $modelManager = $this->createMock(ModelManagerInterface::class);
        $this->admin
            ->expects(self::once())
            ->method('getModelManager')
            ->willReturn($modelManager);

        $this->typeGuesser
            ->expects(self::once())
            ->method('guessType')
            ->willReturn(null);

        $builder = new ShowBuilder($this->typeGuesser);
        $field = new FieldDescription();

        $builder->addField($this->list, null, $field, $this->admin);
    }

    /**
     * @group           templates
     * @dataProvider    addFieldFixesTemplateProvider
     */
    public function testAddFieldFixesTemplate($templatesMap, $field, $type, $expectedTemplate): void
    {
        $builder = new ShowBuilder($this->typeGuesser, $templatesMap);
        $builder->addField($this->list, $type, $field, $this->admin);

        self::assertSame($expectedTemplate, $field->getTemplate());
    }

    public function addFieldFixesTemplateProvider(): array
    {
        $templatesMap = array(
            'text'      => 'textTemplate.html.twig',
            'integer'   => 'integerTemplate.html.twig',
        );

        // configure the fields descriptions
        $field = new FieldDescription();
        $field->setTemplate('customTextTemplate.html.twig');

        return array(
            array($templatesMap, new FieldDescription(), 'text',    'textTemplate.html.twig'),
            array($templatesMap, new FieldDescription(), 'integer', 'integerTemplate.html.twig'),
            array($templatesMap, $field,                 'text',    'customTextTemplate.html.twig'),
            array($templatesMap, new FieldDescription(), 'boolean',  null),
        );
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testAddFieldFixesFieldDescription($field, $givenOptions, $expectedOptions): void
    {
        $field->setOptions($givenOptions);

        $builder = new ShowBuilder($this->typeGuesser);
        $builder->addField($this->list, 'text', $field, $this->admin);

        foreach ($expectedOptions as $option => $value) {
            self::assertSame($value, $field->getOption($option), 'Testing option '.$option);
        }
    }

    public function optionsProvider(): array
    {
        $field = new FieldDescription();
        $field->setName('my_field');

        return array(
            /****************************
             * Code and label related options
             ***************************/
            // the default code is the field's name
            array(
                $field,
                array(),
                array('code' => 'my_field'),
            ),
            // the default label is the field's name
            array(
                $field,
                array(),
                array('label' => 'my_field'),
            ),
            // code and label are updated if given
            array(
                $field,
                array('code' => 'super code', 'label' => 'super label'),
                array('code' => 'super code', 'label' => 'super label'),
            ),
        );
    }
}
