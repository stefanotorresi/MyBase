<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyBaseTest\Controller\TestAsset;

use MyBase\Controller\AbstractConsoleController;

class ConcreteConsoleController extends AbstractConsoleController
{
    public function testAction()
    {
        return 'testAction dispatched';
    }
}
