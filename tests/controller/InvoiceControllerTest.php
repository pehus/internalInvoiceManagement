<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class InvoiceControllerTest extends WebTestCase
{
    public function testListInvoices(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/invoice',
            [],
            [],
            ['HTTP_X-API-KEY' => 'your-secret-api-key']
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateInvoice(): void
    {
        $client = static::createClient();

        $jsonData = [
            'clientName' => 'Test Client',
            'statusId' => 1,
            'items' => [
                ['name' => 'Item 1', 'quantity' => 2, 'unitPrice' => 50.0],
                ['name' => 'Item 2', 'quantity' => 1, 'unitPrice' => 30.0]
            ]
        ];

        $client->request(
            'POST',
            '/api/invoice',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-KEY' => 'your-secret-api-key'],
            json_encode($jsonData)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('OK', $responseData['message']);
    }

    public function testGetSingleInvoice(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/invoice/1',
            [],
            [],
            ['HTTP_X-API-KEY' => 'your-secret-api-key']
        );

        if ($client->getResponse()->getStatusCode() === Response::HTTP_OK)
        {
            $this->assertResponseIsSuccessful();
            $this->assertJson($client->getResponse()->getContent());
        }
        else
        {
            $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        }
    }

    public function testDeleteInvoice(): void
    {
        $client = static::createClient();

        // Nejprve vytvoříme fakturu, abychom ji mohli smazat
        $jsonData = [
            'clientName' => 'Test Client to Delete',
            'statusId' => 1,
            'items' => [
                ['name' => 'Item 1', 'quantity' => 1, 'unitPrice' => 100.0]
            ]
        ];

        $client->request(
            'POST',
            '/api/invoice',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-KEY' => 'your-secret-api-key'],
            json_encode($jsonData)
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $invoiceId = $responseData['id'] ?? null;

        if ($invoiceId)
        {
            // Smažeme fakturu
            $client->request(
                'DELETE',
                "/api/invoice/{$invoiceId}",
                [],
                [],
                ['HTTP_X-API-KEY' => 'your-secret-api-key']
            );
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }
        else
        {
            $this->fail('Invoice creation failed, cannot test deletion.');
        }
    }
}
