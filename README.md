# SortirENI

## Installation
1. cloner le projet
`git clone https://github.com/YangLENI/SortieENI.git`
2. installer les dépendances
`composer install`
3. configurer le .env  pour accéder à la bdd en local
`DATABASE_URL="mysql://root:@127.0.0.1:3306/sortieENI?serverVersion=10.11.2-MariaDB&charset=utf8mb4"`
4. créer la base de données
`php bin/console doctrine:database:create`
5. créer une migration
`php bin/console make:migration`
`php bin/console doctrine:migrations:migrate`
6. chargé les fixtures (faker)
`php bin/console doctrine:fixtures:load`
7. lancer le serveur
`symfony server:start`
8. utiliser d'un serveur SMTP pour tester la feature "mot de passe oublié
`https://www.papercut.com/help/manuals/ng-mf/common/sys-notifications-configure-email/`
9. si utilisation de papercut
`symfony console messenger:consume async`

# compte admin
admin@admin.fr
mdp: 123456

# Happy coding !
