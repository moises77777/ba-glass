<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Carta Responsiva</title>
<style>
* { margin: 0; padding: 0; }
body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 8pt;
    color: #000;
    background: #fff;
    margin: 18pt 22pt 18pt 22pt;
}

/* ─── HEADER TABLE ─── */
table.header {
    width: 100%;
    border-collapse: collapse;
    border: 1.5pt solid #000;
    margin-bottom: 6pt;
}
table.header td {
    border: 1pt solid #000;
    vertical-align: middle;
    padding: 0;
}
td.logo-cell {
    width: 72pt;
    text-align: center;
    padding: 4pt;
    background: #fff;
}
td.logo-inner {
    width: 52pt;
    height: 52pt;
    background: #c8d400;
    text-align: center;
    vertical-align: middle;
    font-size: 22pt;
    font-weight: bold;
    color: #fff;
    border-radius: 26pt;
}
td.title-cell {
    text-align: center;
    font-size: 11pt;
    font-weight: bold;
    padding: 0 8pt;
    letter-spacing: 0.3pt;
}
td.folio-outer {
    width: 75pt;
    padding: 0;
    vertical-align: top;
}
table.folio-table {
    width: 100%;
    border-collapse: collapse;
}
table.folio-table td {
    border-bottom: 1pt solid #000;
    padding: 3pt 5pt;
    font-size: 7.5pt;
    white-space: nowrap;
}
table.folio-table tr:last-child td {
    border-bottom: none;
}

/* ─── SECTION HEADER (gray bar) ─── */
table.section-hdr {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5pt;
}
table.section-hdr td {
    background: #bfbfbf;
    border: 1pt solid #000;
    text-align: center;
    font-size: 8pt;
    font-weight: bold;
    padding: 2pt 4pt;
}

/* ─── DATOS DEL USUARIO ─── */
table.usuario {
    width: 100%;
    border-collapse: collapse;
    border: 1pt solid #000;
}
table.usuario td {
    border: 1pt solid #000;
    padding: 2.5pt 4pt;
    font-size: 8pt;
    vertical-align: middle;
}
td.lbl {
    font-weight: bold;
    width: 105pt;
    white-space: nowrap;
}

/* ─── DATOS COMPUTADORA ─── */
table.computadora {
    width: 100%;
    border-collapse: collapse;
    border: 1pt solid #000;
    margin-top: 5pt;
}
table.computadora td {
    border: 1pt solid #000;
    padding: 3pt 4pt;
    font-size: 8pt;
    vertical-align: middle;
    white-space: nowrap;
}
td.comp-lbl { font-weight: bold; }
td.comp-val { min-width: 55pt; }

/* ─── SOFTWARE / ACCESORIOS ─── */
table.list-box {
    width: 100%;
    border-collapse: collapse;
    border: 1pt solid #000;
    margin-top: 0;
}
table.list-box td {
    border: 1pt solid #000;
    padding: 3pt 5pt;
    font-size: 8pt;
    vertical-align: top;
    line-height: 1.35;
}
.sw-subtitle {
    font-weight: bold;
    border-top: 1pt solid #000;
    margin-top: 2pt;
    padding-top: 2pt;
}

/* ─── DISCLAIMER ─── */
p.disclaimer {
    text-align: center;
    font-size: 8pt;
    color: #1155cc;
    font-style: italic;
    margin: 18pt 40pt 10pt 40pt;
    line-height: 1.5;
}

/* ─── FIRMAS ─── */
table.firmas {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6pt;
}
table.firmas td {
    width: 50%;
    text-align: center;
    padding: 0 25pt;
    vertical-align: bottom;
}
div.firma-linea {
    border-top: 1pt solid #000;
    padding-top: 3pt;
    margin-top: 42pt;
}
div.firma-titulo {
    font-weight: bold;
    font-size: 8pt;
}
div.firma-sub {
    font-size: 7pt;
    color: #222;
    margin-top: 1pt;
}

/* ─── FOLIO BOTTOM ─── */
p.folio-bottom {
    text-align: right;
    font-size: 7pt;
    margin-top: 4pt;
}

