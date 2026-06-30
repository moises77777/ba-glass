# Guia de Formularios del Sistema de Inventario

## 1. Login (Inicio de Sesion)
**Archivo:** `resources/views/auth/login.blade.php`

Campos:
- **Correo electronico**: Usa `@baglass.com` (ej. `admin@baglass.com`)
- **Contrasena**: Minimo 8 caracteres
- **Recordarme**: Mantiene la sesion abierta por mas tiempo

Flujo: El usuario escribe sus credenciales -> Laravel valida contra la tabla `users` -> Si es correcto, redirige al Dashboard.

---

## 2. Equipos (Registrar / Editar)
**Archivos:**
- Crear: `resources/views/equipment/create.blade.php`
- Editar: `resources/views/equipment/edit.blade.php`

Campos principales:
- **Codigo interno** (obligatorio): Identificador unico del equipo. Ejemplo: `EQ-0001-0001`
- **Etiqueta de activo**: Codigo de activo fijo de la empresa (opcional)
- **Categoria** (obligatorio): Tipo de equipo (Laptops, PCs, Servidores, etc.)
- **Marca / Modelo**: Fabricante y modelo del equipo
- **Numero de serie**: Obligatorio solo si la categoria lo requiere (ej. laptops si, mouse no)
- **Especificaciones tecnicas**: Procesador, RAM, Almacenamiento, Tarjeta grafica
- **Red**: MAC, IP, Hostname
- **Proveedor**: Quien vendio el equipo
- **Orden de compra / Factura**: Documentacion de compra
- **Fechas de garantia**: Inicio y fin de la garantia
- **Condicion fisica** (obligatorio): Excelente, Buena, Regular, Mala, Danada, Para reparacion
- **Estado operativo** (obligatorio): Operativo, No operativo, En reparacion, Obsoleto, Pendiente de configuracion
- **Ubicacion**: Donde esta fisicamente el equipo
- **Descripcion / Observaciones / Accesorios**: Notas adicionales
- **Imagenes**: Fotos del equipo (hasta 5MB cada una)

Flujo: El usuario llena los campos -> `EquipmentController@store` valida -> Guarda en tabla `equipment` -> Registra en historial -> Redirige a la ficha del equipo.

---

## 3. Empleados (Registrar / Editar)
**Archivos:**
- Crear: `resources/views/employees/create.blade.php`
- Editar: `resources/views/employees/edit.blade.php`

Campos principales:
- **Numero de empleado** (obligatorio): Codigo interno del empleado
- **Nombre completo** (obligatorio): Nombres y apellidos
- **Correo electronico** (obligatorio): `@baglass.com`
- **Telefono**: Extension o numero de contacto
- **Departamento** (obligatorio): Area de la empresa (RH, IT, Ventas, etc.)
- **Puesto** (obligatorio): Cargo del empleado
- **Fecha de contratacion**: Cuando entro a la empresa
- **Estado** (obligatorio): Activo, Inactivo, Licencia, Baja
- **Direccion**: Domicilio del empleado

Flujo: Llena campos -> `EmployeeController@store` valida -> Guarda en `employees` -> Redirige a la ficha del empleado.

---

## 4. Asignaciones (Resguardos)
**Archivo:** `resources/views/assignments/create.blade.php`

Campos principales:
- **Empleado** (obligatorio): A quien se le asigna el equipo
- **Equipo** (obligatorio): Que equipo se entrega (solo muestra equipos disponibles)
- **Ubicacion de entrega**: Donde se entrego fisicamente
- **Fecha de asignacion** (obligatorio): Cuando se entrego
- **Fecha prevista de devolucion**: Cuando deberia regresar (opcional)
- **Condicion al asignar** (obligatorio): Estado fisico del equipo al momento de entregarlo
- **Notas**: Observaciones sobre la entrega
- **Generar responsiva**: Si se marca, genera el PDF de la carta responsiva

Flujo: Selecciona empleado y equipo -> `AssignmentController@store` valida -> Crea registro en `assignments` -> Cambia estado del equipo a "asignado" -> Envia correo al admin -> Genera PDF si se solicito.

---

## 5. Devoluciones
**Archivo:** `resources/views/assignments/return.blade.php`

Campos principales:
- **Fecha de devolucion** (obligatorio): Cuando regreso el equipo
- **Condicion de devolucion** (obligatorio): Estado fisico al recibirlo
- **Observaciones**: Notas sobre la devolucion
- **Accesorios entregados**: Lo que venia con el equipo

Flujo: Se abre desde la ficha de la asignacion -> `AssignmentController@return` valida -> Actualiza la asignacion con fecha de devolucion -> Cambia estado del equipo a "disponible" -> Registra en historial.

---

## 6. Mantenimiento
**Archivo:** `resources/views/maintenance/create.blade.php`

Campos principales:
- **Equipo** (obligatorio): Equipo que necesita mantenimiento
- **Tipo** (obligatorio): Preventivo, Correctivo, Instalacion, Configuracion, Otro
- **Prioridad** (obligatorio): Baja, Media, Alta, Critica
- **Descripcion** (obligatorio): Que problema tiene o que se va a hacer
- **Proveedor de servicio**: Quien lo va a reparar (si aplica)
- **Costo estimado / Real**: Gastos del mantenimiento
- **Fecha programada**: Cuando se va a realizar
- **Asignado a**: Tecnico responsable

Flujo: Llena campos -> `MaintenanceController@store` valida -> Crea registro en `maintenance_records` -> Envia correo al admin -> Redirige al listado.

---

## 7. Reportes (Filtros)
**Archivos:** `resources/views/reports/*.blade.php`

Estos no son formularios de guardado, sino de filtrado:
- **Fechas**: Rango de fechas para consultar
- **Categorias**: Filtrar por tipo de equipo
- **Departamentos**: Filtrar por area
- **Estado**: Disponible, Asignado, En reparacion, etc.
- **Exportar**: Genera PDF o Excel con los resultados

Flujo: Selecciona filtros -> `ReportController` consulta la base de datos -> Muestra resultados -> Opcionalmente descarga PDF/Excel.

---

## Estructura general de un formulario en Laravel

```
Vista (.blade.php) -> POST/PUT -> Controlador -> Validacion -> Modelo -> Base de datos
```

1. **Vista**: El HTML con los campos (`<input>`, `<select>`, `<textarea>`)
2. **Ruta**: En `routes/web.php` se define que URL va a que metodo del controlador
3. **Controlador**: Recibe los datos, los valida y decide que hacer
4. **Validacion**: `$request->validate([...])` verifica que los datos sean correctos
5. **Modelo**: Representa la tabla de la base de datos (`Equipment::create([...])`)
6. **Base de datos**: MySQL guarda la informacion permanentemente

---

## Comandos utiles para ejecutar los tests

```bash
# Ejecutar todos los tests
C:\xampp\php\php.exe vendor\phpunit\phpunit\phpunit tests/Feature

# Ejecutar un test especifico
C:\xampp\php\php.exe vendor\phpunit\phpunit\phpunit tests/Feature/LoginTest.php
```

Los tests verifican que las paginas cargan correctamente y que usuarios autenticados pueden acceder a las secciones permitidas.
