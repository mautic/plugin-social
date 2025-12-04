<?php

declare(strict_types=1);

namespace MauticPlugin\MauticSocialBundle\Tests\Functional\V2API;

use Mautic\CoreBundle\Test\MauticMysqlTestCase;
use MauticPlugin\MauticSocialBundle\Entity\Monitoring;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;

final class MonitoringV2ApiTest extends MauticMysqlTestCase
{
    public function testGetOperationWorks(): void
    {
        $monitoring = new Monitoring();
        $monitoring->setTitle('Test Monitoring');
        $monitoring->setNetworkType('type');
        $this->em->persist($monitoring);
        $this->em->flush();

        $monitoringId = $monitoring->getId();

        $this->client->request('GET', '/api/v2/monitorings/'.$monitoringId);

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        Assert::assertArrayHasKey('id', $responseData);
        Assert::assertSame($monitoringId, $responseData['id']);
        Assert::assertSame('Test Monitoring', $responseData['title']);
        Assert::assertSame('type', $responseData['networkType']);
    }

    public function testPutOperationWorksGloballyForMonitoringEntity(): void
    {
        $monitoring = new Monitoring();
        $monitoring->setTitle('Original Monitoring');
        $monitoring->setNetworkType('type');
        $monitoring->setDescription('Test Description');
        $this->em->persist($monitoring);
        $this->em->flush();

        $originalId = $monitoring->getId();

        $this->client->request(
            'PUT',
            '/api/v2/monitorings/'.$originalId,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT'  => 'application/ld+json',
            ],
            json_encode([
                'title'         => 'Updated Monitoring',
                'networkType'   => 'type',
                'description'   => 'Test Description Updated',
            ])
        );

        $this->assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        Assert::assertSame($originalId, $response['id']);
        Assert::assertSame('Updated Monitoring', $response['title']);
        Assert::assertSame('Test Description Updated', $response['description']);
    }

    public function testPutOperationUpdatesExistingMonitoring(): void
    {
        $monitoring = new Monitoring();
        $monitoring->setTitle('Original Monitoring');
        $monitoring->setNetworkType('type');
        $this->em->persist($monitoring);
        $this->em->flush();

        $originalId = $monitoring->getId();

        $this->client->request(
            'PUT',
            '/api/v2/monitorings/'.$originalId,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT'  => 'application/ld+json',
            ],
            json_encode([
                'title'       => 'Updated Monitoring',
                'networkType' => 'type',
            ])
        );

        $this->assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        Assert::assertSame($originalId, $response['id']);
        Assert::assertSame('Updated Monitoring', $response['title']);
        Assert::assertSame('type', $response['networkType']);

        $this->em->clear();
        $monitorings = $this->em->getRepository(Monitoring::class)->findAll();
        Assert::assertCount(1, $monitorings);
        Assert::assertSame($originalId, $monitorings[0]->getId());
        Assert::assertSame('Updated Monitoring', $monitorings[0]->getTitle());
        Assert::assertSame('type', $monitorings[0]->getNetworkType());
    }

    public function testPutOperationReturns404ForNonExistentMonitoring(): void
    {
        $nonExistentId = 99999;

        $this->client->request(
            'PUT',
            '/api/v2/monitorings/'.$nonExistentId,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT'  => 'application/ld+json',
            ],
            json_encode([
                'title'       => 'Test Monitoring',
                'networkType' => 'type',
            ])
        );

        Assert::assertSame(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testPostOperationCreatesNewMonitoring(): void
    {
        $this->client->request(
            'POST',
            '/api/v2/monitorings',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT'  => 'application/ld+json',
            ],
            json_encode([
                'title'       => 'New Monitoring',
                'networkType' => 'type',
            ])
        );

        $this->assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        Assert::assertIsInt($response['id']);
        Assert::assertSame('New Monitoring', $response['title']);
        Assert::assertSame('type', $response['networkType']);

        $this->em->clear();
        $monitoring = $this->em->getRepository(Monitoring::class)->find($response['id']);
        Assert::assertNotNull($monitoring);
        Assert::assertSame('New Monitoring', $monitoring->getTitle());
    }

    public function testPutOperationReplacesEntireResource(): void
    {
        $monitoring = new Monitoring();
        $monitoring->setTitle('Original Monitoring');
        $monitoring->setNetworkType('type');
        $this->em->persist($monitoring);
        $this->em->flush();

        $originalId = $monitoring->getId();

        $this->client->request(
            'PUT',
            '/api/v2/monitorings/'.$originalId,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT'  => 'application/ld+json',
            ],
            json_encode([
                'title'       => 'Updated Monitoring Title Only',
                'networkType' => 'type',
                // description intentionally omitted
            ])
        );

        Assert::assertSame(200, $this->client->getResponse()->getStatusCode());

        $response = json_decode($this->client->getResponse()->getContent(), true);

        Assert::assertSame($originalId, $response['id']);
        Assert::assertSame('Updated Monitoring Title Only', $response['title']);

        if (array_key_exists('description', $response)) {
            Assert::assertNull($response['description']);
        }

        $this->em->clear();
        $updatedMonitoring = $this->em->getRepository(Monitoring::class)->find($originalId);
        Assert::assertNotNull($updatedMonitoring);
        Assert::assertSame('Updated Monitoring Title Only', $updatedMonitoring->getTitle());
        Assert::assertNull($updatedMonitoring->getDescription());
    }
}
