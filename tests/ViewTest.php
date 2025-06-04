<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testSetAndGet()
    {
        $tmpFile = __DIR__ . '/tmp.tpl';
        file_put_contents($tmpFile, 'dummy');
        $view = new View($tmpFile);
        $view->set('foo', 'bar');
        $this->assertSame('bar', $view->get('foo'));
        unlink($tmpFile);
    }
}
