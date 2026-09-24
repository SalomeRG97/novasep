<?php
// ============================================================
//  NOVASEP — Intermediario Backend para Simla CRM
//  Archivo: api/crm-handler.php
//  Version: 1.0  |  Fecha: 2026-09-14
// ============================================================
//  Este archivo NUNCA debe ser accedido directamente desde
//  el navegador. Recibe peticiones desde el sitio web (JS),
//  llama a la API de Simla y devuelve el resultado en JSON.
// ============================================================

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metodo no permitido.']);
    exit;
}

// ============================================================
//  CONFIGURACION
// ============================================================
define('SIMLA_BASE_URL', 'https://novasep.simla.com/api/v5');
define('SIMLA_API_KEY', 'ctdnf24VeDoJPDJeLH1jQLW4XJpUSQMW');
define('SITE_COMERCIAL', 'nova-seguridad');
define('SITE_ETICA', 'novasep-etica');  // Tienda dedicada Linea Etica
define('LOG_FILE', __DIR__ . '/crm_errors.log');

// ============================================================
//  MAPAS DE DICCIONARIO (valor HTML => codigo interno Simla)
// ============================================================

$MAP_SERVICIO = [
    'Vigilancia Fisica' => 'vigilancia_humana',
    'Escoltas' => 'escoltas',
    'Vigilancia Aerea' => 'drones_seguridad',
    'Seguridad electronica' => 'seguridad_electronica',
    'Analisis de riesgos' => 'analisis_riesgos',
    // Con tildes tambien:
    'Vigilancia Física' => 'vigilancia_humana',
    'Vigilancia Aérea' => 'drones_seguridad',
    'Seguridad electrónica' => 'seguridad_electronica',
    'Análisis de riesgos' => 'analisis_riesgos',
];

$MAP_SECTOR = [
    'Infraestructura y construccion' => 'infraestructura_construccion',
    'Infraestructura y construcción' => 'infraestructura_construccion',
    'Industrial' => 'industrial',
    'Puertos y aeropuertos' => 'puertos_aeropuertos',
    'Energia e hidrocarburos' => 'energia_hidrocarburos',
    'Energía e hidrocarburos' => 'energia_hidrocarburos',
    'Servicios publicos' => 'servicios_publicos',
    'Servicios públicos' => 'servicios_publicos',
    'Logistica y transporte' => 'logistica_transporte',
    'Logística y transporte' => 'logistica_transporte',
    'Educativo' => 'educativo',
];

$MAP_CATEGORIA = [
    'Acceso no autorizado a información sensible.' => 'acceso_no_autorizado_info',
    'Acoso sexual.' => 'acoso_sexual',
    'Actividades sospechosas de lavado de activos o financiación del terrorismo.' => 'lavado_activos_ft',
    'Conducta inadecuada de clientes y/o usuarios.' => 'conducta_inadecuada_clientes',
    'Competencia desleal.' => 'competencia_desleal',
    'Conflicto de interés.' => 'conflicto_interes',
    'Fraude.' => 'fraude',
    'Inadecuado manejo de datos personales.' => 'manejo_inadecuado_datos',
    'Situación personal que va contravía del código de ética y buen gobierno.' => 'contravia_codigo_etica',
    'Soborno.' => 'soborno',
    'Venta engañosa.' => 'venta_enganosa',
    'Violación o vulneración de derechos humanos.' => 'vulneracion_ddhh',
];

