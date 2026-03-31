# reset_db.ps1
# Ejecuta en PowerShell desde la carpeta del proyecto (donde está docker-compose.yml)
# Objetivo: recrear la base de datos biblioteca_db y volver a crear tablas.

# 1) Levantar servicios (si no están levantados)
docker compose up -d --build

# 2) Terminar conexiones a biblioteca_db (si existen)
docker compose exec -T postgres psql -U postgres -c "SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = 'biblioteca_db' AND pid <> pg_backend_pid();"

# 3) Eliminar base de datos (sin transacción)
docker compose exec -T postgres psql -U postgres --single-transaction=off -c "DROP DATABASE IF EXISTS biblioteca_db;"

# 4) Crear base de datos nueva
docker compose exec -T postgres psql -U postgres -c "CREATE DATABASE biblioteca_db;"

# 5) Crear tablas en nueva DB (usa el SQL en src/init_db.sql)
docker compose exec -T postgres psql -U postgres -d biblioteca_db < src/init_db.sql

# 6) Verificar estado (opcional)
docker compose exec -T postgres psql -U postgres -d biblioteca_db -c "\dt"
Write-Host "✅ Base de datos recreada exitosamente" -ForegroundColor Green
