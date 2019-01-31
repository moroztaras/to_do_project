<?php

/**
 * Created by PhpStorm.
 * User: moroztaras
 * Date: 31.01.19
 * Time: 8:20.
 */

namespace App\Tests\Controller\Api;

use App\Entity\CheckList;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckListControllerTest extends WebTestCase
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

    public function testCreateAction()
    {
        $client = static::createClient();
        $data = [
            'name' => 'My List',
            'expire' => '2019-11-19',
        ];
        $client->request('POST', '/api/checklist',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json',
              'HTTP_X-API_KEY' => 'my-test-api-token',
            ],
            json_encode($data)
        );
        $this->assertContains($data['name'], $client->getResponse()->getContent());
    }

    public function testEditAction()
    {
        $listId = $this->entityManager->getRepository(CheckList::class)->findOneBy(['name' => 'My List'])->getId();
        $client = static::createClient();
        $data = [
            'name' => 'My List New',
            'expire' => '2020-01-19',
        ];
        $client->request('PUT', '/api/checklist/'.$listId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json',
                'HTTP_X-API_KEY' => 'my-test-api-token',
            ],
            json_encode($data)
        );
        $this->assertContains($data['name'], $client->getResponse()->getContent());
    }

    public function testDeleteAction()
    {
        $listId = $this->entityManager->getRepository(CheckList::class)->findOneBy(['name' => 'My List New'])->getId();
        $client = static::createClient();
        $client->request('DELETE', '/api/checklist/'.$listId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json',
                'HTTP_X-API_KEY' => 'my-test-api-token',
            ]
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
