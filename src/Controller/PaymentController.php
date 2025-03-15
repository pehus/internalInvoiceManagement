<?php

namespace App\Controller;

use App\Dto\InvoiceDto;
use App\Dto\PaymentDto;
use App\Entity\Invoice;
use App\Entity\Payment;
use App\Entity\PaymentMethod;
use App\Service\SerializerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/payment', name: 'api_payment_')]
final class PaymentController extends AbstractController
{
    #[Route('/add/{invoiceId}', methods: ['POST'])]
    public function addPayment(
        int $invoiceId,
        Request $request,
        EntityManagerInterface $em,
        SerializerService $serializerService
    ): JsonResponse
    {
        try
        {
            $content = $request->getContent();

            if ($content === null)
            {
                throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, "Content cannot be null.");
            }

            $invoice = $em->getRepository(Invoice::class)->find($invoiceId);
            if ($invoice === null)
            {
                throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, "Invoice not found.");
            }

            /** @var PaymentDto $paymentDto */
            $paymentDto = $serializerService->getSerializer()->deserialize($content, PaymentDto::class,'json');

            if ($paymentDto->getPaymentMethodId() === null)
            {
                throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, "Missing paymentMethodId.");
            }
            $paymentMethod = $em->getRepository(PaymentMethod::class)->find($paymentDto->getPaymentMethodId());

            if ($paymentMethod === null)
            {
                throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, "Payment method not found.");
            }

            $payment = new Payment();
            $payment->setPaymentMethod($paymentMethod);
            $payment->setInvoice($invoice);
            $payment->setAmount($paymentDto->getAmount());
            $payment->setDate(new DateTime());

            $em->persist($payment);
            $em->flush();

            return new JsonResponse(['message' => 'OK'], Response::HTTP_OK);
        }
        catch(Exception $e)
        {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}