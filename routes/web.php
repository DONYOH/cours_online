<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseBuilderController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradebookController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/catalogue', [CatalogController::class, 'index'])->name('catalog');
Route::get('/certificats/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

    Route::get('/tableau-de-bord', DashboardController::class)->name('dashboard');
    Route::get('/classement', LeaderboardController::class)->name('leaderboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/lire', [NotificationController::class, 'markAllRead'])->name('notifications.read');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'password'])->name('profile.password');

    // ----- Espace enseignant : gestion des cours -----
    Route::middleware('can:teach')->prefix('enseigner')->group(function () {
        Route::get('/cours', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/cours/nouveau', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/cours', [CourseController::class, 'store'])->name('courses.store');
    });

    Route::prefix('cours/{course}')->scopeBindings()->group(function () {
        Route::post('/inscription', [EnrollmentController::class, 'store'])->name('enrollments.store');
        Route::delete('/inscription', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');

        // Parcours apprenant
        Route::get('/apprendre', [LessonController::class, 'resume'])->name('courses.learn');
        Route::get('/lecons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
        Route::post('/lecons/{lesson}/terminer', [LessonController::class, 'complete'])->name('lessons.complete');
        Route::get('/lecons/{lesson}/fichier', [LessonController::class, 'download'])->name('lessons.download');

        Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
        Route::post('/quiz/{quiz}/tentatives', [QuizAttemptController::class, 'store'])->name('attempts.store');
        Route::get('/quiz/{quiz}/tentatives/{attempt}', [QuizAttemptController::class, 'show'])->name('attempts.show');
        Route::post('/quiz/{quiz}/tentatives/{attempt}', [QuizAttemptController::class, 'submit'])->name('attempts.submit');

        Route::get('/devoirs/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/devoirs/{assignment}/rendu', [SubmissionController::class, 'store'])->name('submissions.store');
        Route::get('/devoirs/{assignment}/rendus/{submission}/fichier', [SubmissionController::class, 'download'])->name('submissions.download');

        Route::get('/forum', [DiscussionController::class, 'index'])->name('discussions.index');
        Route::post('/forum', [DiscussionController::class, 'store'])->name('discussions.store');
        Route::get('/forum/{discussion}', [DiscussionController::class, 'show'])->name('discussions.show');
        Route::post('/forum/{discussion}/reponses', [DiscussionController::class, 'reply'])->name('discussions.reply');
        Route::post('/forum/{discussion}/reponses/{reply}/solution', [DiscussionController::class, 'markSolution'])->name('discussions.solution');
        Route::patch('/forum/{discussion}/moderation', [DiscussionController::class, 'moderate'])->name('discussions.moderate');

        Route::get('/notes', [GradebookController::class, 'show'])->name('gradebook.show');

        // Gestion (enseignant propriétaire ou admin — contrôlé par la policy "manage")
        Route::get('/modifier', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/contenu', [CourseBuilderController::class, 'show'])->name('courses.builder');
        Route::post('/contenu/ordre', [CourseBuilderController::class, 'reorder'])->name('courses.reorder');
        Route::get('/participants', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::delete('/participants/{enrollment}', [EnrollmentController::class, 'remove'])->name('enrollments.remove');
        Route::get('/notes/export', [GradebookController::class, 'export'])->name('gradebook.export');
        Route::get('/analytique', AnalyticsController::class)->name('courses.analytics');
        Route::post('/annonces', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/annonces/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
        Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
        Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

        Route::get('/sections/{section}/lecons/nouvelle', [LessonController::class, 'create'])->name('lessons.create');
        Route::post('/sections/{section}/lecons', [LessonController::class, 'store'])->name('lessons.store');
        Route::get('/lecons/{lesson}/modifier', [LessonController::class, 'edit'])->name('lessons.edit');
        Route::put('/lecons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
        Route::delete('/lecons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

        Route::get('/sections/{section}/quiz/nouveau', [QuizController::class, 'create'])->name('quizzes.create');
        Route::post('/sections/{section}/quiz', [QuizController::class, 'store'])->name('quizzes.store');
        Route::get('/quiz/{quiz}/modifier', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('/quiz/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
        Route::delete('/quiz/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
        Route::get('/quiz/{quiz}/resultats', [QuizController::class, 'results'])->name('quizzes.results');
        Route::post('/quiz/{quiz}/generer', [QuestionController::class, 'generate'])->name('questions.generate');
        Route::post('/quiz/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::put('/quiz/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::delete('/quiz/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

        Route::get('/sections/{section}/devoirs/nouveau', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/sections/{section}/devoirs', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::get('/devoirs/{assignment}/modifier', [AssignmentController::class, 'edit'])->name('assignments.edit');
        Route::put('/devoirs/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
        Route::delete('/devoirs/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
        Route::get('/devoirs/{assignment}/rendus', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::put('/devoirs/{assignment}/rendus/{submission}', [SubmissionController::class, 'grade'])->name('submissions.grade');
    });

    // Page de présentation d'un cours (après les routes /cours/{course}/... pour la lisibilité)
    Route::get('/cours/{course}', [CourseController::class, 'show'])->name('courses.show')->withoutMiddleware('auth');

    // ----- Administration -----
    Route::middleware('can:administrate')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
        Route::post('/utilisateurs', [UserController::class, 'store'])->name('users.store');
        Route::patch('/utilisateurs/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/utilisateurs/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});
