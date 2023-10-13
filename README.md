# Guía de Uso de la API de Billage  :eyes:
Información detallada sobre cómo utilizar la API de Billage (API V2.1) <br>

## Introducción :floppy_disk:
La API de Billage es una interfaz de programación de aplicaciones que te permite acceder a tus datos de Billage y realizar operaciones como la consulta de facturas, la creación de nuevos gastos<br>
y la gestión de clientes. Para utilizar la API, necesitas una clave API válida que puedes obtener desde la plataforma de Billage.<br>

## Base URL :electric_plug:
La URL base para todas las solicitudes a la API de Billage es:<br>
**https://app.getbillage.com/api**

## Autenticación :computer:
Para autenticarte en la API de Billage, debes incluir tu clave API en las cabeceras de todas tus solicitudes HTTP, la misma se obtiene desde la aplicacion (consultar la guia) <br>

### Obtener Todas las Facturas   :minidisc:

Se realiza una 1º llamada a <br>
Para las FACTURAS RECIBIDAS **GET https://app.getbillage.com/api/v2/spendings** <br>
Para las FACTURAS EMITIDAS **GET https://app.getbillage.com/api/v2/invoices** <br>
para poder obtener todos los ID's de las facturas  <br>

Luego se realiza otra llamada a la cual se les pasa todos los ID's obtenidos de la llamada anterior y asignar  los detalles de cada factura<br>

Opcionalmente se puede pasar los siguientes parametros por el Body en la primer llamada<br>
### Parámetros Opcionales  :keyboard:

Puedes utilizar varios parámetros opcionales para refinar tu búsqueda, incluyendo:<br>

- `q`: Filtrar por código, fecha u otros detalles.<br>
- `start`: Posición de inicio para la consulta.<br>
- `elements`: Número de elementos a recuperar por página.<br>
- `ref`: Código de referencia.<br>
- `serie`: Serie de la factura.<br>
- `account`: Cuenta relacionada con la factura.<br>
- `colour`: Nombre del color asociado a la factura.<br>
- `date-from`: Fecha de inicio (en formato yyyy-MM-dd).<br>
- `date-to`: Fecha de fin (en formato yyyy-MM-dd).<br>
- `owner`: Propietario de la factura.<br>
- `state`: Estado de la factura.<br>
- `category`: Categoría de la factura.<br>
- `tags`: Etiquetas asociadas a la factura.<br>
- `summarized`: Obtener resultados resumidos (true o false).<br>
- `sort`: Campos por los cuales ordenar los resultados.<br>


### Link a la documentacion de la [API](https://app.getbillage.com/api/documentation.html)




