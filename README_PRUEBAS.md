# Pruebas de Caja Blanca - Plataforma de Películas

Este documento describe las pruebas de caja blanca implementadas para el proyecto de plataforma de películas.

## Tipos de Pruebas Implementadas

### 1. Ruta Básica (Statement Coverage)
- **Objetivo**: Ejecutar cada línea de código al menos una vez
- **Archivo**: `pruebas_caja_blanca.php`
- **Pruebas**:
  - Usuario no autenticado
  - Método HTTP incorrecto

### 2. Condición (Decision Coverage)
- **Objetivo**: Ejecutar cada rama de decisión (true/false)
- **Archivo**: `pruebas_caja_blanca.php`
- **Pruebas**:
  - Título vacío
  - Título válido

### 3. Condición Múltiple (Multiple Condition Coverage)
- **Objetivo**: Probar todas las combinaciones de condiciones
- **Archivo**: `pruebas_caja_blanca.php`
- **Pruebas**:
  - Año válido (2020)
  - Año muy bajo (1800)
  - Año muy alto (2050)
  - Sin año (null)

### 4. Ciclos/Bucles (Loop Coverage)
- **Objetivo**: Probar bucles con diferentes números de iteraciones
- **Archivo**: `pruebas_caja_blanca.php`
- **Pruebas**:
  - Lista vacía (0 iteraciones)
  - Una película (1 iteración)
  - Múltiples películas (3 iteraciones)

### 5. No Estructurados (Path Coverage)
- **Objetivo**: Probar diferentes rutas de ejecución
- **Archivo**: `pruebas_caja_blanca.php`
- **Pruebas**:
  - Crear película exitosa
  - Actualizar película inexistente
  - Eliminar película exitosa

## Archivos de Pruebas

### 1. `pruebas_caja_blanca.php`
Pruebas del backend en PHP que cubren:
- Validación de sesión de usuario
- Operaciones CRUD de películas
- Validación de datos de entrada
- Manejo de errores

### 2. `pruebas_frontend_js.html`
Pruebas del frontend en JavaScript que cubren:
- Funciones de visualización de películas
- Validación de datos en el cliente
- Manejo de errores en la interfaz
- Procesamiento de arrays y bucles

## Cómo Ejecutar las Pruebas

### Pruebas del Backend (PHP)

1. **Requisitos**:
   - Servidor web con PHP (XAMPP, WAMP, etc.)
   - Base de datos MySQL configurada

2. **Ejecución**:
   ```bash
   # Desde la línea de comandos
   php pruebas_caja_blanca.php
   
   # O desde el navegador
   http://localhost/TP9_santiIbarra_7mo2da/pruebas_caja_blanca.php
   ```

3. **Resultado esperado**:
   ```
   === PRUEBAS DE RUTA BÁSICA ===
   Test 1.1: Usuario no autenticado
   ✓ Test 1.1 PASÓ
   Test 1.2: Método HTTP incorrecto
   ✓ Test 1.2 PASÓ
   
   === PRUEBAS DE CONDICIÓN ===
   Test 2.1: Título vacío
   ✓ Test 2.1 PASÓ
   ...
   
   === RESUMEN DE PRUEBAS ===
   Total de pruebas: 17/17 completadas
   ```

### Pruebas del Frontend (JavaScript)

1. **Ejecución**:
   - Abrir el archivo `pruebas_frontend_js.html` en un navegador web
   - Las pruebas se ejecutan automáticamente al cargar la página

2. **Resultado esperado**:
   - Interfaz visual con resultados de cada prueba
   - Resumen final con estadísticas de cobertura

## Cobertura de Pruebas

### Backend (PHP)
- **Ruta básica**: 2/2 pruebas
- **Condición**: 2/2 pruebas
- **Condición múltiple**: 4/4 pruebas
- **Ciclos/Bucles**: 3/3 pruebas
- **No estructurados**: 3/3 pruebas
- **Adicionales**: 3/3 pruebas
- **Total**: 17/17 pruebas

### Frontend (JavaScript)
- **Ruta básica**: 2/2 pruebas
- **Condición**: 2/2 pruebas
- **Condición múltiple**: 1/1 pruebas
- **Ciclos/Bucles**: 3/3 pruebas
- **No estructurados**: 2/2 pruebas
- **Adicionales**: 2/2 pruebas
- **Total**: 12/12 pruebas

## Funciones Probadas

### Backend
- `createMovie()`: Creación de películas con validaciones
- `updateMovie()`: Actualización de películas existentes
- `deleteMovie()`: Eliminación de películas
- Validación de sesión de administrador
- Validación de datos de entrada (título, año, URLs)

### Frontend
- `displayMovies()`: Visualización de lista de películas
- `createMovieCard()`: Creación de tarjetas de película
- `handleMovieError()`: Manejo de errores
- `isValidUrl()`: Validación de URLs
- `getSessionRole()`: Obtención de rol de usuario

## Casos de Prueba Específicos

### Validación de Año
```php
// Casos probados:
- Año válido: 2020 ✓
- Año muy bajo: 1800 ✗ (debe fallar)
- Año muy alto: 2050 ✗ (debe fallar)
- Sin año: null ✓
```

### Validación de URLs
```php
// Casos probados:
- URL válida: "https://example.com/image.jpg" ✓
- URL inválida: "no-es-una-url" ✗ (debe fallar)
- URL vacía: "" ✓
```

### Autenticación
```php
// Casos probados:
- Usuario no autenticado ✗ (acceso denegado)
- Usuario normal ✓ (acceso limitado)
- Administrador ✓ (acceso completo)
```

## Notas Importantes

1. **Mock Objects**: Las pruebas utilizan objetos mock para simular la base de datos y evitar dependencias externas.

2. **Captura de Output**: Se utiliza `ob_start()` y `ob_get_contents()` para capturar la salida de las funciones y verificar las respuestas.

3. **Manejo de Excepciones**: Las pruebas verifican que las excepciones se lancen correctamente en casos de error.

4. **Validación JSON**: Se incluyen funciones para validar respuestas JSON del backend.

5. **Cobertura Completa**: Las pruebas cubren tanto casos exitosos como casos de error para garantizar robustez.

## Mejoras Futuras

1. **Integración con herramientas de testing**: Integrar con PHPUnit para pruebas más robustas
2. **Cobertura de código**: Agregar métricas de cobertura de código
3. **Pruebas de integración**: Agregar pruebas que involucren la base de datos real
4. **Automatización**: Configurar ejecución automática de pruebas en CI/CD

## Autor

**Santi Ibarra** - 2024 