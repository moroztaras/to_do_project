<?php

/**
 * Created by PhpStorm.
 * User: moroztaras
 * Date: 31.01.19
 * Time: 9:20.
 */

namespace App\Tests\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegistrationAction()
    {
        $client = static::createClient();
        $data = [
            'password' => 'moroztaras',
            'email' => time().'@i.ua',
            ];
        $client->request('POST', '/registration',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertContains($data['email'], $client->getResponse()->getContent());
    }

    public function testAuthorizeAction()
    {
        $client = static::createClient();
        $data = [
            'password' => 'moroztaras',
            'email' => 'moroztaras@i.ua',
        ];
        $client->request('POST', '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $kernel = self::bootKernel();
        $em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        /**
         * @var User
         */
        $user = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        $this->assertContains($user->getApiToken(), $client->getResponse()->getContent());
    }
}
