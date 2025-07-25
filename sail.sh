#!/bin/bash

# UPMANAGER - Docker Sail Script
# Este script facilita o uso do Laravel Sail para o projeto UPMANAGER

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para exibir ajuda
show_help() {
    echo -e "${BLUE}UPMANAGER - Laravel Sail Helper${NC}"
    echo ""
    echo "Uso: ./sail.sh [comando]"
    echo ""
    echo "Comandos disponíveis:"
    echo -e "  ${GREEN}up${NC}          - Inicia os containers Docker"
    echo -e "  ${GREEN}down${NC}        - Para os containers Docker"
    echo -e "  ${GREEN}restart${NC}     - Reinicia os containers Docker"
    echo -e "  ${GREEN}logs${NC}        - Mostra logs dos containers"
    echo -e "  ${GREEN}shell${NC}       - Acessa o shell do container Laravel"
    echo -e "  ${GREEN}mysql${NC}       - Acessa o MySQL via linha de comando"
    echo -e "  ${GREEN}migrate${NC}     - Executa as migrations"
    echo -e "  ${GREEN}seed${NC}        - Executa os seeders"
    echo -e "  ${GREEN}fresh${NC}       - Recria o banco (migrate:fresh + seed)"
    echo -e "  ${GREEN}tinker${NC}      - Abre o Laravel Tinker"
    echo -e "  ${GREEN}test${NC}        - Executa os testes"
    echo -e "  ${GREEN}status${NC}      - Mostra status dos containers"
    echo -e "  ${GREEN}help${NC}        - Mostra esta ajuda"
    echo ""
    echo "Exemplos:"
    echo "  ./sail.sh up"
    echo "  ./sail.sh migrate"
    echo "  ./sail.sh shell"
}

# Verifica se o Docker está rodando
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        echo -e "${RED}Erro: Docker não está rodando!${NC}"
        exit 1
    fi
}

# Executa comando baseado no parâmetro
case "${1}" in
    "up")
        echo -e "${BLUE}Iniciando containers Docker...${NC}"
        docker-compose up -d
        echo -e "${GREEN}Containers iniciados!${NC}"
        echo -e "${YELLOW}Aplicação disponível em: http://localhost:8000${NC}"
        ;;
    "down")
        echo -e "${BLUE}Parando containers Docker...${NC}"
        docker-compose down
        echo -e "${GREEN}Containers parados!${NC}"
        ;;
    "restart")
        echo -e "${BLUE}Reiniciando containers Docker...${NC}"
        docker-compose restart
        echo -e "${GREEN}Containers reiniciados!${NC}"
        ;;
    "logs")
        docker-compose logs -f
        ;;
    "shell")
        echo -e "${BLUE}Acessando shell do container Laravel...${NC}"
        docker-compose exec laravel.test bash
        ;;
    "mysql")
        echo -e "${BLUE}Acessando MySQL...${NC}"
        docker-compose exec mysql mysql -u sail -ppassword upmanager
        ;;
    "migrate")
        echo -e "${BLUE}Executando migrations...${NC}"
        docker-compose exec laravel.test php artisan migrate
        ;;
    "seed")
        echo -e "${BLUE}Executando seeders...${NC}"
        docker-compose exec laravel.test php artisan db:seed
        ;;
    "fresh")
        echo -e "${BLUE}Recriando banco de dados...${NC}"
        docker-compose exec laravel.test php artisan migrate:fresh --seed
        echo -e "${GREEN}Banco de dados recriado!${NC}"
        ;;
    "tinker")
        echo -e "${BLUE}Abrindo Laravel Tinker...${NC}"
        docker-compose exec laravel.test php artisan tinker
        ;;
    "test")
        echo -e "${BLUE}Executando testes...${NC}"
        docker-compose exec laravel.test php artisan test
        ;;
    "status")
        echo -e "${BLUE}Status dos containers:${NC}"
        docker-compose ps
        ;;
    "help"|"")
        show_help
        ;;
    *)
        echo -e "${RED}Comando desconhecido: ${1}${NC}"
        echo ""
        show_help
        exit 1
        ;;
esac 