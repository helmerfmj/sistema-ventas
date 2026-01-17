#!/bin/bash
#================================================================
# setup-server.sh - Initial Server Setup Script
#================================================================

set -e

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

log_info() { echo -e "${BLUE}[INFO]${NC} $1"; }
log_success() { echo -e "${GREEN}[SUCCESS]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

ENVIRONMENT=${1:-production}
DEPLOY_PATH=${2}

if [ -z "$DEPLOY_PATH" ]; then
    if [ "$ENVIRONMENT" == "production" ]; then
        DEPLOY_PATH="/var/www/sistema-ventas-production"
    else
        DEPLOY_PATH="/var/www/sistema-ventas-staging"
    fi
fi

log_info "Initializing server for environment: $ENVIRONMENT"
log_info "Path: $DEPLOY_PATH"

# Create base directories
sudo mkdir -p "$DEPLOY_PATH/releases"
sudo mkdir -p "$DEPLOY_PATH/shared/storage/app/public"
sudo mkdir -p "$DEPLOY_PATH/shared/storage/framework/cache/data"
sudo mkdir -p "$DEPLOY_PATH/shared/storage/framework/sessions"
sudo mkdir -p "$DEPLOY_PATH/shared/storage/framework/views"
sudo mkdir -p "$DEPLOY_PATH/shared/storage/logs"
sudo mkdir -p "$DEPLOY_PATH/shared/database/backups"

# Create initial .env if not exists
if [ ! -f "$DEPLOY_PATH/shared/.env" ]; then
    log_info "Creating template .env file..."
    sudo touch "$DEPLOY_PATH/shared/.env"
    log_success "Template .env created at $DEPLOY_PATH/shared/.env"
    log_info "IMPORTANT: Please edit this file with production values."
else
    log_success ".env already exists."
fi

# Set permissions
log_info "Setting permissions..."
sudo chown -R $USER:www-data "$DEPLOY_PATH"
sudo chmod 755 "$DEPLOY_PATH"  # Ensure the runner user can enter the directory
sudo chmod -R 775 "$DEPLOY_PATH/shared/storage"

log_success "Server setup complete!"
echo "--------------------------------------------------------"
echo "Next steps:"
echo "1. Edit $DEPLOY_PATH/shared/.env with production config"
echo "2. Ensure GitHub Secret PROD_PATH is: $DEPLOY_PATH"
echo "3. Run deployment via GitHub Actions"
echo "--------------------------------------------------------"
