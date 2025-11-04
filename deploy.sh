#!/bin/bash

# Script de Deploy - JAMEES
# Copia arquivos da aplicação para o servidor via SCP
# Parte pública (public/) vai para /home/bxfcgip2/www
# Parte privada (resto) vai para /home/bxfcgip2/app_jamees

# Configurações
SERVER="bxfcgip2@jamees.com"
PUBLIC_PATH="/home/bxfcgip2/www"
APP_PATH="/home/bxfcgip2/app_jamees"
LOCAL_PATH="."

# Cores para output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Deploy JAMEES${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    echo -e "${RED}Erro: Execute este script na raiz do projeto Laravel${NC}"
    exit 1
fi

# Perguntar confirmação antes de fazer deploy
echo -e "${YELLOW}Estrutura do deploy:${NC}"
echo "  Parte pública (public/) → $PUBLIC_PATH"
echo "  Parte privada (resto) → $APP_PATH"
echo ""
read -p "Deseja fazer o deploy? (s/N): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Ss]$ ]]; then
    echo -e "${YELLOW}Deploy cancelado.${NC}"
    exit 0
fi

echo -e "${YELLOW}Iniciando deploy via SCP...${NC}"
echo ""

# Criar diretório temporário para arquivos
TEMP_DIR=$(mktemp -d)
echo "Criando pacotes temporários em: $TEMP_DIR"
echo ""

# ============================================
# PARTE 1: Criar pacote da parte pública (conteúdo de public/)
# ============================================
echo -e "${YELLOW}[1/2] Preparando parte pública (conteúdo de public/)...${NC}"

tar --exclude='.DS_Store' \
    --exclude='*.md' \
    -czf "$TEMP_DIR/public.tar.gz" \
    -C "$LOCAL_PATH/public" .

if [ $? -ne 0 ]; then
    echo -e "${RED}Erro ao criar pacote da parte pública!${NC}"
    rm -rf "$TEMP_DIR"
    exit 1
fi

echo -e "${GREEN}Pacote público criado: $(du -h "$TEMP_DIR/public.tar.gz" | cut -f1)${NC}"
echo ""

# ============================================
# PARTE 2: Criar pacote da parte privada (resto)
# ============================================
echo -e "${YELLOW}[2/2] Preparando parte privada (aplicação)...${NC}"