$MAP_SUBCATEGORIA = [
    'Abuso de autoridad.' => 'abuso_autoridad',
    'Acoso laboral.' => 'acoso_laboral',
    'Coartar la libertad de expresión.' => 'coartar_libertad_expresion',
    'Corrupción.' => 'corrupcion',
    'Daño a la reputación.' => 'dano_reputacion',
    'Desigualdad.' => 'desigualdad',
    'Despido injusto o demanda laboral.' => 'despido_injusto',
    'Desplazamientos forzados.' => 'desplazamiento_forzado',
    'Discriminación.' => 'discriminacion',
    'Hacer juicios sin elementos probatorios.' => 'juicios_sin_pruebas',
    'Incumplimiento de normas legales.' => 'incumplimiento_normas_legales',
    'Incumplimiento legal de la moral, del orden publico y del bienestar general en una sociedad democratica.' => 'incumplimiento_moral_orden_publico',
    'Influenciar en la decisión de una persona.' => 'influenciar_decision',
    'Maltrato.' => 'maltrato',
    'Manejo inadecuado de la información confidencial.' => 'manejo_info_confidencial',
    'Negación de permisos y licencias otorgadas y amparadas por ley.' => 'negacion_permisos_licencias',
    'Negar el reconocimiento o acceso de los derechos jurídicos de una persona.' => 'negar_derechos_juridicos',
    'Negar información para la adquisición de la vivienda.' => 'negar_info_vivienda',
    'Negar la creación de una asociación y obligar a pertenecer a una asociación.' => 'libertad_asociacion',
    'Persecución por opinión.' => 'persecucion_opinion',
    'Plagio o apropiación de ideas.' => 'plagio_ideas',
    'Retención ilegal.' => 'retencion_ilegal',
    'Trabajo forzoso.' => 'trabajo_forzoso',
    'Trabajo infantil.' => 'trabajo_infantil',
    'Uso desproporcionado y continuo de la fuerza.' => 'uso_desproporcionado_fuerza',
    'Vulneración trabajo digno.' => 'vulneracion_trabajo_digno',
];

// ============================================================
//  FUNCION: Llamada generica a Simla API
// ============================================================
function llamar_simla(string $endpoint, array $payload): array
{
    $url = SIMLA_BASE_URL . $endpoint;
    $body = http_build_query($payload);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
            'X-API-KEY: ' . SIMLA_API_KEY,
        ],
    ]);

    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($raw === false) {
        registrar_error("cURL error [{$endpoint}]: {$curlErr}");
        return ['success' => false, 'error' => 'Error de red al conectar con el CRM.', 'http_code' => 0];
    }

    $data = json_decode($raw, true) ?? [];
    $data['http_code'] = $httpCode;

    if (!($data['success'] ?? false)) {
        registrar_error("API error [{$endpoint}] HTTP {$httpCode}: " . ($data['errorMsg'] ?? $raw));
    }

    return $data;
}

// ============================================================
//  FUNCION: Registrar errores en log
// ============================================================
function registrar_error(string $mensaje): void
{
    $linea = date('[Y-m-d H:i:s] ') . $mensaje . PHP_EOL;
    file_put_contents(LOG_FILE, $linea, FILE_APPEND | LOCK_EX);
}

// ============================================================
//  FUNCION: Mapear valor del formulario a codigo del CRM
// ============================================================
function mapear(string $valor, array $mapa): string
{
    return $mapa[$valor] ?? strtolower(preg_replace('/[^a-z0-9_]/i', '_', trim($valor)));
}

// ============================================================
//  FORMULARIO 1: COTIZACION COMERCIAL
//  POST /customers/create — Entidad: Cliente
// ============================================================
function procesar_cotizacion(array $post, array $mapServicio, array $mapSector): array
{
    $nombre = trim($post['nombre'] ?? '');
    $empresa = trim($post['empresa'] ?? '');
    $cargo = trim($post['cargo'] ?? '');
    $ciudad = trim($post['ciudad'] ?? '');
    $telefono = trim($post['telefono'] ?? '');
    $email = trim($post['email'] ?? '');
    $servicio = trim($post['servicio'] ?? '');
    $sector = trim($post['sector'] ?? '');
    $mensaje = trim($post['mensaje'] ?? '');
    $origen = trim($post['origen_pagina'] ?? '/contacto');

    if (!$nombre || !$empresa || !$ciudad || !$telefono || !$email || !$servicio) {
        return ['success' => false, 'error' => 'Faltan campos obligatorios en el formulario de cotizacion.'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'El correo electronico no es valido.'];
    }

    $customer = [
        'firstName' => $nombre,
        'email' => $email,
        'phones' => [['number' => $telefono]],
        'customFields' => [
            'nombre_completo' => $nombre,
            'empresa' => $empresa,
            'cargo' => $cargo,
            'ciudad' => $ciudad,
            'mensaje' => $mensaje,
            'servicio_interes' => mapear($servicio, $mapServicio),
            'origen_pagina' => $origen,
            'acepta_politicas' => 'si',
        ],
    ];

    if ($sector) {
        $customer['customFields']['sector_empresa'] = mapear($sector, $mapSector);
    }

    return llamar_simla('/customers/create', [
        'site' => SITE_COMERCIAL,
        'customer' => json_encode($customer, JSON_UNESCAPED_UNICODE),
    ]);
}

