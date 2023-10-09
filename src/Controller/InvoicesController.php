<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class InvoicesController extends AbstractController
{
    #[Route('/invoices', name: 'app_invoices')]
    public function getInvoices(Request $request)
    {
        $authorizationKey = 'lfoiEtlbkZk5tgOIN2xGEe3xsJdvk71QBT1FwSsSCvBPIYX9b5E5ttcFZBTK13LeTjARSNCtSALydGtNN7Vr8bPhsgzSIqjZasCKpHXq9wu686sZrnk5li4hnJrI9bFr';

        // Parámetros opcionales de consulta
        $queryParams = [];
        $dateFrom = $request->query->get('date-from');
        $dateTo = $request->query->get('date-to');

        if ($dateFrom) {
            $queryParams['date-from'] = $dateFrom;
        }

        if ($dateTo) {
            $queryParams['date-to'] = $dateTo;
        }

        // -------------------------------------------Obtener el token de Autorización -----------------------------------------------------//
        $client = new Client();
        $response = $client->request('GET', 'https://app.getbillage.com/api/v2/invoices', [
            'headers' => [
                'Authorization' => $authorizationKey,
            ],
            'query' => $queryParams, // Agrega los parámetros opcionales aquí        
        ]);

        $invoices = json_decode($response->getBody()->getContents(), true);

        // return $this->json($invoices);
        //}
        // --------------------------------------- Recorrer las facturas y asignar variables ---------------------------------------------------------//        
        $cantidadTotalFacturas = $invoices['total_elements'];
        // Crear un array que contendrá tanto la cantidad total de facturas como las facturas individuales
        $respuesta = [
            'CANTIDAD TOTAL DE FACTURAS' => $cantidadTotalFacturas,
            'FACTURAS EMITIDAS EN EL PERIODO' => [],
        ];

        foreach ($invoices['page'] as $invoice) {


            $factura = [
                'ID de Factura' => $invoice['id'],
                'Razon Social Cliente' => $invoice['customer_name'],
                'NIF Cliente' => $invoice['customer_vat'],
                'Tipo de Factura' => $invoice['type'],
                //'Descripcion' => $invoice['invoiceClassDescription'],
                'Fecha de Emision' => $invoice['date'],
                //'Fecha de Vencimiento' => $invoice['expirationDate'],
                'Número de Factura' => $invoice['number'],
                'Estado de la Factura' => $invoice['state'],
                //'Importe de la retención' => number_format($invoice['retentionAmount'], 2, '.', ','),
                //'Porcentaje de la retención' => number_format($invoice['retentionPercentage'], 2, '.', ','),
                'Base total imponible' => number_format($invoice['base'], 2, '.', ','),
                'Importe total de impuestos' => number_format($invoice['taxes'], 2, '.', ','),
                'Importe total de la factura ' => number_format($invoice['total'], 2, '.', ','),
                //'Importe total retenido' => number_format($invoice['totalReAmount'], 2, '.', ','),
                //'Importe total cobrado' => number_format($invoice['totalPayedAmount'], 2, '.', ','),
                //'Importe Total Pendiente de Cobro' => number_format($invoice['totalAmountPerPay'], 2, '.', ','),
                //'Importe total computable' => number_format($invoice['totalComputableAmount'], 2, '.', ','),
                //'Importe total computable de impuestos' => number_format($invoice['totalComputableAmountForVAT'], 2, '.', ','),
                //'Porcentaje computable' => number_format($invoice['computablePercentage'], 2, '.', ','),
                //'Porcentaje computable de impuestos' => number_format($invoice['computablePercentageVAT'], 2, '.', ','),
                //'Factura rectificada ' => number_format($invoice['isRectificationInvoice'], 2, '.', ','),
                //'ID de la factura rectificada' => number_format($invoice['rectifiesInvoiceId'], 2, '.', ','),
                //'DETALLES DEL EMISOR' => $issuerDetails,

            ];
            $respuesta['FACTURAS EMITIDAS EN EL PERIODO'][] = $factura;
        }
        return new JsonResponse($respuesta);
    }
}
