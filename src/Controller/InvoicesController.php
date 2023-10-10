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

        // -------------------------------------------Autorización -----------------------------------------------------//
        $client = new Client();
        $response = $client->request('GET', 'https://app.getbillage.com/api/v2/invoices', [
            'headers' => [
                'Authorization' => $authorizationKey,
            ],
        ]);
        $invoices = json_decode($response->getBody()->getContents(), true);

        // Crear un arreglo para almacenar los detalles de las facturas
        $detalleFacturas = [];

        foreach ($invoices['page'] as $invoice) {
            // Realizar una solicitud adicional para obtener detalles de la factura por ID
            $responseDetalle = $client->request('GET', 'https://app.getbillage.com/api/v2/invoices/' . $invoice['id'], [
                'headers' => [
                    'Authorization' => $authorizationKey,
                ],
            ]);

            $detalleFactura = json_decode($responseDetalle->getBody()->getContents(), true);
        
          //---------------------------------------------- ACCESO A ESCTRUCTURAS Y CAMPOS ------------------------------------//
            //  "retention"
            $retention = $detalleFactura['retention'];
            $retentionName = $retention['name'];
            $retentionValue = $retention['value'];
            //     "paymentDocument"
            $paymentDocument = $detalleFactura['payment_document'];
            $paymentName = $paymentDocument['name'];           
            //  "customer"
            $customer = $detalleFactura['customer'];
            $customerName = $customer['business_name'];
            $customerNif = $customer['vat'];

            // ASIGNACION DE VARIABLES //
            $factura = [
                'ID de Factura' => $detalleFactura['id'],
                'Fecha' => $detalleFactura['date'],
                'Estado' => $detalleFactura['state'],
                'Tipo de Factura' => $detalleFactura['type'],
                'Detalle Cliente' => [
                    'Razon Social' => $customerName,
                    'NIF' => $customerNif,
                ],
                'Total Base Imponible' => $detalleFactura['base'],
                'Total Impuestos' => $detalleFactura['taxes'],
                'Total Factura' => $detalleFactura['total'],
                'Total Retenciones' => $detalleFactura['retention_amount'],
                'Detalle Retención' => [
                    'Tipo retencion' => $retentionName,
                    'Porcentaje' => $retentionValue,
                ],
                'Forma de Pago' => [
                    'Metodo de Pago' => $paymentName,
                    
                ],

            ];
        // ------------------------------------- PRODUCTOS ------------------------------------------------//
        $products = [];
        foreach ($detalleFactura['concepts'] as $product) {
            $productDetails = [
                'Descripción' => $product['description'],
                'Cantidad' => $product['quantity'],
                'Precio' => $product['price'],
                'Descuento' => $product['discount'],
                'Total' => $product['total'],
                'Impuesto' => [
                    'Nombre' => $product['tax']['name'],
                    'Porcentaje' => $product['tax']['value'],
                    'Etiqueta' => $product['tax']['label'],
                    'Recargo' => $product['tax']['surcharge'],
                ],
            ];
            $products[] = $productDetails;
        }
        $factura['Productos'] = $products;
        // Agregar la factura al arreglo de respuesta
        $detalleFacturas[] = $factura;
    }
    // Crear la respuesta final
    $respuesta = [
        'CANTIDAD TOTAL DE FACTURAS' => $invoices['total_elements'],
        'FACTURAS RECIBIDAS EN EL PERIODO' => $detalleFacturas,
    ];
    return new JsonResponse($respuesta);
}
}