<?php

namespace Oneup\UploaderBundle\Tests\Uploader\Naming;

use Oneup\UploaderBundle\Uploader\Naming\FormatNamer;
use Symfony\Component\HttpFoundation\Request;

class FormatNamerTest extends \PHPUnit_Framework_TestCase
{
    public function testConsistentPlaceholdersData()
    {
        $request = new Request(
            array(),
            array(
                'root1' => 1,
                'root2' => array('nested1' => 'foo'),
            )
        );

        $file = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        $file
            ->expects($this->any())
            ->method('guessExtension')
            ->will($this->returnValue('tmp'));

        $namer = new FormatNamer('test-{root1}-{root2.nested1}');
        $this->assertEquals('test-1-foo.tmp', $namer->name($file, $request));
    }

    public function testInconsistentPlaceholdersData()
    {
        $request = new Request(
            array(),
            array(
                'root1' => false,
                'root2' => false,
            )
        );

        $file = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock();

        $file
            ->expects($this->any())
            ->method('guessExtension')
            ->will($this->returnValue('tmp'));

        $namer = new FormatNamer('test-{root1}-{root2.nested1}');
        $this->assertEquals('test-root1-root2_nested1.tmp', $namer->name($file, $request));
    }
}
