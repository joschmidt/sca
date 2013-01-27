<?php
/**
 * Copyright (C) 2013 Johannes Schmidt <joschmidt@users.sourceforge.net>
 * 
 * This library is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU Lesser General Public License as published by 
 * the Free Software Foundation; either version 3 of the License, or 
 * (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU Lesser General Public License for more details.
 *  
 * You should have received a copy of the GNU Lesser General Public License 
 * along with this library; if not, see <https://www.gnu.org/licenses/lgpl.html>.
 */

namespace App\Tests;

use App\ContextAware;

class ContextAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testNewInstance()
    {
        $contextAware = ContextAware::newInstance();
        $hash1 = spl_object_hash($contextAware);
        
        $contextAware = ContextAware::newInstance();
        $hash2 = spl_object_hash($contextAware);
        
        $this->assertEquals($hash1, $hash2);
        
        $hash3 = spl_object_hash($this);
        
        $this->assertFalse($hash1 === $hash3);
    }
    
    public function testSetContext()
    {
        $contextAware = ContextAware::newInstance();
        $contextAware->setContext(array('test' => true));
        
        $this->assertTrue($contextAware['test']);
        
        $contextAware->setContext(array('test' => false));
        
        $this->assertTrue($contextAware['test']);
        
        $contextAware->setContext(array('foo' => 'bar'));
        
        $this->assertTrue($contextAware['test']);
        $this->assertEquals($contextAware['foo'], 'bar');
    }
    
    public function testOffsetExists()
    {
        $contextAware = ContextAware::newInstance();
        $contextAware->setContext(array('test' => true));
        
        $this->assertTrue(isset($contextAware['test']));
    }
    
    public function testOffsetGetInvalid()
    {
        $contextAware = ContextAware::newInstance();
        try {
            $contextAware['baz'];
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue($e instanceof \InvalidArgumentException);
        }
    }
    
    public function testOffsetSet()
    {
        $contextAware = ContextAware::newInstance();
        $contextAware->setContext(array('test' => true));
        
        try {
            $contextAware['test'] = false;
            $this->fail();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof \Exception);
        }
        
        try {
            $contextAware['foo'] = 'bar';
            $this->fail();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof \Exception);
        }
    }
    
    public function testOffsetUnset()
    {
        $contextAware = ContextAware::newInstance();
        $contextAware->setContext(array('test' => true));
        
        try {
            unset($contextAware['test']);
            $this->fail();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof \Exception);
        }
    }
}