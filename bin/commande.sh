#!/bin/bash

# Afficher l'aide
show_help() {
    echo "Usage: ./commande.sh [options]"
    echo ""
    echo "Options disponibles :"
    echo "--migration, -m                : Créer et appliquer une migration"
    echo "--help, -h                    : Afficher l'aide"
}

# Si aucun argument
if [ $# -eq 0 ]; then
    show_help
    exit 1
fi

# Variables
FIXTURES_TYPE=""
SEND_DATE=""

# Analyse des arguments
case "$1" in
    -h|--help)
        show_help
        exit 0
        ;;
    -m|--migration)
        echo "Création et application de la migration..."
        php bin/console make:migration --no-interaction
        php bin/console doctrine:migrations:migrate --no-interaction
        exit 0
        ;;
    *)
        echo "Option inconnue : $1"
        show_help
        exit 1
        ;;
esac
