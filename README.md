# FormFlow Server

Backend para el proyecto FormFlow - Un sistema de gestión de formularios con Laravel.

## Requisitos

- Docker
- Docker Compose

## Configuración

1. Clonar el repositorio:
```bash
git clone <repository-url>
cd fullstack-project-backend
```

2. Copiar el archivo de variables de entorno:
```bash
cp .env.example .env
```

3. Ejecutar el script de despliegue:
```bash
chmod +x deploy.sh
./deploy.sh
```

## API Endpoints

### Enviar formulario
```http
POST /api/submissions
Content-Type: application/json

{
    "nombre": "string, required",
    "email": "string, email válido, required",
    "mensaje": "string, mínimo 10 caracteres, required",
    "tipo_comprobante": "enum ['Factura', 'CFF', 'Ticket'], required",
    "metodo_pago": "enum ['Efectivo', 'Transferencia', 'Tarjeta'], required",
    "comprobante_pago": "file, required si metodo_pago es 'Transferencia'"
}
```

Para enviar archivos (cuando metodo_pago es 'Transferencia'):
```http
POST /api/submissions
Content-Type: multipart/form-data

nombre: string
email: string
mensaje: string
tipo_comprobante: string
metodo_pago: "Transferencia"
comprobante_pago: File (jpg, png, pdf, max 2MB)
```

### Obtener todos los registros
```http
GET /api/submissions
Accept: application/json

Response:
{
    "data": [
        {
            "id": number,
            "nombre": string,
            "email": string,
            "mensaje": string,
            "tipo_comprobante": string,
            "metodo_pago": string,
            "comprobante_pago": string|null,
            "created_at": datetime,
            "updated_at": datetime
        }
    ]
}
```

## Estructura del Proyecto

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── FormSubmissionController.php    # Manejo de formularios
│   │   ├── Requests/
│   │   │   └── StoreFormSubmissionRequest.php  # Validación de formularios
│   ├── Models/
│   │   └── FormSubmission.php                  # Modelo de formularios
├── database/
│   └── migrations/
│       └── *_create_form_submissions_table.php # Estructura de la tabla
├── routes/
│   └── api.php                                # Rutas de la API
├── storage/
│   └── app/
│       └── public/
│           └── comprobantes/                   # Archivos subidos
├── docker-compose.yml                         # Configuración Docker
├── Dockerfile                                 # Configuración de la imagen
└── deploy.sh                                  # Script de despliegue
```

## Desarrollo

El proyecto está dockerizado y usa:
- PHP 8.2
- Laravel 12
- MySQL 8.0
- Nginx

### Pruebas de API
Se incluye una colección de Postman (`FormFlow.postman_collection.json`) con todos los endpoints configurados para pruebas.

### Validaciones
- El campo `comprobante_pago` solo es requerido cuando `metodo_pago` es "Transferencia"
- Los archivos permitidos son: jpg, png, pdf
- Tamaño máximo de archivo: 2MB
- El mensaje debe tener al menos 10 caracteres
- El email debe ser válido

## Base de Datos

La tabla `form_submissions` tiene la siguiente estructura:
- `id`: bigint, auto-incremental
- `nombre`: string, required
- `email`: string, required
- `mensaje`: text, required
- `tipo_comprobante`: enum ['Factura', 'CFF', 'Ticket'], required
- `metodo_pago`: enum ['Efectivo', 'Transferencia', 'Tarjeta'], required
- `comprobante_pago`: string, nullable
- `created_at`: timestamp
- `updated_at`: timestamp

## Licencia

MIT