/* ─── PÁGINA 2 ─── */
p.pol-title {
    font-size: 9pt;
    font-weight: bold;
    text-align: center;
    margin: 8pt 0 10pt 0;
}
p.pol-body {
    font-size: 7.5pt;
    text-align: justify;
    line-height: 1.5;
    margin-bottom: 6pt;
}
ul.pol-list {
    font-size: 7.5pt;
    line-height: 1.5;
    margin-left: 18pt;
    margin-bottom: 6pt;
}
ul.pol-list li {
    margin-bottom: 3pt;
}
table.prohibidos {
    width: 100%;
    border-collapse: collapse;
    border: 1pt solid #000;
    margin-bottom: 6pt;
}
table.prohibidos td {
    border: 1pt solid #000;
    padding: 4pt 6pt;
    font-size: 7.5pt;
    vertical-align: top;
    width: 50%;
    line-height: 1.45;
}
ol.alpha-list {
    margin-left: 14pt;
    padding: 0;
}
ol.alpha-list li {
    margin-bottom: 3pt;
}
</style>
</head>
<body>

@php
    $eq   = $assignment->equipment;
    $emp  = $assignment->employee;
    $loc  = $assignment->location;

    /* ── Software principal (base fija + dinámica del equipo) ── */
    $swPrincipal = [
        'Microsoft Office 365',
        'CrowdStrike Windows Sensor',
        'Microsoft OneDrive',
        'Microsoft Teams Meeting',
        'TeamViewer',
        'SAP GUI for Windows 7.70',
        'FortiClient VPN',
    ];
    if ($eq->operating_system) {
        $swPrincipal[] = trim($eq->operating_system . ($eq->os_version ? ' ' . $eq->os_version : ''));
    }
    if ($eq->description) {
        foreach (explode("\n", $eq->description) as $l) {
            if (trim($l)) $swPrincipal[] = trim($l);
        }
    }

    /* ── Software adicional (base fija + dinámica del equipo) ── */
    $swAdicional = [
        'Adobe Acrobat',
        'Google Chrome',
        '7 zip',
        'Cisco Secure Client',
    ];
    if ($eq->observations) {
        foreach (explode("\n", $eq->observations) as $l) {
            if (trim($l)) $swAdicional[] = trim($l);
        }
    }

    $accList = [];
    if ($eq->has_keyboard) {
        $accList[] = 'Teclado' . ($eq->charger_details ? ' ' . $eq->charger_details : '');
    }
    if ($eq->has_mouse) {
        $accList[] = 'Mouse' . ($eq->mouse_details ? ' ' . $eq->mouse_details : '');
    }
    if ($eq->has_charger) {
        $accList[] = 'Cargador' . ($eq->charger_details ? ' ' . $eq->charger_details : '');
    }
    if ($eq->has_power_strip) $accList[] = 'Multicontacto';
    if ($eq->has_bag_case)    $accList[] = 'Funda / Mochila';
    if ($eq->adapters)        $accList[] = $eq->adapters;
    if ($eq->other_accessories) $accList[] = $eq->other_accessories;
    if ($eq->accessories) {
        foreach (explode("\n", $eq->accessories) as $a) {
            if (trim($a)) $accList[] = trim($a);
        }
    }

    $fecha = strtoupper(\Carbon\Carbon::now()->locale('es')->isoFormat('D [DE] MMMM [DEL] YYYY'));
@endphp

{{-- ══════════════ PÁGINA 1 ══════════════ --}}
<table class="header">
    <tr>
        <td class="logo-cell">
            <table style="margin:0 auto; border-collapse:collapse;">
                <tr>
                    <td class="logo-inner">BA</td>
                </tr>
            </table>
        </td>
        <td class="title-cell">CARTA RESPONSIVA DE EQUIPO Y PROGRAMAS DE COMPUTO</td>
        <td class="folio-outer">
            <table class="folio-table">
                <tr><td>Nivel: V</td></tr>
                <tr><td>Hoja 1</td></tr>
                <tr><td>De: 2</td></tr>
            </table>
        </td>
    </tr>
</table>