// ============================================================
//  FORMULARIO 3: LINEA ETICA
//  POST /orders/create — Entidad: Pedido
// ============================================================
function procesar_linea_etica(array $post, array $mapCategoria, array $mapSubcategoria): array
{
    $fechaSolicitud = trim($post['fecha_solicitud'] ?? '');
    $ciudadHechos = trim($post['ciudad_hechos'] ?? '');
    $categoria = trim($post['categoria_caso'] ?? '');
    $descripcion = trim($post['descripcion_hechos'] ?? '');
    $relacionUser = trim($post['relacion_usuario'] ?? '');
    $esAnonimo = (trim($post['reporte_anonimo'] ?? 'Si') === 'Si');

    if (!$fechaSolicitud || !$ciudadHechos || !$categoria || !$descripcion || !$relacionUser) {
        return ['success' => false, 'error' => 'Faltan campos obligatorios en el formulario de Linea Etica.'];
    }

    $subcategoria = trim($post['subcategoria_ddhh'] ?? '');
    $nombreImplicado = trim($post['nombre_implicado'] ?? '');
    $relacionImplicado = trim($post['relacion_implicado'] ?? '');
    $dependencia = trim($post['dependencia_implicado'] ?? '');

    $customFields = [
        'fecha_solicitud' => $fechaSolicitud,
        'ciudad_hechos' => $ciudadHechos,
        'categoria_caso' => mapear($categoria, $mapCategoria),
        'descripcion_hechos' => $descripcion,
        'relacion_usuario' => $relacionUser,
        'reporte_anonimo' => $esAnonimo ? 'si' : 'no',
    ];

    if ($subcategoria) {
        $customFields['subcategoria_ddhh'] = mapear($subcategoria, $mapSubcategoria);
    }
    if ($nombreImplicado)
        $customFields['nombre_implicado'] = $nombreImplicado;
    if ($relacionImplicado)
        $customFields['relacion_implicado'] = $relacionImplicado;
    if ($dependencia)
        $customFields['dependencia_implicado'] = $dependencia;

    // ----------------------------------------------------------------
    //  CASO 1: REPORTE ANONIMO
    //  Se reutiliza siempre UN SOLO cliente fijo "Reporte Anonimo"
    //  en la tienda de etica, identificado por externalId fijo.
    //  Si no existe, se crea la primera vez; las siguientes lo reusan.
    // ----------------------------------------------------------------
    if ($esAnonimo) {
        $extIdAnonimo = 'reporte-anonimo-etica';

        // Intentar crear el cliente anonimo fijo
        $respAnon = llamar_simla('/customers/create', [
            'site' => SITE_ETICA,
            'customer' => json_encode([
                'firstName' => 'Reporte Anonimo',
                'externalId' => $extIdAnonimo,
            ], JSON_UNESCAPED_UNICODE),
        ]);

        if ($respAnon['success'] ?? false) {
            // Se creo por primera vez
            $customerData = ['id' => $respAnon['id']];
        } else {
            // Ya existe: buscarlo por externalId para obtener su ID
            $busq = llamar_simla('/customers', [
                'site' => SITE_ETICA,
                'externalId' => $extIdAnonimo,
            ]);
            $idAnon = $busq['customers'][0]['id'] ?? null;
            $customerData = $idAnon ? ['id' => $idAnon] : ['firstName' => 'Reporte Anonimo'];
        }

        // ----------------------------------------------------------------
        //  CASO 2: REPORTE IDENTIFICADO
        //  Se crea el cliente real explicitamente en SITE_ETICA primero,
        //  luego el pedido se enlaza a ese cliente por su ID.
        // ----------------------------------------------------------------
    } else {
        $nombreCompleto = trim($post['nombre_completo'] ?? '');
        $telefonoCont = trim($post['telefono_contacto'] ?? '');
        $emailUsuario = trim($post['email_usuario'] ?? '');
        $documento = trim($post['documento_identidad'] ?? '');

        if (!$emailUsuario || !filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'El correo del denunciante no es valido.'];
        }

        $nuevoCliente = [
            'firstName' => $nombreCompleto ?: 'Denunciante',
            'email' => $emailUsuario,
        ];
        if ($telefonoCont) {
            $nuevoCliente['phones'] = [['number' => $telefonoCont]];
        }

        // Crear cliente en la tienda de etica
        $respCliente = llamar_simla('/customers/create', [
            'site' => SITE_ETICA,
            'customer' => json_encode($nuevoCliente, JSON_UNESCAPED_UNICODE),
        ]);

        if ($respCliente['success'] ?? false) {
            // Cliente nuevo creado exitosamente
            $customerData = ['id' => $respCliente['id']];
        } else {
            // Ya existe con ese email: buscarlo para obtener su ID
            $busqueda = llamar_simla('/customers', [
                'site' => SITE_ETICA,
                'email' => $emailUsuario,
            ]);
            $idExistente = $busqueda['customers'][0]['id'] ?? null;
            $customerData = $idExistente ? ['id' => $idExistente] : $nuevoCliente;
        }

        if ($documento) {
            $customFields['documento_identidad'] = $documento;
        }
    }

    $externalId = 'LE-' . date('Ymd') . '-' . substr(uniqid(), -6);

    // Paso 2: Crear el pedido enlazado al cliente (por ID si esta identificado,
    // o con datos inline si es anonimo)
    $order = [
        'externalId' => $externalId,
        'customer' => $customerData,
        'customFields' => $customFields,
    ];

    return llamar_simla('/orders/create', [
        'site' => SITE_ETICA,
        'order' => json_encode($order, JSON_UNESCAPED_UNICODE),
    ]);
}

