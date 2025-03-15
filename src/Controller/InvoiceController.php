<?php

namespace App\Controller;

use App\Dto\InvoiceDto;
use App\Dto\ResponseDto;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\InvoiceStatus;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/invoice', name: 'api_invoice_')]
final class InvoiceController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em, SerializerService $serializerService): JsonResponse
    {
        try
        {
            /** @var Invoice[] $invoices */
            $invoices = $em->getRepository(Invoice::class)->findAll();
            $responseDto = (new ResponseDto())
                ->setMessage("OK")
                ->setData($invoices);

            $jsonData = $serializerService->getSerializer()->serialize($responseDto, 'json');
            return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
        }
        catch (Throwable $t)
        {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, $t->getMessage());
        }
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?Invoice $invoice, SerializerService $serializerService): JsonResponse
    {
        try
        {
            if ($invoice === null)
            {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Invoice not found");
            }

            $responseDto = (new ResponseDto())
                ->setMessage("OK")
                ->setData($invoice);

            $jsonData = $serializerService->getSerializer()->serialize($responseDto, 'json');
            return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
        }
        catch (Throwable $t)
        {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, $t->getMessage());
        }
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        SerializerService $serializerService
    ): JsonResponse
    {
        try
        {
            $jsonData = $request->getContent();

            if ($jsonData === null)
            {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Invalid request data");
            }

            /** @var InvoiceDto $invoiceDto */
            $invoiceDto = $serializerService->getSerializer()->deserialize(
                $jsonData,
                InvoiceDto::class,
                'json'
            );

            if ($invoiceDto->getClientName() === null)
            {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Missing clientName");
            }

            if ($invoiceDto->getStatusId() === null)
            {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Missing statusId");
            }

            if ($invoiceDto->getItems() === null)
            {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Missing invoiceItems");
            }

            $invoiceStatusRepository = $em->getRepository(InvoiceStatus::class);
            $invoiceStatusEntity = $invoiceStatusRepository->find($invoiceDto->getStatusId());

            if ($invoiceStatusEntity === null)
            {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Status not exists");
            }

            $em->beginTransaction();
            $totalAmount = 0.0;
            $items = [];
            foreach ($invoiceDto->getItems() as $itemDto)
            {
                $invoiceItem = new InvoiceItem();
                $invoiceItem->setName($itemDto->getName());
                $invoiceItem->setQuantity($itemDto->getQuantity());
                $invoiceItem->setUnitPrice($itemDto->getUnitPrice());

                $totalAmount += $itemDto->getQuantity() * $itemDto->getUnitPrice();

                $em->persist($invoiceItem);
                $items[] = $invoiceItem;
            }

            $invoice = new Invoice();
            $invoice->setClientName($invoiceDto->getClientName());
            $invoice->setStatus($invoiceStatusEntity);
            $invoice->setTotalAmount($totalAmount);


            foreach ($items as $item)
            {
                $invoice->getItems()->add($item);
                $item->setInvoice($invoice);
            }

            $em->persist($invoice);
            $em->flush();

            $em->commit();

            return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
        }
        catch (Throwable $t)
        {
            if ($em->getConnection()->isTransactionActive())
            {
                $em->rollback();
            }
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, $t->getMessage());
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?Invoice $invoice, EntityManagerInterface $em): JsonResponse
    {
        try
        {
            if ($invoice === null)
            {
                throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, "Invoice not found");
            }

            //we don't have to deal with deleting InvoiceItems and payment, Doctrine will do it for us
            $em->remove($invoice);
            $em->flush();

            return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
        }
        catch (Throwable $t)
        {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, $t->getMessage());
        }
    }
}
