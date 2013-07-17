<?php

namespace Oneup\UploaderBundle\Tests\Uploader\Naming;

use Oneup\UploaderBundle\Uploader\Naming\UniqidNamer;

class UniqidNamerTest extends \PHPUnit_Framework_TestCase
{
    public function testNamerReturnsName()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $file = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $file
            ->expects($this->any())
            ->method('guessExtension')
            ->will($this->returnValue('jpeg'))
        ;

        $namer = new UniqidNamer();
        $this->assertRegExp('/[a-z0-9]{13}.jpeg/', $namer->name($file, $request));
    }

    public function testNamerReturnsUniqueName()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;


        $file = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $file
            ->expects($this->any())
            ->method('guessExtension')
            ->will($this->returnValue('jpeg'))
        ;

        $namer = new UniqidNamer();

        // get two different names
        $name1 = $namer->name($file, $request);
        $name2 = $namer->name($file, $request);

        $this->assertNotEquals($name1, $name2);
    }
}
