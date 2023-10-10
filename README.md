# Guía de Uso de la API de Billage  :eyes:
Información detallada sobre cómo utilizar la API de Billage (API V2.1) 

## Introducción :floppy_disk:
La API de Billage es una interfaz de programación de aplicaciones que te permite acceder a tus datos de Billage y realizar operaciones como la consulta de facturas, la creación de nuevos gastos y la gestión de clientes. Para utilizar la API, necesitas una clave API válida que puedes obtener desde la plataforma de Billage.

## Base URL :electric_plug:
La URL base para todas las solicitudes a la API de Billage es:
**https://app.getbillage.com/api**

## Autenticación :computer:
Para autenticarte en la API de Billage, debes incluir tu clave API en las cabeceras de todas tus solicitudes HTTP 

### Obtener Todas las Facturas de Gastos (Compras)  :minidisc:

Se realiza una 1er llamada a **GET https://app.getbillage.com/api/v2/spendings** para obtener todos los ID's de las facturas y asi poder obtner los detalles de cada factura desde
**GET https://app.getbillage.com/api/v2/spendings**

Opcionalmente se puede pasar los siguientes parametros por el Body en la primer llamada

### Parámetros Opcionales  :keyboard:

Puedes utilizar varios parámetros opcionales para refinar tu búsqueda, incluyendo:

- `q`: Filtrar por código, fecha u otros detalles.
- `start`: Posición de inicio para la consulta.
- `elements`: Número de elementos a recuperar por página.
- `ref`: Código de referencia.
- `serie`: Serie de la factura.
- `account`: Cuenta relacionada con la factura.
- `colour`: Nombre del color asociado a la factura.
- `date-from`: Fecha de inicio (en formato yyyy-MM-dd).
- `date-to`: Fecha de fin (en formato yyyy-MM-dd).
- `owner`: Propietario de la factura.
- `state`: Estado de la factura.
- `category`: Categoría de la factura.
- `tags`: Etiquetas asociadas a la factura.
- `summarized`: Obtener resultados resumidos (true o false).
- `sort`: Campos por los cuales ordenar los resultados.






