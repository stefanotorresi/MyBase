<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBase\Test\Form\Element;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Form\Form;
use Zend\Validator\InArray;

class AbstractPrefilledSelectTest extends TestCase
{
    public function testNullCustomValidatorMessagePropertyLeavesValidatorsDefault()
    {
        $select = new TestAsset\PrefilledSelect('select');

        $form = new Form();
        $form->add($select);

        $form->setData([
            'select' => 'asd',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertNull($select->getInArrayValidatorMessage());
        $this->assertNotNull($form->getMessages('select')[InArray::NOT_IN_ARRAY]);
    }

    public function testCanSetCustomValidatorMessageOption()
    {
        $message = 'some message';

        $select = new TestAsset\PrefilledSelect('select');
        $select->setOptions([
            'inarray_validator_message' => $message,
        ]);

        $this->assertSame($message, $select->getOption('inarray_validator_message'));
        $this->assertSame($message, $select->getInArrayValidatorMessage());
    }

    public function testCustomValidatorMessageSetterUpdatesValidator()
    {
        $select = new TestAsset\PrefilledSelect('select');
        $message = 'some message';
        $select->setInArrayValidatorMessage($message);

        $reflection = new \ReflectionMethod(get_class($select), 'getValidator');
        $reflection->setAccessible(true);
        $validator = $reflection->invoke($select); /** @var $validator InArray */

        $this->assertSame($message, $validator->getMessageTemplates()[InArray::NOT_IN_ARRAY]);
    }

    public function testFormIntegration()
    {
        $select = new TestAsset\PrefilledSelectWithMessage('select');
        $form = new Form();
        $form->add($select);

        $form->setData([
            'select' => 'asd',
        ]);

        $this->assertFalse($form->isValid());

        $this->assertEquals(
            $select->getInArrayValidatorMessage(),
            $form->getMessages('select')[InArray::NOT_IN_ARRAY]
        );
    }

    public function testIntegrationWithMultipleOption()
    {
        $select = new TestAsset\PrefilledSelectWithMessage('select');
        $select->setAttribute('multiple', true);

        $form = new Form();
        $form->add($select);

        $form->setData([
            'select' => ['value', 'value2'],
        ]);

        $this->assertTrue($form->isValid());

        $form->setData([
            'select' => ['value2', 'bar'],
        ]);

        $this->assertFalse($form->isValid());

        $messages = $form->getMessages('select');

        $this->assertEquals(
            $select->getInArrayValidatorMessage(),
            $messages[0][InArray::NOT_IN_ARRAY]
        );
    }
}
