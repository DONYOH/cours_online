<x-layouts.app :wide="true">
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-violet-900">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
            <div class="max-w-3xl">
                <span class="badge bg-white/10 text-indigo-200 ring-1 ring-white/20">LMS nouvelle génération · open source</span>
                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-6xl">Apprendre et enseigner,<br><span class="bg-gradient-to-r from-indigo-300 to-fuchsia-300 bg-clip-text text-transparent">sans la complexité.</span></h1>
                <p class="mt-6 text-lg text-indigo-100/80">Créez un cours en quelques minutes, générez vos quiz automatiquement, suivez la progression de chaque apprenant et repérez le décrochage avant qu'il ne soit trop tard.</p>
                <div class="mt-10 flex flex-wrap gap-3">
                    <a href="{{ route('catalog') }}" class="btn bg-white px-6 py-3 text-slate-900 hover:bg-indigo-50">Explorer les cours</a>
                    @guest<a href="{{ route('register') }}" class="btn px-6 py-3 text-white ring-1 ring-white/30 hover:bg-white/10">Créer un compte gratuit</a>@endguest
                </div>
                <dl class="mt-14 grid max-w-lg grid-cols-3 gap-6 text-white">
                    <div><dt class="text-sm text-indigo-200/70">Cours</dt><dd class="text-3xl font-extrabold">{{ $stats['courses'] }}</dd></div>
                    <div><dt class="text-sm text-indigo-200/70">Apprenants</dt><dd class="text-3xl font-extrabold">{{ $stats['learners'] }}</dd></div>
                    <div><dt class="text-sm text-indigo-200/70">Certificats</dt><dd class="text-3xl font-extrabold">{{ $stats['certificates'] }}</dd></div>
                </dl>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ([
                ['✨', 'Quiz générés par IA', 'Transformez un support de cours en questions d\'évaluation en un clic, avec correction automatique et crédit partiel.'],
                ['📈', 'Détection du décrochage', 'Un score de risque expliqué pour chaque apprenant : inactivité, retard, résultats, devoirs manqués.'],
                ['🧩', 'Constructeur glisser-déposer', 'Organisez sections, leçons, quiz et devoirs à la souris. Fini les menus à rallonge.'],
                ['🏆', 'Gamification', 'Points d\'expérience, niveaux, classement et certificats vérifiables pour garder la motivation.'],
                ['💬', 'Forum avec solutions', 'Questions/réponses par cours, réponses marquées comme solution, notifications.'],
                ['🔌', 'API REST', 'Jetons Sanctum pour brancher une app mobile, un SI ou un outil de BI.'],
            ] as [$icon, $title, $text])
                <div class="card p-6">
                    <div class="text-3xl">{{ $icon }}</div>
                    <h3 class="mt-3 font-bold text-slate-900">{{ $title }}</h3>
                    <p class="mt-1 text-sm text-slate-600">{{ $text }}</p>
                </div>
            @endforeach
        </div>

        @if($featured->isNotEmpty())
            <div class="mt-16 flex items-end justify-between">
                <h2 class="text-2xl font-extrabold">Cours populaires</h2>
                <a href="{{ route('catalog') }}" class="link text-sm">Tout le catalogue →</a>
            </div>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featured as $course)<x-course-card :course="$course" />@endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