{{-- DATOS DEL USUARIO --}}
<table class="section-hdr"><tr><td>DATOS DEL USUARIO</td></tr></table>
<table class="usuario">
    <tr>
        <td class="lbl">FECHA:</td>
        <td>{{ $fecha }}</td>
    </tr>
    <tr>
        <td class="lbl">NOMBRE:</td>
        <td>{{ strtoupper($emp->first_name ?? '') }}</td>
    </tr>
    <tr>
        <td class="lbl">APELLIDOS</td>
        <td>{{ strtoupper($emp->last_name ?? '') }}</td>
    </tr>
    <tr>
        <td class="lbl">NUMERO EMPLEADO:</td>
        <td>{{ $emp->employee_number ?? '' }}</td>
    </tr>
    <tr>
        <td class="lbl">DEPARTAMENTO:</td>
        <td>{{ strtoupper($emp->department->name ?? '') }}</td>
    </tr>
    <tr>
        <td class="lbl">LOCALIDAD</td>
        <td>{{ strtoupper($loc->name ?? '') }}</td>
    </tr>
</table>

{{-- DATOS DE LA COMPUTADORA ASIGNADA --}}
<table class="section-hdr" style="margin-top:8pt;"><tr><td>DATOS DE LA COMPUTADORA ASIGNADA</td></tr></table>
<table class="computadora">
    <tr>
        <td class="comp-lbl">TIPO:</td>
        <td class="comp-val">{{ strtoupper($eq->category->name ?? '') }}</td>
        <td class="comp-lbl">MARCA:</td>
        <td class="comp-val">{{ strtoupper($eq->brand->name ?? '') }}</td>
        <td class="comp-lbl">MODELO</td>
        <td class="comp-val">{{ strtoupper($eq->model ?? '') }}</td>
        <td class="comp-lbl">SERIE:</td>
        <td class="comp-val">{{ strtoupper($eq->serial_number ?? '') }}</td>
    </tr>
</table>

{{-- DATOS DEL SOFTWARE --}}
<table class="section-hdr" style="margin-top:8pt;"><tr><td>DATOS DEL SOFTWARE</td></tr></table>
<table class="list-box">
    <tr>
        <td>
            @foreach($swPrincipal as $sw)
                <div>{{ $sw }}</div>
            @endforeach
            @if(count($swAdicional))
                <div class="sw-subtitle">SOFTWARE ADICIONAL</div>
                @foreach($swAdicional as $sw)
                    <div>{{ $sw }}</div>
                @endforeach
            @endif
            @if(!count($swPrincipal) && !count($swAdicional))
                <div>&nbsp;</div>
            @endif
        </td>
    </tr>
</table>

{{-- EQUIPO ADICIONAL --}}
<table class="section-hdr" style="margin-top:8pt;"><tr><td>EQUIPO ADICIONAL</td></tr></table>
<table class="list-box">
    <tr>
        <td style="padding-left: 15pt;">
            @forelse($accList as $acc)
                <div>{{ $acc }}</div>
            @empty
                <div>&nbsp;</div><div>&nbsp;</div>
            @endforelse
            <div>&nbsp;</div>
        </td>
    </tr>
</table>

<p class="disclaimer">
    La presente carta responsiva sustituye cualquier otro documento expedido con fecha anterior a esta y en el cual se haga<br>
    referencia al mismo equipo de cómputo
</p>

<table class="firmas">
    <tr>
        <td>
            <div class="firma-linea">
                <div class="firma-titulo">NOMBRE DEL USUARIO</div>
                <div class="firma-sub">Firma del usuario que recibe el equipo y programas de computo</div>
            </div>
        </td>
        <td>
            <div class="firma-linea">
                <div class="firma-titulo">NOMBRE Y FIRMA DE QUIEN ENTREGA EL EQUIPO</div>
                <div class="firma-sub">Departamento de Sistemas</div>
            </div>
        </td>
    </tr>
</table>

<p class="folio-bottom">FO-23-05-01/01</p>

{{-- ══════════════ SALTO DE PÁGINA ══════════════ --}}
<div style="page-break-before: always;"></div>

{{-- ══════════════ PÁGINA 2 ══════════════ --}}
<table class="header">
    <tr>
        <td class="logo-cell">
            <table style="margin:0 auto; border-collapse:collapse;">
                <tr>
                    <td class="logo-inner">BA</td>
                </tr>
            </table>
        </td>
        <td class="title-cell">CARTA RESPONSIVA DE EQUIPO Y PROGRAMAS DE COMPUTO</td>
        <td class="folio-outer">
            <table class="folio-table">
                <tr><td>Nivel: V</td></tr>
                <tr><td>Hoja 2</td></tr>
                <tr><td>De: 2</td></tr>
            </table>
        </td>
    </tr>
