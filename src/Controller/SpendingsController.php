<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpendingsController extends AbstractController
{
    #[Route('/spendings', name: 'app_spendings')]
    public function getspendings()
    {
        $authorizationKey = 'lfoiEtlbkZk5tgOIN2xGEe3xsJdvk71QBT1FwSsSCvBPIYX9b5E5ttcFZBTK13LeTjARSNCtSALydGtNN7Vr8bPhsgzSIqjZasCKpHXq9wu686sZrnk5li4hnJrI9bFr';

        // ------------------------------------------- Autorización -----------------------------------------------------//

        $client = new Client();
        $response = $client->request('GET', 'https://app.getbillage.com/api/v2/spendings', [
            'headers' => [
                'Authorization' => $authorizationKey,
            ],
        ]);
        $spendings = json_decode($response->getBody()->getContents(), true);

        // Crear un arreglo para almacenar los detalles de las facturas
        $detalleFacturas = [];

        foreach ($spendings['page'] as $spending) {
            // Realizar una solicitud adicional para obtener detalles de la factura por ID
            $responseDetalle = $client->request('GET', 'https://app.getbillage.com/api/v2/spendings/' . $spending['id'], [
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
            //  "provider"
            $provider = $detalleFactura['provider'];
            $provider_name = $provider['business_name'];
            $nif = $provider['vat'];

            // ASIGNACION DE VARIABLES //
            $factura = [
                'ID de Factura' => $detalleFactura['id'],
                'Fecha' => $detalleFactura['date'],
                'Estado' => $detalleFactura['state'],
                'Tipo de Factura' => $detalleFactura['type'],
                'Detalle Proveedor' => [
                    'Razon Social' => $provider_name,
                    'NIF' => $nif,
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
            'CANTIDAD TOTAL DE FACTURAS' => $spendings['total_elements'],
            'FACTURAS RECIBIDAS EN EL PERIODO' => $detalleFacturas,
        ];
        return new JsonResponse($respuesta);
    }
}
