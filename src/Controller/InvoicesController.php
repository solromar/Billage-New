<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class InvoicesController extends AbstractController
{
    #[Route('/invoices', name: 'app_invoices')]
    public function getInvoices()
    {
        $authorizationKey = 'lfoiEtlbkZk5tgOIN2xGEe3xsJdvk71QBT1FwSsSCvBPIYX9b5E5ttcFZBTK13LeTjARSNCtSALydGtNN7Vr8bPhsgzSIqjZasCKpHXq9wu686sZrnk5li4hnJrI9bFr';

        // -------------------------------------------Obtener el token de Autorización -----------------------------------------------------//
        $client = new Client();
        $response = $client->request('GET', 'https://app.getbillage.com/api/v2/invoices', [
            'headers' => [
                'Authorization' => $authorizationKey,
            ],
        ]);
        $invoices = json_decode($response->getBody()->getContents(), true);
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
                'Fecha de Emision' => $invoice['date'],
                'Número de Factura' => $invoice['number'],
                'Estado de la Factura' => $invoice['state'],
                'Base total imponible' => number_format($invoice['base'], 2, '.', ','),
                'Importe total de impuestos' => number_format($invoice['taxes'], 2, '.', ','),
                'Importe total de la factura ' => number_format($invoice['total'], 2, '.', ','),
            ];
            $respuesta['FACTURAS EMITIDAS EN EL PERIODO'][] = $factura;
        }
        return new JsonResponse($respuesta);
    }
}