</table>

<p class="pol-title">Política de uso de programas de cómputo (software) por parte del personal de BA GLASS MEXICO</p>

<p class="pol-body">En BA GLASS MEXICO, S.A. DE C.V. acatamos al pie de la letra todas las normas jurídicas que nos son aplicables, los compromisos asumidos en contratos que celebramos y las disposiciones de nuestros reglamentos y procedimientos internos, incluyendo nuestro Código de Conducta Empresarial.</p>

<p class="pol-body">Por lo que se refiere al uso de los programas de cómputo, respetamos los derechos de autor de los titulares y las condiciones que marcan las licencias correspondientes para el uso de tales programas de cómputo.</p>

<p class="pol-body">En este sentido, todas las personas que laboran en BA GLASS MEXICO o nos prestan algún servicio, están obligadas a abstenerse de utilizar cualquier tipo de programas de cómputo sin contar con la licencia respectiva. Específicamente para el uso de programas de cómputo se deberá llevar a cabo de conformidad con los lineamientos siguientes:</p>

<ul class="pol-list">
    <li>Todos los programas de cómputo deberán contar con la licencia respectiva.</li>
    <li>Todo usuario de una computadora se compromete, a través de la firma de la correspondiente carta responsiva y la presente política, a saber, que programas de cómputo tiene instalados y abstenerse de instalar programas de cómputo adicionales a los autorizados.</li>
    <li>En cualquier caso, si el usuario instala algún programa a la computadora que tiene asignada, deberá contar con la licencia respectiva.</li>
    <li>En Ningún caso podrá utilizarse las computadoras asignadas y los programas de cómputo instalados en las mismas para actividades ilícitas o contrarias a los compromisos contractuales de BA GLASS MEXICO, nuestros procedimientos y políticas o nuestro código de Código de Conducta Empresarial, por lo que está prohibido guardar en ella algún material de los mencionados a continuación:</li>
</ul>

<table class="prohibidos">
    <tr>
        <td>
            <ol class="alpha-list" type="a">
                <li>Material reñido con la ley, la moral y las buenas costumbres.</li>
                <li>Chistes, caricaturas, imágenes que denoten implícita o explícita discriminación o falta de respeto para algún grupo minoritario por motivos de condición social, condición física, raza, religión, sexo, etc.</li>
                <li>Cadenas de cualquier índole.</li>
                <li>Asuntos políticos.</li>
                <li>Material pornográfico en todas sus modalidades (imágenes, textos, etc.).</li>
            </ol>
        </td>
        <td>
            <ol class="alpha-list" type="a" start="6">
                <li>Cualquier acto u omisión que incite a la violencia en cualquiera de sus modalidades.</li>
                <li>Tarjetas electrónicas de felicitación no institucionales.</li>
                <li>Copiar música para uso personal (incluye copias).</li>
                <li>Entretenimiento.</li>
                <li>El colaborador se compromete a no utilizar ni transmitir contenido o información difamatoria, discriminatoria, racista, calumniosa o amenazante o en cualquier forma ofensiva a terceros.</li>
            </ol>
        </td>
    </tr>
</table>

<p class="pol-body">El incumplimiento de estas políticas podrá dar lugar a acciones civiles o penales en contra del infractor, así como a la rescisión de su relación con BA GLASS MEXICO.</p>

<p class="pol-body">Declaro conocer y entender la política de uso de programas de cómputo (software) de BA GLASS MEXICO y me comprometo a cumplirla.</p>

<p class="disclaimer" style="margin-top:22pt;">
    La presente carta responsiva sustituye cualquier otro documento expedido con fecha anterior a esta y en el cual se haga<br>
    referencia al mismo equipo de cómputo
</p>

<table class="firmas">
    <tr>
        <td>
            <div class="firma-linea">
                <div class="firma-titulo">NOMBRE DEL USUARIO</div>
                <div class="firma-sub">Firma del usuario que recibe el equipo y programas de</div>
            </div>
        </td>
        <td>
            <div class="firma-linea">
                <div class="firma-titulo">NOMBRE Y FIRMA DE QUIEN ENTREGA EL EQUIPO</div>
                <div class="firma-sub">Departamento de Sistemas</div>
            </div>
        </td>
    </tr>
</table>

<p class="folio-bottom">FO-23-05-01/01</p>

</body>
</html>