tar --exclude='.git' \
    --exclude='.gitignore' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.env' \
    --exclude='.env.backup' \
    --exclude='.env.*' \
    --exclude='storage/logs/*.log' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='storage/app/public/*' \
    --exclude='.idea' \
    --exclude='.vscode' \
    --exclude='.DS_Store' \
    --exclude='*.md' \
    --exclude='deploy.sh' \
    --exclude='deploy-commands.sh' \
    --exclude='tests' \
    --exclude='phpunit.xml' \
    --exclude='.phpunit.result.cache' \
    --exclude='package-lock.json' \
    --exclude='composer.lock' \
    --exclude='public' \
    -czf "$TEMP_DIR/app.tar.gz" \
    -C "$LOCAL_PATH" .

if [ $? -ne 0 ]; then
    echo -e "${RED}Erro ao criar pacote da aplicação!${NC}"
    rm -rf "$TEMP_DIR"
    exit 1
fi

echo -e "${GREEN}Pacote privado criado: $(du -h "$TEMP_DIR/app.tar.gz" | cut -f1)${NC}"
echo ""

# ============================================
# PARTE 3: Enviar parte pública para /home/bxfcgip2/www
# ============================================
echo -e "${YELLOW}[1/2] Enviando parte pública para $PUBLIC_PATH...${NC}"
scp "$TEMP_DIR/public.tar.gz" "$SERVER:$PUBLIC_PATH/"

if [ $? -ne 0 ]; then
    echo -e "${RED}Erro ao enviar parte pública via SCP!${NC}"
    echo -e "${YELLOW}Verifique se a senha está correta: 1Ti04fv3vW${NC}"
    rm -rf "$TEMP_DIR"
    exit 1
fi

echo -e "${GREEN}Parte pública enviada com sucesso!${NC}"
echo ""

# Descompactar parte pública no servidor (arquivos diretamente em www, sem pasta public)
echo -e "${YELLOW}Descompactando parte pública no servidor...${NC}"
ssh "$SERVER" "cd $PUBLIC_PATH && tar -xzf public.tar.gz && rm -f public.tar.gz"

if [ $? -ne 0 ]; then
    echo -e "${RED}Erro ao descompactar parte pública no servidor!${NC}"
    rm -rf "$TEMP_DIR"
    exit 1
fi

echo -e "${GREEN}Parte pública descompactada em $PUBLIC_PATH!${NC}"
echo ""

# ============================================
# PARTE 4: Enviar parte privada para /home/bxfcgip2/app_jamees
# ============================================
echo -e "${YELLOW}[2/2] Enviando parte privada para $APP_PATH...${NC}"
scp "$TEMP_DIR/app.tar.gz" "$SERVER:$APP_PATH/"

if [ $? -ne 0 ]; then
    echo -e "${RED}Erro ao enviar parte privada via SCP!${NC}"
    echo -e "${YELLOW}Verifique se a senha está correta: 1Ti04fv3vW${NC}"
    rm -rf "$TEMP_DIR"
    exit 1
fi

echo -e "${GREEN}Parte privada enviada com sucesso!${NC}"
echo ""

# Descompactar parte privada no servidor
echo -e "${YELLOW}Descompactando parte privada no servidor...${NC}"
ssh "$SERVER" "cd $APP_PATH && tar -xzf app.tar.gz && rm -f app.tar.gz"

if [ $? -ne 0 ]; then
    echo -e "${RED}Erro ao descompactar parte privada no servidor!${NC}"
    rm -rf "$TEMP_DIR"
    exit 1
fi

echo -e "${GREEN}Parte privada descompactada!${NC}"
echo ""

# Limpar arquivos temporários locais
rm -rf "$TEMP_DIR"

# ============================================
# PARTE 5: Configurar index.php com caminhos corretos
# ============================================
echo -e "${YELLOW}Configurando index.php com caminhos corretos...${NC}"

# Criar o conteúdo do index.php atualizado
INDEX_PHP_CONTENT=$(cat <<'EOF'
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../app_jamees/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../app_jamees/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../app_jamees/bootstrap/app.php')
    ->handleRequest(Request::capture());
EOF
)

# Criar arquivo temporário com o conteúdo
TEMP_INDEX=$(mktemp)
echo "$INDEX_PHP_CONTENT" > "$TEMP_INDEX"

# Enviar e configurar o index.php no servidor
scp "$TEMP_INDEX" "$SERVER:$PUBLIC_PATH/index.php"

if [ $? -ne 0 ]; then
    echo -e "${RED}Erro ao configurar index.php!${NC}"
    rm -f "$TEMP_INDEX"
    exit 1
fi

# Limpar arquivo temporário local
rm -f "$TEMP_INDEX"

echo -e "${GREEN}index.php configurado com sucesso!${NC}"
echo ""

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Deploy concluído com sucesso!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Estrutura no servidor:${NC}"
echo "  Parte pública (arquivos): $PUBLIC_PATH"
echo "  Parte privada (aplicação): $APP_PATH"
echo ""
echo -e "${YELLOW}Próximos passos no servidor:${NC}"
echo "1. Acesse o servidor: ssh $SERVER"
echo "2. Navegue até a aplicação: cd $APP_PATH"
echo "3. Execute: composer install --no-dev --optimize-autoloader"
echo "4. Configure o .env:"
echo "   - Edite: nano $APP_PATH/.env"
echo "   - Certifique-se que APP_URL está correto"
echo "5. Execute: php artisan config:cache"
echo "6. Execute: php artisan route:cache"
echo "7. Execute: php artisan view:cache"
echo "8. Execute: php artisan migrate --force (se houver migrações)"
echo "9. Ajuste permissões:"
echo "   cd $APP_PATH"
echo "   chmod -R 775 storage bootstrap/cache"
echo "   chown -R bxfcgip2:bxfcgip2 storage bootstrap/cache"
echo "10. Configure o DocumentRoot do servidor web para: $PUBLIC_PATH"
echo ""
echo -e "${GREEN}Deploy finalizado!${NC}"
