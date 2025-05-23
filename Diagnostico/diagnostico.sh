#!/bin/bash

# Base URL de tu servidor
BASE_URL="http://sage.larioja.edu.ar"

# Rutas a testear (agregá más si necesitás)
declare -a RUTAS=(
    "/"
    "/login"
    "/buscar_agente"
    "/home"
    "/usuarios"
    "/api/test"
    "/verCargosCreados/866"
)

echo "🔍 Escaneando rutas por contenido inyectado..."

for ruta in "${RUTAS[@]}"; do
    echo -n "→ Probando $ruta... "
    RESPONSE=$(curl -s "$BASE_URL$ruta")

    if echo "$RESPONSE" | grep -q "Selamat datang"; then
        echo "⚠️ INYECCIÓN DETECTADA"
    else
        echo "✅ Limpio"
    fi
done
