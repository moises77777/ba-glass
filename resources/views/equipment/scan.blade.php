@extends('layouts.app')

@section('title', 'Escanear QR')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Escanear Código QR</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Escanear QR</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-qr-code-scan me-2"></i>Apunta la cámara al código QR del activo
            </div>
            <div class="card-body">
                <div id="qrReader" style="width: 100%;"></div>
                <div id="scanStatus" class="alert alert-info mt-3 mb-0 d-none"></div>
                <div class="d-grid gap-2 mt-3">
                    <button id="startScanBtn" class="btn btn-primary">
                        <i class="bi bi-camera me-1"></i>Iniciar escaneo
                    </button>
                    <button id="stopScanBtn" class="btn btn-outline-secondary d-none">
                        <i class="bi bi-stop-circle me-1"></i>Detener escaneo
                    </button>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    El escaneo abrirá automáticamente la ficha del activo. Requiere permisos de cámara.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrCode = null;
    const statusEl = document.getElementById('scanStatus');
    const startBtn = document.getElementById('startScanBtn');
    const stopBtn = document.getElementById('stopScanBtn');
    let isProcessing = false;

    function showStatus(message, type) {
        statusEl.className = 'alert alert-' + type + ' mt-3 mb-0';
        statusEl.textContent = message;
        statusEl.classList.remove('d-none');
    }

    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;

        showStatus('Código detectado: ' + decodedText, 'success');

        let targetUrl = null;
        const trimmed = decodedText.trim();

        // Si es una ruta relativa (empieza con /)
        if (trimmed.startsWith('/')) {
            targetUrl = window.location.origin + trimmed;
        }
        // Si es una URL completa del mismo dominio
        else if (trimmed.startsWith(window.location.origin)) {
            targetUrl = trimmed;
        }
        // Si es una URL completa de cualquier dominio que contenga /equipment/
        else if (trimmed.includes('/equipment/')) {
            try {
                const url = new URL(trimmed);
                targetUrl = window.location.origin + url.pathname;
            } catch (e) {
                // No es URL válida, intentar extraer la ruta
                const match = trimmed.match(/\/equipment\/[^\s]+/);
                if (match) {
                    targetUrl = window.location.origin + match[0];
                }
            }
        }
        // Si parece un código interno (formato EQ-XXXX-XXXX o similar)
        else if (/^EQ-[A-Z0-9-]+$/i.test(trimmed)) {
            targetUrl = window.location.origin + '/equipment/' + encodeURIComponent(trimmed);
        }
        // Si es solo un número (ID del equipo)
        else if (/^\d+$/.test(trimmed)) {
            targetUrl = window.location.origin + '/equipment/' + trimmed;
        }

        if (targetUrl) {
            showStatus('Redirigiendo...', 'info');
            stopScanner().finally(() => {
                window.location.href = targetUrl;
            });
        } else {
            showStatus('El código escaneado no corresponde a un activo del sistema. Intenta con otro QR.', 'warning');
            setTimeout(() => { isProcessing = false; }, 3000);
        }
    }

    function startScanner() {
        html5QrCode = new Html5Qrcode('qrReader');
        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess
        ).then(() => {
            startBtn.classList.add('d-none');
            stopBtn.classList.remove('d-none');
            showStatus('Cámara activa. Apunta al código QR.', 'info');
        }).catch((err) => {
            showStatus('No se pudo acceder a la cámara: ' + err, 'danger');
        });
    }

    function stopScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            return html5QrCode.stop().then(() => {
                html5QrCode.clear();
                startBtn.classList.remove('d-none');
                stopBtn.classList.add('d-none');
            });
        }
        return Promise.resolve();
    }

    startBtn.addEventListener('click', startScanner);
    stopBtn.addEventListener('click', function() {
        stopScanner().then(() => showStatus('Escaneo detenido.', 'secondary'));
    });

    window.addEventListener('beforeunload', stopScanner);
</script>
@endpush
