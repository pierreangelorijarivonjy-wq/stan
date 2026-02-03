# EduPass-MG Makefile
# Simplify Docker and deployment commands

.PHONY: help build up down restart logs shell test deploy-staging deploy-prod clean

# Default target
.DEFAULT_GOAL := help

# Colors for output
BLUE := \033[0;34m
GREEN := \033[0;32m
YELLOW := \033[0;33m
RED := \033[0;31m
NC := \033[0m # No Color

help: ## Show this help message
	@echo "$(BLUE)EduPass-MG - Available Commands$(NC)"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "$(GREEN)%-20s$(NC) %s\n", $$1, $$2}'

# Docker Commands
build: ## Build Docker image
	@echo "$(BLUE)Building Docker image...$(NC)"
	docker build -t edupass-mg:latest -f docker/Dockerfile .

up: ## Start all containers
	@echo "$(GREEN)Starting containers...$(NC)"
	docker-compose -f docker-compose.prod.yml up -d

down: ## Stop all containers
	@echo "$(YELLOW)Stopping containers...$(NC)"
	docker-compose -f docker-compose.prod.yml down

restart: down up ## Restart all containers

logs: ## Show container logs
	docker-compose -f docker-compose.prod.yml logs -f

logs-app: ## Show app container logs only
	docker-compose -f docker-compose.prod.yml logs -f app

logs-db: ## Show database logs only
	docker-compose -f docker-compose.prod.yml logs -f postgres

logs-redis: ## Show Redis logs only
	docker-compose -f docker-compose.prod.yml logs -f redis

# Application Commands
shell: ## Open shell in app container
	docker-compose -f docker-compose.prod.yml exec app /bin/bash

artisan: ## Run artisan command (usage: make artisan CMD="migrate")
	docker-compose -f docker-compose.prod.yml exec app php artisan $(CMD)

migrate: ## Run database migrations
	@echo "$(BLUE)Running migrations...$(NC)"
	docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

migrate-fresh: ## Fresh migration with seed
	@echo "$(RED)WARNING: This will drop all tables!$(NC)"
	docker-compose -f docker-compose.prod.yml exec app php artisan migrate:fresh --seed --force

seed: ## Run database seeders
	docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force

cache-clear: ## Clear all caches
	@echo "$(BLUE)Clearing caches...$(NC)"
	docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
	docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
	docker-compose -f docker-compose.prod.yml exec app php artisan route:clear
	docker-compose -f docker-compose.prod.yml exec app php artisan view:clear

cache-optimize: ## Optimize caches for production
	@echo "$(GREEN)Optimizing caches...$(NC)"
	docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
	docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
	docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

queue-restart: ## Restart queue workers
	docker-compose -f docker-compose.prod.yml exec app php artisan queue:restart

# Testing
test: ## Run PHPUnit tests
	@echo "$(BLUE)Running tests...$(NC)"
	docker-compose -f docker-compose.prod.yml exec app php artisan test

test-coverage: ## Run tests with coverage
	docker-compose -f docker-compose.prod.yml exec app php artisan test --coverage

# Deployment
deploy-staging: ## Deploy to staging
	@echo "$(YELLOW)Deploying to staging...$(NC)"
	chmod +x deployment/deploy.sh
	./deployment/deploy.sh staging

deploy-prod: ## Deploy to production
	@echo "$(RED)Deploying to production...$(NC)"
	chmod +x deployment/deploy.sh
	./deployment/deploy.sh production

# Health & Status
health: ## Check application health
	@curl -s http://localhost:8080/health | jq '.'

status: ## Show container status
	docker-compose -f docker-compose.prod.yml ps

# Maintenance
clean: ## Clean up Docker resources
	@echo "$(YELLOW)Cleaning up...$(NC)"
	docker-compose -f docker-compose.prod.yml down -v
	docker system prune -f

clean-all: ## Clean everything including images
	@echo "$(RED)Cleaning everything...$(NC)"
	docker-compose -f docker-compose.prod.yml down -v --rmi all
	docker system prune -af

backup-db: ## Backup database
	@echo "$(BLUE)Backing up database...$(NC)"
	docker-compose -f docker-compose.prod.yml exec postgres pg_dump -U edupass_user edupass_prod > backup_$(shell date +%Y%m%d_%H%M%S).sql

restore-db: ## Restore database (usage: make restore-db FILE=backup.sql)
	@echo "$(YELLOW)Restoring database from $(FILE)...$(NC)"
	docker-compose -f docker-compose.prod.yml exec -T postgres psql -U edupass_user edupass_prod < $(FILE)

# Development
dev-setup: ## Setup development environment
	cp .env.example .env
	composer install
	npm install
	php artisan key:generate
	php artisan migrate
	php artisan db:seed

dev-watch: ## Watch and compile assets
	npm run dev

dev-build: ## Build assets for production
	npm run build
