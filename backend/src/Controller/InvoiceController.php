<?php

namespace App\Controller;

use App\Dto\InvoiceItemDto;
use App\Dto\InvoiceRequestDto;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Exception\InvoiceValidationException;
use App\Service\InvoiceCalculator;
use App\Service\PdfGenerator;
use App\Validator\InvoiceValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class InvoiceController extends AbstractController
{
    public function __construct(
        private InvoiceCalculator $calculator,
        private InvoiceValidator $invoiceValidator,
        private PdfGenerator $pdfGenerator,
        private EntityManagerInterface $em,
        private ValidatorInterface $validator
    ) {}

    #[Route('/invoices', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return $this->json(['error' => 'JSON invalido'], 400);
            }

            $dto = $this->mapToDto($data);
            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                return $this->json([
                    'error' => 'Errores de validacion',
                    'details' => $this->formatErrors($errors)
                ], 422);
            }

            $this->invoiceValidator->validate($dto);
            $calculated = $this->calculator->calculateInvoice($dto);

            $invoice = $this->saveInvoice($dto, $calculated);
            return $this->json([
                'message' => 'Factura creada exitosamente',
                'invoice' => $this->serializeInvoice($invoice, $calculated)
            ], 201);

        } catch (InvoiceValidationException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return $this->json(['error' => 'El numero de factura ya existe'], 409);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    #[Route('/invoices/{id}/pdf', methods: ['GET'])]
    public function downloadPdf(int $id): Response
    {
        try {
            $invoice = $this->em->getRepository(Invoice::class)->find($id);

            if (!$invoice) {
                return $this->json(['error' => 'Factura no encontrada'], 404);
            }

            $invoiceData = $this->buildPdfData($invoice);
            $pdfContent = $this->pdfGenerator->generate($invoiceData);

            return new Response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $invoice->getInvoiceNumber() . '.pdf"',
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Error al generar el PDF'], 500);
        }
    }

    #[Route('/invoices/calculate', methods: ['POST'])]
    public function calculate(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return $this->json(['error' => 'JSON invalido'], 400);
            }

            $dto = $this->mapToDto($data);

            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                return $this->json([
                    'error' => 'Errores de validacion',
                    'details' => $this->formatErrors($errors)
                ], 422);
            }

            $this->invoiceValidator->validate($dto);

            $calculated = $this->calculator->calculateInvoice($dto);

            return $this->json($calculated);

        } catch (InvoiceValidationException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    #[Route('/invoices', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $invoices = $this->em->getRepository(Invoice::class)->findAll();

        $result = array_map(function (Invoice $invoice) {
            return [
                'id' => $invoice->getId(),
                'invoiceNumber' => $invoice->getInvoiceNumber(),
                'fromName' => $invoice->getFromName(),
                'toName' => $invoice->getToName(),
                'itemType' => $invoice->getItemType(),
                'grandTotal' => $invoice->getGrandTotal(),
                'currency' => $invoice->getCurrency(),
                'issueDate' => $invoice->getIssueDate()->format('Y-m-d'),
                'createdAt' => $invoice->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $invoices);

        return $this->json($result);
    }

    private function mapToDto(array $data): InvoiceRequestDto
    {
        $dto = new InvoiceRequestDto();
        $dto->invoiceNumber = $data['invoiceNumber'] ?? '';
        $dto->issueDate = $data['issueDate'] ?? '';
        $dto->dueDate = $data['dueDate'] ?? null;
        $dto->fromName = $data['fromName'] ?? '';
        $dto->fromEmail = $data['fromEmail'] ?? null;
        $dto->fromAddress = $data['fromAddress'] ?? null;
        $dto->toName = $data['toName'] ?? '';
        $dto->toEmail = $data['toEmail'] ?? null;
        $dto->toAddress = $data['toAddress'] ?? null;
        $dto->itemType = $data['itemType'] ?? 'servicio';
        $dto->currency = $data['currency'] ?? 'MXN';
        $dto->notes = $data['notes'] ?? null;

        foreach ($data['items'] ?? [] as $itemData) {
            $itemDto = new InvoiceItemDto();
            $itemDto->description = $itemData['description'] ?? '';
            $itemDto->quantity = $itemData['quantity'] ?? 1;
            $itemDto->price = $itemData['price'] ?? 0;
            $itemDto->taxRate = $itemData['taxRate'] ?? 0;
            $itemDto->discount = $itemData['discount'] ?? 0;
            $dto->items[] = $itemDto;
        }

        return $dto;
    }

    private function saveInvoice(InvoiceRequestDto $dto, array $calculated): Invoice
    {
        $invoice = new Invoice();
        $invoice->setInvoiceNumber($dto->invoiceNumber);
        $invoice->setIssueDate(new \DateTime($dto->issueDate));
        $invoice->setDueDate($dto->dueDate ? new \DateTime($dto->dueDate) : null);
        $invoice->setFromName($dto->fromName);
        $invoice->setFromEmail($dto->fromEmail);
        $invoice->setFromAddress($dto->fromAddress);
        $invoice->setToName($dto->toName);
        $invoice->setToEmail($dto->toEmail);
        $invoice->setToAddress($dto->toAddress);
        $invoice->setItemType($dto->itemType);
        $invoice->setCurrency($dto->currency);
        $invoice->setNotes($dto->notes);
        $invoice->setSubtotal((string) $calculated['subtotal']);
        $invoice->setTotalTax((string) $calculated['totalTax']);
        $invoice->setTotalDiscount((string) $calculated['totalDiscount']);
        $invoice->setGrandTotal((string) $calculated['grandTotal']);

        foreach ($calculated['items'] as $itemData) {
            $item = new InvoiceItem();
            $item->setDescription($itemData['description']);
            $item->setQuantity($itemData['quantity']);
            $item->setPrice((string) $itemData['price']);
            $item->setTaxRate((string) $itemData['taxRate']);
            $item->setDiscount((string) $itemData['discount']);
            $item->setSubtotal((string) $itemData['subtotal']);
            $item->setTaxAmount((string) $itemData['taxAmount']);
            $item->setDiscountAmount((string) $itemData['discountAmount']);
            $item->setTotal((string) $itemData['total']);
            $invoice->addItem($item);
        }

        $this->em->persist($invoice);
        $this->em->flush();

        return $invoice;
    }

    private function serializeInvoice(Invoice $invoice, array $calculated): array
    {
        return [
            'id' => $invoice->getId(),
            'invoiceNumber' => $invoice->getInvoiceNumber(),
            'issueDate' => $invoice->getIssueDate()->format('Y-m-d'),
            'dueDate' => $invoice->getDueDate()?->format('Y-m-d'),
            'fromName' => $invoice->getFromName(),
            'fromEmail' => $invoice->getFromEmail(),
            'fromAddress' => $invoice->getFromAddress(),
            'toName' => $invoice->getToName(),
            'toEmail' => $invoice->getToEmail(),
            'toAddress' => $invoice->getToAddress(),
            'itemType' => $invoice->getItemType(),
            'currency' => $invoice->getCurrency(),
            'notes' => $invoice->getNotes(),
            'subtotal' => $calculated['subtotal'],
            'totalTax' => $calculated['totalTax'],
            'totalDiscount' => $calculated['totalDiscount'],
            'grandTotal' => $calculated['grandTotal'],
            'items' => $calculated['items'],
        ];
    }

    private function buildPdfData(Invoice $invoice): array
    {
        $items = [];
        foreach ($invoice->getItems() as $item) {
            $items[] = [
                'description' => $item->getDescription(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'taxRate' => $item->getTaxRate(),
                'discount' => $item->getDiscount(),
                'subtotal' => $item->getSubtotal(),
                'taxAmount' => $item->getTaxAmount(),
                'discountAmount' => $item->getDiscountAmount(),
                'total' => $item->getTotal(),
            ];
        }

        return [
            'invoiceNumber' => $invoice->getInvoiceNumber(),
            'issueDate' => $invoice->getIssueDate()->format('Y-m-d'),
            'dueDate' => $invoice->getDueDate()?->format('Y-m-d'),
            'fromName' => $invoice->getFromName(),
            'fromEmail' => $invoice->getFromEmail(),
            'fromAddress' => $invoice->getFromAddress(),
            'toName' => $invoice->getToName(),
            'toEmail' => $invoice->getToEmail(),
            'toAddress' => $invoice->getToAddress(),
            'itemType' => $invoice->getItemType(),
            'currency' => $invoice->getCurrency(),
            'notes' => $invoice->getNotes(),
            'subtotal' => $invoice->getSubtotal(),
            'totalTax' => $invoice->getTotalTax(),
            'totalDiscount' => $invoice->getTotalDiscount(),
            'grandTotal' => $invoice->getGrandTotal(),
            'items' => $items,
        ];
    }

    private function formatErrors($errors): array
    {
        $formatted = [];
        foreach ($errors as $error) {
            $formatted[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }
        return $formatted;
    }
}