// ============================================================
//  ENRUTADOR PRINCIPAL
// ============================================================
$tipo = trim($_POST['form_type'] ?? '');

switch ($tipo) {
    case 'cotizacion':
        $resultado = procesar_cotizacion($_POST, $MAP_SERVICIO, $MAP_SECTOR);
        break;

    case 'etica':
        $resultado = procesar_linea_etica($_POST, $MAP_CATEGORIA, $MAP_SUBCATEGORIA);
        break;

    default:
        $resultado = ['success' => false, 'error' => 'Tipo de formulario no reconocido.'];
}

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
exit;

/*
============================================================
  INSTRUCCIONES ANTES DE SUBIR A PRODUCCION
============================================================

1. COMPLETAR SITE_ETICA:
   - Ingresar al panel Simla con usuario Admin
   - Ir a: Administracion > Tiendas
   - Copiar el "Codigo simbolico" de la tienda de Linea Etica
   - Reemplazar 'PENDIENTE' en la constante SITE_ETICA

2. PARA PRUEBAS (opcional):
   - Cambiar SITE_COMERCIAL y SITE_ETICA a 'novasep_test'
   - Probar los formularios
   - Verificar registros en el CRM
   - Eliminar registros de prueba
   - Restaurar codigos de produccion antes de publicar

3. SEGURIDAD — IMPORTANTE:
   - Este archivo NO debe ser accesible directamente por URL
   - Agregar al .htaccess de la carpeta /api:
       Options -Indexes
       <FilesMatch "\.php$">
         Order Deny,Allow
         Deny from all
         Allow from env=HTTP_REFERER
       </FilesMatch>
   - El archivo crm_errors.log se creara automaticamente en /api/
     Revisar periodicamente si hay errores

============================================================
*/
