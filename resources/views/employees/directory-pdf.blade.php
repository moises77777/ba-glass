<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Directorio de Equipos Asignados</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 16px; color: #0055A4; margin-bottom: 5px; }
        .header p { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #0055A4; color: white; padding: 8px 6px; text-align: left; font-size: 10px; font-weight: bold; border: 1px solid #004494; }
        td { padding: 6px; border: 1px solid #ddd; font-size: 10px; }
        tr:nth-child(even) { background-color: #f5f5f5; }
        .text-center { text-align: center; }
        .footer { margin-top: 15px; text-align: right; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Directorio de Equipos Asignados - BA Glass Mexico</h1>
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Empleado</th>
                <th>Nombre</th>
                <th>Departamento</th>
                <th>Puesto</th>
                <th>Equipo</th>
                <th>Codigo</th>
                <th>Marca / Modelo</th>
                <th>Fecha Asignacion</th>
            </tr>
        </thead>
        <tbody>
            @php $hasData = false; @endphp
            @foreach($employees as $employee)
                @php
                    $activeAssignments = $employee->assignments->where('status', 'active');
                @endphp
                @if($activeAssignments->count() > 0)
                    @php $hasData = true; @endphp
                    @foreach($activeAssignments as $index => $assignment)
                    <tr>
                        <td>{{ $employee->employee_number ?? '-' }}</td>
                        <td><strong>{{ $employee->full_name }}</strong></td>
                        <td>{{ $employee->department->name ?? '-' }}</td>
                        <td>{{ $employee->position->name ?? '-' }}</td>
                        <td>{{ $assignment->equipment->category->name ?? '-' }}</td>
                        <td>{{ $assignment->equipment->internal_code ?? '-' }}</td>
                        <td>{{ $assignment->equipment->brand->name ?? '-' }} {{ $assignment->equipment->model ?? '' }}</td>
                        <td>{{ $assignment->assignment_date ? $assignment->assignment_date->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @endforeach
                @endif
            @endforeach
            @if(!$hasData)
            <tr>
                <td colspan="8" class="text-center">No hay empleados con equipos asignados.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Sistema de Control de Equipos - BA Glass Mexico
    </div>
</body>
</html>
