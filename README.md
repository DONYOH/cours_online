# EduSphere — LMS moderne (Laravel 12 · PHP 8.2)

Plateforme d'apprentissage en ligne pensée comme une alternative plus simple et plus « intelligente » à Moodle :
création de cours en glisser-déposer, quiz auto-corrigés (et générés par IA), devoirs, carnet de notes,
forum, gamification, certificats vérifiables, **learning analytics avec détection du décrochage** et API REST.

## Prérequis

- PHP **8.2+** (extensions : pdo_sqlite ou pdo_mysql, mbstring, intl, fileinfo)
- Composer 2, Node.js 20+
- SQLite (par défaut), MySQL/MariaDB ou PostgreSQL

## Installation

> Guide complet (Windows/Linux/macOS, MySQL, production Nginx, dépannage) : **[INSTALL.md](INSTALL.md)**

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite        # ou configurez DB_* pour MySQL
php artisan migrate --seed            # crée les tables + données de démonstration
npm install && npm run build
php artisan serve
```

Ouvrez http://localhost:8000 — comptes de démo (mot de passe `password`) :

| Rôle | E-mail |
|---|---|
| Administrateur | admin@edusphere.test |
| Enseignante | prof@edusphere.test |
| Enseignant | prof2@edusphere.test |
| Apprenant | etudiant@edusphere.test |

En développement : `composer dev` (serveur + file d'attente + Vite).
Tests : `php artisan test` (37 tests, 170 assertions).
Installation vierge sans démo : `php artisan migrate` puis `php artisan edusphere:admin vous@exemple.com`.

### Activer la génération de quiz par IA (optionnel)

Dans `.env` : `ANTHROPIC_API_KEY=...` (modèle configurable via `ANTHROPIC_MODEL`).
Sans clé, un générateur **local hors-ligne** crée des questions à trous à partir des leçons.

## Ce qui fait mieux que Moodle

| Besoin | Moodle | EduSphere |
|---|---|---|
| Créer un cours | Nombreux écrans et réglages | Constructeur unique, glisser-déposer (SortableJS) |
| Écrire un quiz | Banque de questions complexe | Éditeur en ligne + **génération IA** depuis les leçons |
| Correction | Stricte | Crédit partiel (choix multiples), tolérance casse/accents/fautes (réponses courtes) |
| Décrochage | Rapports bruts | **Score de risque 0–100 expliqué** (inactivité, retard vs progression attendue, résultats, devoirs manqués) |
| Qualité des quiz | — | Analyse d'items : taux de réussite par question, alerte énoncé trop dur/facile |
| Motivation | Badges à configurer | XP, niveaux, classement, certificats à code de vérification public |
| UX | Datée | Interface moderne, responsive, en français |
| Intégration | Web services lourds | API REST Sanctum `/api/v1` |

## Fonctionnalités

- **Rôles** : administrateur, enseignant, apprenant (policies Laravel ; l'admin a tous les droits).
- **Cours** : catégories, niveaux, dates, clé d'inscription, brouillon/publié, archivage (soft delete).
- **Contenu** : sections, leçons (texte Markdown, vidéo YouTube/Vimeo/MP4, document), quiz, devoirs.
- **Quiz** : choix unique, choix multiples, vrai/faux, réponse courte ; chrono avec soumission auto, tentatives limitées, mélange, correction détaillée.
- **Devoirs** : rendu texte + fichier, retard signalé ou refusé, notation + commentaire, notification.
- **Progression** automatique → certificat délivré à 100 % avec moyenne finale.
- **Carnet de notes** /20 (enseignant : matrice + export CSV Excel ; apprenant : ses notes).
- **Forum** par cours : épingler, verrouiller, réponse « solution ».
- **Annonces** notifiées aux inscrits ; centre de notifications.
- **Tableaux de bord** par rôle : échéances, notes récentes, copies à corriger, apprenants à risque, stats plateforme.
- **Analytique** par cours : KPI, activité 30 jours, répartition de la progression, tableau de risque.

## API REST (Sanctum)

```
POST   /api/v1/token                {email, password, device_name} → {token}
GET    /api/v1/courses              catalogue public (?q=)
GET    /api/v1/me                   (Bearer)
GET    /api/v1/me/courses           cours + progression
GET    /api/v1/courses/{slug}       plan du cours
GET    /api/v1/courses/{slug}/grades
DELETE /api/v1/token
```

## Architecture

```
app/
  Enums/          Role, CourseLevel, LessonType, QuestionType
  Models/         Course, Section, Lesson, Quiz, Question, QuizAttempt, Assignment, Submission,
                  Enrollment, Discussion, Reply, Announcement, Certificate, Activity, …
  Policies/       CoursePolicy (view, manage, learn, enroll)
  Services/       QuizGrader, ProgressService, GradebookService, RiskAnalyzer,
                  QuizGenerator (IA + repli local), CourseOutline, GamificationService, ActivityLogger
  Http/Controllers/  web + Api/ + Admin/
resources/views/  Blade + Tailwind CSS 4 + Alpine.js + Chart.js
tests/            Feature (parcours complet, droits, API, pages) + Unit (correction)
```

La table `activities` journalise chaque action (vue, complétion, soumission…) : elle alimente l'analytique
et peut être exportée vers un entrepôt de données / outil de BI.

## Pistes d'évolution

Réinitialisation de mot de passe par e-mail, import/export SCORM et LTI 1.3, groupes et cohortes,
banque de questions partagée, classes virtuelles, application mobile sur l'API, multi-établissement (SaaS).
