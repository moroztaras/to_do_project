<?php

/**
 * Created by PhpStorm.
 * User: moroztaras
 * Date: 31.01.19
 * Time: 8:01.
 */

namespace App\Tests\Controller\Api;

use App\Entity\CheckList;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AttachmentControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testAddAction()
    {
        $list = $this->entityManager->getRepository(CheckList::class)->findOneBy(['name' => 'List Name']);
        $item = $list->getItems()->last();
        $client = static::createClient();
        $data = [
            'text' => 'My Text',
        ];
        $client->request('POST', '/api/list/'.$list->getId().'/item/'.$item->getId().'/attachment',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json',
                'HTTP_X-API_KEY' => 'my-test-api-token',
            ],
            json_encode($data)
        );
        $this->assertContains($data['text'], $client->getResponse()->getContent());
    }

    public function testRemoveAction()
    {
        $list = $this->entityManager->getRepository(CheckList::class)->findOneBy(['name' => 'List Name']);
        $item = $list->getItems()->last();
        $attachment = $item->getAttachment();
        $client = static::createClient();
        $client->request('DELETE', '/api/list/'.$list->getId().'/item/'.$item->getId().'/attachment/'.$attachment->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json',
                'HTTP_X-API_KEY' => 'my-test-api-token',
            ]
        );
        $this->assertContains('moroztaras@i.ua', $client->getResponse()->getContent());
    }
}
