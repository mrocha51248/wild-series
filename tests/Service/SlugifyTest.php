<?php

namespace App\Tests\Service;

use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SlugifyTest extends KernelTestCase
{
    /**
     * @dataProvider getSlugs
     */
    public function testSomething(string $text, string $slug): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        /** @var Slugify $slugify */
        $slugify = static::getContainer()->get(Slugify::class);
        $this->assertSame($slug, $slugify->generate($text));
    }

    public function getSlugs(): iterable
    {
        return [
            ['Hello World', 'hello-world'],
            ['Hello    World', 'hello-world'],
            [' Hello World ', 'hello-world'],
            ['Héllo World', 'hello-world'],
            ['HELLO World', 'hello-world'],
            ['HELLO^$*%World', 'hello-world'],
        ];
    }
}
