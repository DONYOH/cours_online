<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\User;
use App\Services\ProgressService;
use App\Services\QuizGrader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Jeu de données de démonstration réaliste :
 * cours complets, apprenants aux profils d'engagement variés (assidus, irréguliers, décrocheurs),
 * pour que le tableau de bord, le carnet de notes et l'analytique soient parlants dès l'installation.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        fake()->seed(2026);
        mt_srand(2026);

        // ---------- Comptes ----------
        User::factory()->admin()->create(['name' => 'Admin EduSphere', 'email' => 'admin@edusphere.test']);
        $prof = User::factory()->teacher()->create(['name' => 'Awa Mensah', 'email' => 'prof@edusphere.test', 'bio' => 'Data engineer et formatrice Python / SQL.']);
        $prof2 = User::factory()->teacher()->create(['name' => 'Julien Martin', 'email' => 'prof2@edusphere.test']);
        $demo = User::factory()->create(['name' => 'Kofi Agbeko', 'email' => 'etudiant@edusphere.test']);

        $students = User::factory(24)->create()->prepend($demo);

        // ---------- Catégories ----------
        $cats = collect(['Data & IA', 'Développement web', 'Bases de données', 'Bureautique'])
            ->mapWithKeys(fn ($name) => [$name => Category::create(['name' => $name, 'slug' => Str::slug($name)])]);

        // ---------- Cours ----------
        $python = $this->pythonCourse($prof, $cats['Data & IA']);
        $sql = $this->sqlCourse($prof, $cats['Bases de données']);
        $laravel = $this->laravelCourse($prof2, $cats['Développement web']);

        $draft = $this->course($prof, $cats['Data & IA'], ['title' => 'Machine Learning avec scikit-learn', 'summary' => 'Régression, classification et validation croisée.', 'level' => 'advanced', 'is_published' => false]);
        $draft->sections()->create(['title' => 'Introduction', 'position' => 1]);

        // ---------- Simulation de l'activité des apprenants ----------
        foreach ([[$python, 22], [$sql, 16], [$laravel, 12]] as [$course, $count]) {
            $cohort = $students->shuffle()->take($count);
            if (! $cohort->contains($demo) && $course->is($python)) {
                $cohort->push($demo);
            }
            foreach ($cohort as $student) {
                $this->simulate($course, $student, $student->is($demo) ? 'demo' : null);
            }
        }

        $this->forum($python, $prof, $students);
    }

    // =====================================================================
    // Cours
    // =====================================================================

    private function pythonCourse(User $teacher, Category $cat): Course
    {
        $course = $this->course($teacher, $cat, [
            'title' => 'Python pour l\'analyse de données',
            'summary' => 'Des bases du langage à pandas : chargez, nettoyez, analysez et visualisez vos données.',
            'level' => 'beginner',
            'starts_at' => now()->subWeeks(4)->toDateString(),
            'ends_at' => now()->addWeeks(4)->toDateString(),
            'description' => "## Objectifs\n\n- Maîtriser la syntaxe de base de Python\n- Manipuler des tableaux de données avec **pandas**\n- Produire des graphiques clairs avec **matplotlib**\n\n## Prérequis\n\nAucun : ce cours part de zéro.\n\n## Évaluation\n\nQuiz auto-corrigés à chaque chapitre et un mini-projet d'analyse noté sur 20.",
        ]);

        $s1 = $this->section($course, 'Les bases de Python', 1);
        $this->lesson($s1, 'Variables et types de données', 12, <<<'MD'
En Python, une **variable** est un nom qui référence une valeur. On la crée par simple affectation, sans déclarer son type.

```python
age = 25            # int
prix = 19.99        # float
nom = "Awa"         # str
actif = True        # bool
```

Python est un langage à **typage dynamique** : le type est déterminé à l'exécution. La fonction `type()` permet de connaître le type d'une valeur.

Les **listes** stockent des collections ordonnées et modifiables, tandis que les **tuples** sont immuables. Les **dictionnaires** associent des clés à des valeurs, ce qui est idéal pour représenter un enregistrement.

```python
notes = [12, 15, 9]
etudiant = {"nom": "Kofi", "moyenne": 12.0}
```
MD);
        $this->lesson($s1, 'Conditions et boucles', 15, <<<'MD'
Les **conditions** s'écrivent avec `if`, `elif` et `else`. L'indentation délimite les blocs : elle est obligatoire en Python.

```python
if moyenne >= 10:
    print("Admis")
else:
    print("Ajourné")
```

La boucle **for** parcourt les éléments d'une séquence, tandis que la boucle **while** répète un bloc tant qu'une condition reste vraie.

Les **compréhensions de liste** offrent une syntaxe concise pour construire une liste : `carres = [x**2 for x in range(10)]`.
MD);
        $this->lesson($s1, 'Vidéo : premiers pas avec Jupyter', 8, "Découvrez l'environnement **Jupyter Notebook**, l'outil de référence des data analysts pour exécuter du code cellule par cellule.", 'video', 'https://www.youtube.com/watch?v=HW29067qVWk');
        $this->quiz($s1, 'Quiz — Les bases', [
            ['single', 'Quel est le type de la valeur 3.14 en Python ?', ['int', 'float', 'str', 'decimal'], [1], 'Un nombre à virgule est un float.'],
            ['true_false', 'En Python, l\'indentation est facultative.', [], [1], 'L\'indentation délimite les blocs : elle est obligatoire.'],
            ['multiple', 'Quelles structures sont modifiables (mutables) ?', ['list', 'tuple', 'dict', 'str'], [0, 2], 'Les listes et dictionnaires sont mutables ; tuples et chaînes sont immuables.'],
            ['short', 'Quelle fonction renvoie le type d\'une valeur ?', [], ['type', 'type()'], 'type(valeur) renvoie la classe de la valeur.'],
            ['single', 'Que produit [x*2 for x in range(3)] ?', ['[0, 2, 4]', '[2, 4, 6]', '[0, 1, 2]', 'Une erreur'], [0], 'range(3) produit 0, 1, 2.'],
        ], due: now()->addDays(3));

        $s2 = $this->section($course, 'Manipuler des données avec pandas', 2);
        $this->lesson($s2, 'Le DataFrame', 20, <<<'MD'
Le **DataFrame** est la structure centrale de pandas : un tableau à deux dimensions avec des colonnes nommées et typées.

```python
import pandas as pd
df = pd.read_csv("ventes.csv")
df.head()
```

La méthode `head()` affiche les premières lignes, `info()` résume les types et valeurs manquantes, et `describe()` calcule les statistiques descriptives des colonnes numériques.

On sélectionne une colonne avec `df["montant"]` et on filtre les lignes avec une condition booléenne : `df[df["montant"] > 100]`.
MD);
        $this->lesson($s2, 'Nettoyer les données', 18, <<<'MD'
Les données réelles sont rarement propres. Les **valeurs manquantes** se détectent avec `isna()` et se traitent avec `dropna()` ou `fillna()`.

Les **doublons** se suppriment avec `drop_duplicates()`. Il faut aussi vérifier les types : une date stockée comme texte se convertit avec `pd.to_datetime()`.

L'agrégation se fait avec `groupby()` : `df.groupby("region")["montant"].sum()` calcule le chiffre d'affaires par région.
MD);
        $this->quiz($s2, 'Quiz — pandas', [
            ['single', 'Quelle méthode affiche les premières lignes d\'un DataFrame ?', ['first()', 'head()', 'top()', 'show()'], [1], null],
            ['single', 'Comment remplacer les valeurs manquantes par 0 ?', ['df.dropna(0)', 'df.fillna(0)', 'df.replace(None)', 'df.isna(0)'], [1], null],
            ['multiple', 'Quelles méthodes aident à explorer un jeu de données ?', ['info()', 'describe()', 'to_csv()', 'head()'], [0, 1, 3], 'to_csv() sert à exporter.'],
            ['true_false', 'groupby() permet d\'agréger des données par catégorie.', [], [0], null],
        ], due: now()->addDays(10));
        $this->assignment($s2, 'Mini-projet : analyse des ventes', "Téléchargez le jeu de données de ventes, puis :\n\n1. Nettoyez les valeurs manquantes et les doublons\n2. Calculez le chiffre d'affaires par région et par mois\n3. Rédigez 5 constats métier\n\nRendez votre notebook `.ipynb` ou un PDF.", now()->subDays(2));

        $s3 = $this->section($course, 'Visualisation', 3);
        $this->lesson($s3, 'Graphiques avec matplotlib', 15, "La bibliothèque **matplotlib** permet de tracer des courbes, histogrammes et diagrammes.\n\n```python\nimport matplotlib.pyplot as plt\ndf.groupby(\"mois\")[\"montant\"].sum().plot(kind=\"bar\")\nplt.show()\n```\n\nUn bon graphique possède un titre explicite, des axes légendés et une seule idée principale.");
        $this->assignment($s3, 'Tableau de bord final', "Produisez un tableau de bord de 3 graphiques répondant à la question : *quelles régions faut-il prioriser ?*", now()->addDays(12));

        return $course;
    }

    private function sqlCourse(User $teacher, Category $cat): Course
    {
        $course = $this->course($teacher, $cat, [
            'title' => 'SQL et modélisation de bases de données',
            'summary' => 'Concevez un schéma relationnel propre et écrivez des requêtes SQL efficaces.',
            'level' => 'intermediate',
            'description' => "Du modèle conceptuel aux requêtes avancées : jointures, agrégations, sous-requêtes et index.",
        ]);

        $s1 = $this->section($course, 'Modélisation', 1);
        $this->lesson($s1, 'Entités, associations et cardinalités', 20, "Un **modèle conceptuel** décrit les entités (Client, Commande…) et leurs associations. Les **cardinalités** précisent combien d'occurrences d'une entité peuvent être liées à une autre.\n\nLa **normalisation** élimine la redondance : en troisième forme normale, chaque attribut non clé dépend uniquement de la clé primaire.");
        $this->lesson($s1, 'Clés primaires et étrangères', 12, "La **clé primaire** identifie de façon unique chaque ligne d'une table. Une **clé étrangère** référence la clé primaire d'une autre table et garantit l'intégrité référentielle.");
        $this->quiz($s1, 'Quiz — Modélisation', [
            ['single', 'Quel est le rôle d\'une clé étrangère ?', ['Accélérer les requêtes', 'Garantir l\'intégrité référentielle', 'Chiffrer les données', 'Trier la table'], [1], null],
            ['true_false', 'Une table peut avoir plusieurs clés primaires distinctes.', [], [1], 'Une seule clé primaire, éventuellement composée de plusieurs colonnes.'],
            ['short', 'Quel processus élimine la redondance dans un schéma ?', [], ['normalisation', 'la normalisation'], null],
        ]);

        $s2 = $this->section($course, 'Requêtes SQL', 2);
        $this->lesson($s2, 'SELECT, WHERE et ORDER BY', 15, "```sql\nSELECT nom, ville FROM clients WHERE pays = 'Togo' ORDER BY nom;\n```\n\nLa clause **WHERE** filtre les lignes, **ORDER BY** les trie.");
        $this->lesson($s2, 'Jointures et agrégations', 25, "La **jointure interne** (INNER JOIN) ne conserve que les lignes correspondantes dans les deux tables, alors que la **jointure gauche** (LEFT JOIN) conserve toutes les lignes de la table de gauche.\n\nLes fonctions d'agrégation (COUNT, SUM, AVG) s'utilisent avec **GROUP BY** ; la clause **HAVING** filtre après agrégation.");
        $this->quiz($s2, 'Quiz — Requêtes', [
            ['single', 'Quelle clause filtre après un GROUP BY ?', ['WHERE', 'HAVING', 'FILTER', 'LIMIT'], [1], null],
            ['multiple', 'Quelles sont des fonctions d\'agrégation ?', ['COUNT', 'SUM', 'CONCAT', 'AVG'], [0, 1, 3], null],
            ['single', 'Quelle jointure conserve toutes les lignes de la table de gauche ?', ['INNER JOIN', 'LEFT JOIN', 'CROSS JOIN', 'SELF JOIN'], [1], null],
        ], timeLimit: 10, maxAttempts: 3);
        $this->assignment($s2, 'Étude de cas : base de données d\'une école', "Proposez le MCD puis le script SQL de création des tables d'une école (élèves, classes, notes, enseignants).", now()->addDays(20));

        return $course;
    }

    private function laravelCourse(User $teacher, Category $cat): Course
    {
        $course = $this->course($teacher, $cat, [
            'title' => 'Créer une API REST avec Laravel 12',
            'summary' => 'Routes, contrôleurs, Eloquent, validation et authentification par jetons avec Sanctum.',
            'level' => 'intermediate',
            'enrollment_key' => null,
            'description' => "Construisez pas à pas une API REST professionnelle avec Laravel 12 et PHP 8.2.",
        ]);

        $s1 = $this->section($course, 'Fondamentaux', 1);
        $this->lesson($s1, 'Routes et contrôleurs', 15, "Les routes d'API se déclarent dans `routes/api.php`. Un **contrôleur de ressource** regroupe les actions index, store, show, update et destroy.\n\n```php\nRoute::apiResource('articles', ArticleController::class);\n```");
        $this->lesson($s1, 'Eloquent ORM', 20, "**Eloquent** associe chaque table à un modèle. Les relations (`hasMany`, `belongsTo`) se déclarent comme des méthodes, et le chargement anticipé avec `with()` évite le problème des requêtes N+1.");
        $this->quiz($s1, 'Quiz — Fondamentaux Laravel', [
            ['single', 'Quelle méthode évite le problème N+1 ?', ['load()', 'with()', 'all()', 'find()'], [1], 'with() charge les relations en amont (eager loading).'],
            ['true_false', 'Route::apiResource crée aussi les routes create et edit.', [], [1], 'apiResource exclut les formulaires create/edit.'],
        ]);
        $s2 = $this->section($course, 'Sécurité', 2);
        $this->lesson($s2, 'Authentification avec Sanctum', 18, "**Sanctum** délivre des jetons d'API personnels. Le middleware `auth:sanctum` protège les routes.");
        $this->assignment($s2, 'API de gestion de bibliothèque', 'Livrez une API CRUD sécurisée (livres, auteurs, emprunts) avec tests.', now()->addDays(15));

        return $course;
    }

    // =====================================================================
    // Simulation
    // =====================================================================

    private function simulate(Course $course, User $student, ?string $forcedProfile = null): void
    {
        // Profils : assidu (35 %), régulier (35 %), irrégulier (15 %), décrocheur (15 %)
        $roll = mt_rand(1, 100);
        $profile = $forcedProfile ?? match (true) {
            $roll <= 35 => 'assidu',
            $roll <= 70 => 'regulier',
            $roll <= 85 => 'irregulier',
            default => 'decrocheur',
        };

        [$ratio, $skill, $idleDays] = match ($profile) {
            'assidu' => [mt_rand(80, 100) / 100, 0.85, mt_rand(0, 2)],
            'regulier' => [mt_rand(45, 75) / 100, 0.7, mt_rand(1, 5)],
            'irregulier' => [mt_rand(20, 45) / 100, 0.55, mt_rand(6, 12)],
            'decrocheur' => [mt_rand(0, 15) / 100, 0.35, mt_rand(16, 28)],
            'demo' => [0.45, 0.75, 1],
        };

        $enrolledAt = now()->subDays(mt_rand(22, 30));
        $enrollment = Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $student->id,
            'last_activity_at' => now()->subDays($idleDays),
        ]);
        $enrollment->forceFill(['created_at' => $enrolledAt])->save();

        $items = $course->sections()->with(['lessons', 'quizzes.questions', 'assignments'])->get()
            ->flatMap(fn (Section $s) => $s->items(true))->values();
        $toDo = (int) round($items->count() * $ratio);
        $lastActive = now()->subDays($idleDays);
        $span = max(1, $enrolledAt->diffInDays($lastActive));
        $activityRows = [];

        foreach ($items->take($toDo) as $i => $item) {
            $when = $enrolledAt->copy()->addDays((int) floor($span * ($i + 1) / max(1, $toDo)))->setTime(mt_rand(8, 21), mt_rand(0, 59));
            if ($when->greaterThan(now())) {
                $when = now()->subHour();
            }

            match ($item->kind()) {
                'lesson' => $student->completedLessons()->attach($item->id, ['created_at' => $when, 'updated_at' => $when]),
                'quiz' => $this->takeQuiz($item, $student, $skill, $when),
                'assignment' => $this->submit($item, $student, $skill, $when),
            };

            $activityRows[] = $this->activityRow($student, $course, $item->kind().'_completed', $when);
            for ($k = 0; $k < mt_rand(1, 3); $k++) {
                $activityRows[] = $this->activityRow($student, $course, 'lesson_viewed', $when->copy()->subMinutes(mt_rand(5, 90)));
            }
        }

        Activity::insert($activityRows);
        $student->increment('xp', $toDo * mt_rand(12, 22));
        app(ProgressService::class)->refresh($course, $student);
    }

    private function takeQuiz(Quiz $quiz, User $student, float $skill, Carbon $when): void
    {
        $attempts = mt_rand(1, 2);
        for ($a = 0; $a < $attempts; $a++) {
            $answers = [];
            foreach ($quiz->questions as $q) {
                $right = mt_rand(1, 100) <= ($skill + $a * 0.1) * 100;
                $answers[$q->id] = match ($q->type->value) {
                    'short' => $right ? $q->correct[0] : 'je ne sais pas',
                    'multiple' => $right ? $q->correct : [0],
                    default => $right ? $q->correct[0] : (($q->correct[0] + 1) % max(2, count($q->displayOptions()))),
                };
            }
            $attempt = $quiz->attempts()->create(['user_id' => $student->id, 'question_order' => $quiz->questions->pluck('id')->all(), 'started_at' => $when->copy()->subMinutes(8)]);
            $attempt->setRelation('quiz', $quiz);
            app(QuizGrader::class)->grade($attempt, $answers);
            $attempt->forceFill(['submitted_at' => $when, 'created_at' => $when])->save();

            if ($attempt->passed) {
                break;
            }
        }
    }

    private function submit($assignment, User $student, float $skill, Carbon $when): void
    {
        $graded = $assignment->due_at && $assignment->due_at->isPast() && mt_rand(1, 100) <= 70;
        $assignment->submissions()->create([
            'user_id' => $student->id,
            'content' => "Voici mon rendu pour « {$assignment->title} ».\n\n- Démarche suivie\n- Résultats obtenus\n- Limites identifiées",
            'submitted_at' => $when,
            'is_late' => $assignment->due_at && $when->greaterThan($assignment->due_at),
            'grade' => $graded ? round(min($assignment->max_points, max(0, $assignment->max_points * ($skill + mt_rand(-15, 15) / 100))) * 4) / 4 : null,
            'feedback' => $graded ? fake()->randomElement(['Bon travail, analyse claire.', 'Pensez à justifier vos choix de nettoyage.', 'Très complet, bravo !', 'Les graphiques manquent de titres.']) : null,
            'graded_at' => $graded ? $when->copy()->addDays(2) : null,
            'graded_by' => $graded ? $assignment->course->teacher_id : null,
        ]);
    }

    private function forum(Course $course, User $teacher, $students): void
    {
        $threads = [
            ['Différence entre liste et tuple ?', 'Je ne comprends pas quand utiliser un tuple plutôt qu\'une liste. Quelqu\'un peut expliquer ?', 'Un tuple est immuable : utilisez-le pour des données qui ne doivent pas changer (coordonnées, clés de dictionnaire). Sinon, une liste.'],
            ['Erreur KeyError avec pandas', 'J\'obtiens `KeyError: \'Montant\'` en sélectionnant ma colonne.', 'Vérifiez la casse avec `df.columns` : la colonne s\'appelle sans doute `montant` en minuscules.'],
            ['Ressources pour aller plus loin', 'Des livres ou sites à conseiller pour progresser en data ?', null],
        ];

        foreach ($threads as $i => [$title, $body, $answer]) {
            $author = $students[$i];
            $d = $course->discussions()->create(['user_id' => $author->id, 'title' => $title, 'body' => $body, 'is_pinned' => $i === 2, 'last_reply_at' => now()->subDays(3 - $i)]);
            if ($answer) {
                $d->replies()->create(['user_id' => $teacher->id, 'body' => $answer, 'is_solution' => true]);
                $d->replies()->create(['user_id' => $students[$i + 4]->id, 'body' => 'Merci, c\'est beaucoup plus clair !']);
            }
        }

        $course->announcements()->create([
            'user_id' => $teacher->id,
            'title' => 'Bienvenue dans le cours !',
            'body' => 'Commencez par le chapitre 1 et n\'hésitez pas à poser vos questions sur le **forum**. Le mini-projet est à rendre en fin de semaine 4.',
        ]);
    }

    // =====================================================================
    // Helpers
    // =====================================================================

    private function course(User $teacher, Category $cat, array $attrs): Course
    {
        $course = new Course($attrs + ['category_id' => $cat->id, 'language' => 'fr', 'is_published' => true]);
        $course->teacher_id = $teacher->id;
        $course->save();

        return $course;
    }

    private function section(Course $course, string $title, int $position): Section
    {
        return $course->sections()->create(['title' => $title, 'position' => $position]);
    }

    private int $pos = 0;

    private function lesson(Section $section, string $title, int $minutes, string $content, string $type = 'text', ?string $video = null): void
    {
        $lesson = $section->lessons()->make(['title' => $title, 'type' => $type, 'content' => $content, 'video_url' => $video, 'duration_minutes' => $minutes, 'position' => ++$this->pos, 'is_published' => true]);
        $lesson->course_id = $section->course_id;
        $lesson->save();
    }

    private function quiz(Section $section, string $title, array $questions, ?Carbon $due = null, ?int $timeLimit = null, ?int $maxAttempts = null): void
    {
        $quiz = $section->quizzes()->make(['title' => $title, 'description' => 'Vérifiez vos acquis. Correction détaillée après soumission.', 'pass_score' => 60, 'show_answers' => true, 'due_at' => $due, 'time_limit_minutes' => $timeLimit, 'max_attempts' => $maxAttempts, 'position' => ++$this->pos, 'is_published' => true]);
        $quiz->course_id = $section->course_id;
        $quiz->save();

        foreach ($questions as $i => [$type, $prompt, $options, $correct, $explanation]) {
            $quiz->questions()->create(compact('type', 'prompt', 'options', 'correct', 'explanation') + ['points' => 1, 'position' => $i + 1]);
        }
    }

    private function assignment(Section $section, string $title, string $instructions, Carbon $due): void
    {
        $assignment = $section->assignments()->make(['title' => $title, 'instructions' => $instructions, 'due_at' => $due->setTime(23, 59), 'max_points' => 20, 'allow_late' => true, 'position' => ++$this->pos, 'is_published' => true]);
        $assignment->course_id = $section->course_id;
        $assignment->save();
    }

    private function activityRow(User $user, Course $course, string $type, Carbon $when): array
    {
        return ['user_id' => $user->id, 'course_id' => $course->id, 'type' => $type, 'subject_type' => null, 'subject_id' => null, 'meta' => null, 'created_at' => $when];
    }
}
