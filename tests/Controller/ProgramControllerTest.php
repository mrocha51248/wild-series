<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProgramControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUsersForNew
     */
    public function testNew(?string $username, int $expected): void
    {
        $client = static::createClient();

        if ($username) {
            $userRepository = static::getContainer()->get(UserRepository::class);
            $user = $userRepository->findOneByUsername($username);
            $client->loginUser($user);
        }

        $client->request('GET', '/program/new');

        $this->assertResponseStatusCodeSame($expected);
        if ($client->getResponse()->isSuccessful()) {
            $client->submitForm('Save', [
                'program[title]' => 'Some Title' . $username,
                'program[summary]' => 'My summary',
                'program[poster]' => 'http://host/image.jif',
                'program[category]' => "1",
                'program[actors][2]' => "3",
                'program[actors][3]' => "4",
            ]);
    
            $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        }
    }

    public function getUsersForNew(): iterable
    {
        return [
            [null, Response::HTTP_FOUND],
            ['User 1', Response::HTTP_FORBIDDEN],
            ['Contributor 1', Response::HTTP_OK],
            ['Administrator', Response::HTTP_OK],
        ];
    }
}
