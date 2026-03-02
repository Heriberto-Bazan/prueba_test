# Generador de Facturas 

Sistema para crear ya dministrar facturas y genera el PDF para descargar 

#Tecnologias 
Backend:Symfony 6 (PHP 8.2) + PostgreSQL
Frontend:Nuxt 3 (Vue 3) + Tailwind CSS
PDF:wkhtmltopdf con KnpSnappyBundle
Infraestructura:Docker (4 contenedores)

#Implementacion de proyecto 
git clone
cd Pruebas_Test
docker compose up -d

Se inicializan los contenedores

# Url de poryectos
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000/api/invoices

##Regla de negocio

- El calculo de impuestos y descuentos depende del tipo de factura:

- Servicio: el descuento se aplica primero, despues se calcula el impuesto sobre el monto ya descontado.
- Producto: el impuesto se calcula sobre el precio base, y el descuento se aplica al final sobre el monto con impuesto.

- No se permite mezclar productos y servicios en la misma factura.

#Tests

docker compose exec php vendor/bin/phpunit

## Docker

El proyecto usa 4 contenedores:

- invoice_db - PostgreSQL 15
- invoice_php - PHP-FPM 8.2
- invoice_nginx - Nginx
- invoice_front - Node 20 (Nuxt)
