# Guide d'installation complet — EduSphere

Ce guide couvre l'installation en local (Windows, Linux, macOS), le passage à MySQL/PostgreSQL,
la mise en production sur un serveur Linux et le dépannage.

Sommaire :
1. [Prérequis](#1-prérequis)
2. [Installation rapide (5 minutes)](#2-installation-rapide-5-minutes)
3. [Installation pas à pas](#3-installation-pas-à-pas)
4. [Base de données MySQL / MariaDB / PostgreSQL](#4-base-de-données-mysql--mariadb--postgresql)
5. [Configuration du fichier .env](#5-configuration-du-fichier-env)
6. [Activer l'IA pour la génération de quiz](#6-activer-lia-pour-la-génération-de-quiz)
7. [Lancer les tests](#7-lancer-les-tests)
8. [Mise en production (Linux + Nginx)](#8-mise-en-production-linux--nginx)
9. [Mettre à jour l'application](#9-mettre-à-jour-lapplication)
10. [Dépannage](#10-dépannage)

---

## 1. Prérequis

| Outil | Version | Vérifier |
|---|---|---|
| PHP | **8.2 ou plus** | `php -v` |
| Composer | 2.x | `composer -V` |
| Node.js + npm | Node 20 ou plus — **optionnel** (seulement pour modifier le CSS/JS) | `node -v` |
| Base de données | SQLite 3 (par défaut), MySQL 8 / MariaDB 10.6+, ou PostgreSQL 13+ | — |

**Extensions PHP requises** : `pdo_sqlite` (ou `pdo_mysql` / `pdo_pgsql`), `mbstring`, `openssl`,
`fileinfo`, `tokenizer`, `xml`, `ctype`, `curl`, `intl` (recommandée).
Vérifier avec : `php -m`

### Selon votre système

**Windows** — le plus simple : [Laragon](https://laragon.org) (PHP, Composer, MySQL, Node inclus).
Alternative : XAMPP + Composer + Node.js installés séparément.
Dans `php.ini`, décommentez (retirez le `;`) : `extension=pdo_sqlite`, `extension=sqlite3`,
`extension=fileinfo`, `extension=intl`, `extension=zip`.

**Ubuntu / Debian** :
```bash
sudo apt update
sudo apt install -y php8.2-cli php8.2-sqlite3 php8.2-mysql php8.2-mbstring php8.2-xml \
                    php8.2-curl php8.2-intl php8.2-zip unzip git
# Composer
curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer
# Node.js 20
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash - && sudo apt install -y nodejs
```
> Si `php8.2-*` est introuvable : `sudo add-apt-repository ppa:ondrej/php` puis relancer.

**macOS** (Homebrew) :
```bash
brew install php@8.2 composer node
```

---

## 2. Installation rapide (5 minutes)

Depuis le dossier du projet décompressé :

```bash
composer install
cp .env.example .env              # Windows (cmd) : copy .env.example .env
php artisan key:generate
touch database/database.sqlite    # Windows (PowerShell) : ni database/database.sqlite
php artisan migrate --seed
php artisan serve
```
> Le CSS/JS est déjà compilé dans `public/build/` : **npm n'est pas nécessaire** pour faire tourner l'application.

Ouvrez **http://localhost:8000**.

Comptes de démonstration (mot de passe : `password`) :

| Rôle | E-mail |
|---|---|
| Administrateur | admin@edusphere.test |
| Enseignante (cours Python, SQL) | prof@edusphere.test |
| Enseignant (cours Laravel) | prof2@edusphere.test |
| Apprenant | etudiant@edusphere.test |

---

## 3. Installation pas à pas

### 3.1 Récupérer le code
Décompressez `edusphere-lms.zip`, puis ouvrez un terminal dans le dossier `edusphere/`.

### 3.2 Dépendances PHP
```bash
composer install
```
Le projet est verrouillé pour PHP 8.2 (`config.platform.php = 8.2.0` dans `composer.json`) :
les versions installées restent compatibles même si votre machine a PHP 8.3 ou 8.4.

### 3.3 Fichier d'environnement et clé
```bash
cp .env.example .env
php artisan key:generate
```

### 3.4 Base de données (SQLite par défaut)
```bash
touch database/database.sqlite
php artisan migrate
```
Aucun serveur de base de données n'est nécessaire avec SQLite. Pour MySQL, voir la [section 4](#4-base-de-données-mysql--mariadb--postgresql).

### 3.5 Données
Deux possibilités :

- **Avec données de démonstration** (recommandé pour découvrir) :
  ```bash
  php artisan db:seed
  ```
  Crée 3 cours complets, 28 comptes, des tentatives de quiz, des devoirs, un forum et 30 jours d'activité.

- **Installation vierge** (usage réel) : créez uniquement votre administrateur :
  ```bash
  php artisan edusphere:admin vous@exemple.com --name="Votre Nom"
  ```
  Le mot de passe est demandé de façon masquée. Connectez-vous ensuite, allez dans
  **Administration → Utilisateurs** pour créer des enseignants, puis **Catégories**.

### 3.6 Interface (CSS / JS) — optionnel
Les fichiers compilés sont fournis dans `public/build/`. Ne lancez ces commandes que si vous modifiez
`resources/css`, `resources/js` ou des classes Tailwind dans les vues :
```bash
npm install
npm run build        # ou npm run dev pour le rechargement à chaud
```

### 3.7 Démarrer
```bash
php artisan serve                 # http://localhost:8000
```
Pour développer avec rechargement à chaud (serveur + file d'attente + Vite en parallèle) :
```bash
composer dev
```

### Repartir de zéro à tout moment
```bash
php artisan migrate:fresh --seed
```
⚠ Efface toutes les données.

---

## 4. Base de données MySQL / MariaDB / PostgreSQL

### MySQL / MariaDB
1. Créez la base et un utilisateur :
   ```sql
   CREATE DATABASE edusphere CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'edusphere'@'localhost' IDENTIFIED BY 'MotDePasseSolide';
   GRANT ALL PRIVILEGES ON edusphere.* TO 'edusphere'@'localhost';
   FLUSH PRIVILEGES;
   ```
2. Dans `.env`, remplacez `DB_CONNECTION=sqlite` par :
   ```dotenv
   DB_CONNECTION=mysql          # mariadb pour MariaDB
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=edusphere
   DB_USERNAME=edusphere
   DB_PASSWORD=MotDePasseSolide
   ```
3. Appliquez :
   ```bash
   php artisan config:clear
   php artisan migrate --seed
   ```

> **Laragon** : utilisateur `root`, mot de passe vide, base créée via HeidiSQL.


### Importer directement un fichier .sql (phpMyAdmin, HeidiSQL, Laragon…)
Deux dumps prêts à l'emploi sont fournis dans `database/` :

| Fichier | Contenu |
|---|---|
| `edusphere_demo.sql` | Structure + données de démonstration (4 cours, 28 comptes, quiz, devoirs, forum, activité) |
| `edusphere_vierge.sql` | Structure seule, aucun utilisateur |

1. Créez la base `edusphere` (utf8mb4_unicode_ci), puis importez le fichier :
   ```bash
   mysql -u root -p edusphere < database/edusphere_demo.sql
   ```
   ou phpMyAdmin → base `edusphere` → **Importer**.
2. Configurez `DB_*` dans `.env` comme ci-dessus — **ne lancez pas** `php artisan migrate --seed`
   (les tables et l'historique des migrations sont déjà présents).
3. Avec la base vierge, créez l'administrateur : `php artisan edusphere:admin vous@exemple.com`

> Les dates de démonstration (échéances, activité) sont figées au moment de l'export. Pour des données
> « fraîches » par rapport à aujourd'hui, préférez `php artisan migrate:fresh --seed`.

### PostgreSQL
```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=edusphere
DB_USERNAME=edusphere
DB_PASSWORD=MotDePasseSolide
```
Nécessite l'extension `pdo_pgsql`.

---

## 5. Configuration du fichier .env

| Variable | Rôle | Valeur conseillée |
|---|---|---|
| `APP_NAME` | Nom affiché dans l'interface | `EduSphere` (ou le nom de votre établissement) |
| `APP_ENV` | Environnement | `local` en dev, `production` en prod |
| `APP_DEBUG` | Affiche les erreurs détaillées | `true` en dev, **`false` en prod** |
| `APP_URL` | URL publique (utilisée dans les liens et certificats) | `https://lms.mon-ecole.fr` |
| `APP_TIMEZONE` | Fuseau des échéances | `Europe/Paris` (ou `Africa/Lome`) |
| `APP_LOCALE` | Langue | `fr` |
| `DB_*` | Base de données | voir section 4 |
| `SESSION_DRIVER` | Stockage des sessions | `database` (déjà configuré) |
| `FILESYSTEM_DISK` | Stockage des fichiers déposés | `local` (fichiers privés dans `storage/app/private`) |
| `ANTHROPIC_API_KEY` | Clé IA pour générer les quiz | optionnel, voir section 6 |
| `ANTHROPIC_MODEL` | Modèle IA | `claude-sonnet-5` |

Après toute modification de `.env` : `php artisan config:clear`

> L'encart « comptes de démonstration » sur la page de connexion n'apparaît que si `APP_ENV=local`.

### Taille des fichiers déposés
Les leçons acceptent des documents jusqu'à 50 Mo et les devoirs jusqu'à 20 Mo.
Ajustez `php.ini` en conséquence :
```ini
upload_max_filesize = 50M
post_max_size = 55M
```

---

## 6. Activer l'IA pour la génération de quiz

1. Créez une clé API sur https://console.anthropic.com
2. Ajoutez-la dans `.env` :
   ```dotenv
   ANTHROPIC_API_KEY=sk-ant-...
   ANTHROPIC_MODEL=claude-sonnet-5
   ```
3. `php artisan config:clear`

Dans un quiz (**Enseigner → cours → Contenu → Questions**), le bloc « ✨ Générer des questions »
utilise alors l'IA à partir des leçons de la section ou d'un texte collé.
Sans clé, ou si l'API est injoignable, un **générateur local hors-ligne** prend le relais (questions à trous).
Relisez toujours les questions générées avant de publier le quiz.

---

## 7. Lancer les tests

```bash
php artisan test
```
Résultat attendu : **37 tests, 170 assertions**, tous verts. Les tests utilisent une base SQLite
en mémoire : ils ne touchent pas à vos données.

---

## 8. Mise en production (Linux + Nginx)

Exemple pour Ubuntu 22.04/24.04 avec Nginx, PHP-FPM 8.2 et MySQL.

### 8.1 Paquets serveur
```bash
sudo apt install -y nginx php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
                    php8.2-curl php8.2-intl php8.2-zip php8.2-sqlite3 mysql-server unzip
```

### 8.2 Déployer le code
```bash
sudo mkdir -p /var/www/edusphere && sudo chown $USER:www-data /var/www/edusphere
cd /var/www/edusphere
# copiez-y le contenu du zip (scp, git clone…)

composer install --no-dev --optimize-autoloader
cp .env.example .env && php artisan key:generate
nano .env        # APP_ENV=production, APP_DEBUG=false, APP_URL, DB_* (MySQL)

php artisan migrate --force
php artisan edusphere:admin admin@mon-ecole.fr --name="Administrateur"

npm ci && npm run build       # ou buildez en local et envoyez public/build/

php artisan optimize          # met en cache config, routes, vues, événements
```

### 8.3 Permissions
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 8.4 Nginx — `/etc/nginx/sites-available/edusphere`
```nginx
server {
    listen 80;
    server_name lms.mon-ecole.fr;
    root /var/www/edusphere/public;
    index index.php;

    client_max_body_size 55M;
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```
```bash
sudo ln -s /etc/nginx/sites-available/edusphere /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 8.5 HTTPS (Let's Encrypt)
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d lms.mon-ecole.fr
```

### 8.6 File d'attente (optionnelle)
Les notifications sont actuellement envoyées de façon synchrone : aucun worker n'est obligatoire.
Si vous rendez des traitements asynchrones plus tard, lancez un worker avec Supervisor :
```ini
; /etc/supervisor/conf.d/edusphere-worker.conf
[program:edusphere-worker]
command=php /var/www/edusphere/artisan queue:work --sleep=3 --tries=3 --max-time=3600
user=www-data
autostart=true
autorestart=true
numprocs=1
stdout_logfile=/var/www/edusphere/storage/logs/worker.log
```
```bash
sudo supervisorctl reread && sudo supervisorctl update
```

### 8.7 Sauvegardes
À sauvegarder régulièrement :
- la base de données (`mysqldump edusphere > sauvegarde.sql`)
- le dossier `storage/app/private` (documents de cours et devoirs rendus)
- le fichier `.env`

### Checklist production
- [ ] `APP_ENV=production` et `APP_DEBUG=false`
- [ ] `APP_URL` en `https://…`
- [ ] Données de démo **non** chargées (ne pas lancer `db:seed`)
- [ ] `php artisan optimize` exécuté
- [ ] Permissions `storage/` et `bootstrap/cache/` correctes
- [ ] HTTPS actif
- [ ] Sauvegardes planifiées

---

## 9. Mettre à jour l'application

```bash
php artisan down
# remplacez le code par la nouvelle version
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci && npm run build
php artisan optimize
php artisan up
```

---

## 10. Dépannage

| Symptôme | Cause probable | Solution |
|---|---|---|
| `Your requirements could not be resolved…` ou blocage sur `composer install` | Fichier `composer.lock` incompatible avec votre environnement | `composer update` (régénère le lock en restant compatible PHP 8.2) |
| `could not find driver` | Extension PDO manquante | Activez `pdo_sqlite` / `pdo_mysql` dans `php.ini`, redémarrez |
| `Database file at path … does not exist` | Fichier SQLite absent | `touch database/database.sqlite` |
| `No application encryption key has been specified` | Clé non générée | `php artisan key:generate` |
| `Vite manifest not found` | Assets non compilés | `npm install && npm run build` (ou `npm run dev` en développement) |
| Page sans style | Idem, ou `public/hot` qui traîne après `npm run dev` | Supprimez `public/hot`, puis `npm run build` |
| Erreur 419 « Page Expired » | Session expirée ou `APP_URL` incohérent | Rechargez la page ; vérifiez `APP_URL` et le domaine |
| Erreur 500 en production | Voir le journal | `tail -f storage/logs/laravel.log` |
| `Permission denied` sur `storage/` | Droits Linux | Section 8.3 |
| Dépôt de fichier refusé (trop gros) | Limites PHP / Nginx | `upload_max_filesize`, `post_max_size`, `client_max_body_size` |
| Changement dans `.env` sans effet | Configuration en cache | `php artisan config:clear` (ou `php artisan optimize` en prod) |
| Génération de quiz en « mode local » malgré la clé | Clé absente du cache ou API injoignable | `php artisan config:clear` ; consultez `storage/logs/laravel.log` |
| Dates/échéances décalées | Mauvais fuseau | `APP_TIMEZONE=Europe/Paris` (ou `Africa/Lome`) |
| Mot de passe administrateur oublié | — | `php artisan edusphere:admin email@existant.fr` (réinitialise et promeut) |

---

## Référence rapide des commandes

| Commande | Effet |
|---|---|
| `php artisan serve` | Serveur de développement |
| `composer dev` | Serveur + file d'attente + Vite (rechargement à chaud) |
| `php artisan migrate --seed` | Créer les tables + données de démo |
| `php artisan migrate:fresh --seed` | Tout réinitialiser (⚠ efface les données) |
| `php artisan edusphere:admin <email>` | Créer / réinitialiser un administrateur |
| `php artisan test` | Lancer la suite de tests |
| `php artisan optimize` / `optimize:clear` | Mettre en cache / vider les caches |
| `php artisan route:list` | Lister les routes web et API |
