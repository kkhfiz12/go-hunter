<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Quiz;
use App\Models\QuizCategory;
use App\Models\QuizHistory;
use App\Models\Reward;
use App\Models\User;
use App\Models\UserReward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EksplorPediaController extends Controller
{
    /**
     * Display the main Eksplor Pedia catalog page (city quiz cards).
     */
    public function index(Request $request): View
    {
        $selectedCitySlug = $request->query('city');

        $cities = City::whereHas('quizCategories')
            ->withCount('quizCategories')
            ->orderBy('name')
            ->get();

        $categoriesQuery = QuizCategory::with(['city', 'quizzes.questions'])
            ->withCount('quizzes');

        if ($selectedCitySlug) {
            $categoriesQuery->whereHas('city', function ($q) use ($selectedCitySlug) {
                $q->where('slug', $selectedCitySlug);
            });
        }

        $categories = $categoriesQuery->latest()->get();

        // Top 3 players for mini leaderboard on the hero section
        $topPlayers = User::where('total_points', '>', 0)
            ->orderByDesc('total_points')
            ->take(3)
            ->get();

        // Total available quizzes and points stats
        $totalQuizzesCount = Quiz::count();
        $totalAttemptsCount = (int) QuizCategory::sum('total_attempts');

        return view('eksplor-pedia.index', [
            'cities' => $cities,
            'categories' => $categories,
            'selectedCitySlug' => $selectedCitySlug,
            'topPlayers' => $topPlayers,
            'totalQuizzesCount' => $totalQuizzesCount,
            'totalAttemptsCount' => $totalAttemptsCount,
        ]);
    }

    /**
     * Display the sub-level quiz set list for a specific city package.
     */
    public function category(Request $request, QuizCategory $category): View
    {
        $category->load(['city', 'quizzes.questions']);

        $quizzes = $category->quizzes()
            ->withCount('questions')
            ->orderByRaw("CASE difficulty WHEN 'easy' THEN 1 WHEN 'medium' THEN 2 WHEN 'hard' THEN 3 ELSE 4 END")
            ->get();

        // Check if user has already taken any of these quizzes
        $userHistories = [];
        if ($request->user()) {
            $userHistories = QuizHistory::where('user_id', $request->user()->id)
                ->whereIn('quiz_id', $quizzes->pluck('id'))
                ->get()
                ->keyBy('quiz_id');
        }

        return view('eksplor-pedia.category', [
            'category' => $category,
            'quizzes' => $quizzes,
            'userHistories' => $userHistories,
        ]);
    }

    /**
     * Display the interactive quiz play view.
     */
    public function quiz(Request $request, Quiz $quiz): View
    {
        $quiz->load(['category.city', 'questions']);

        // Check if user has completed this quiz before
        $lastAttempt = null;
        if ($request->user()) {
            $lastAttempt = QuizHistory::where('user_id', $request->user()->id)
                ->where('quiz_id', $quiz->id)
                ->latest()
                ->first();
        }

        // Sanitized questions for client (omit correct answer index to prevent cheating)
        $clientQuestions = $quiz->questions->map(function ($q, $index) {
            return [
                'id' => $q->id,
                'index' => $index + 1,
                'question_text' => $q->question_text,
                'options' => $q->options_json,
            ];
        });

        return view('eksplor-pedia.play', [
            'quiz' => $quiz,
            'clientQuestions' => $clientQuestions,
            'lastAttempt' => $lastAttempt,
        ]);
    }

    /**
     * Process quiz submission, calculate score, mutate points, and record history.
     */
    public function submit(Request $request, Quiz $quiz): JsonResponse|RedirectResponse
    {
        $request->validate([
            'answers' => ['required', 'array'],
        ]);

        $submittedAnswers = $request->input('answers', []);
        $questions = $quiz->questions()->get();

        $correctCount = 0;
        $totalQuestions = $questions->count();
        $detailedResults = [];

        foreach ($questions as $question) {
            $questionId = (string) $question->id;
            $selectedAnswerIndex = isset($submittedAnswers[$questionId]) ? (int) $submittedAnswers[$questionId] : -1;
            $isCorrect = ($selectedAnswerIndex === $question->correct_answer_index);

            if ($isCorrect) {
                $correctCount++;
            }

            $options = $question->options_json ?? [];
            $detailedResults[] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'selected_index' => $selectedAnswerIndex,
                'selected_text' => $options[$selectedAnswerIndex] ?? 'Tidak Dijawab',
                'correct_index' => $question->correct_answer_index,
                'correct_text' => $options[$question->correct_answer_index] ?? '',
                'is_correct' => $isCorrect,
                'explanation' => $question->explanation,
            ];
        }

        $percentageScore = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 100) : 0;
        $pointsEarned = $correctCount * $quiz->points_per_question;

        $user = $request->user();
        $newTotalPoints = 0;
        $newBadgeLevel = 'Penjelajah Pemula';

        if ($user) {
            // Record quiz history
            QuizHistory::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'score' => $percentageScore,
                'points_earned' => $pointsEarned,
                'completed_at' => now(),
            ]);

            // Mutate user points
            $user->total_points = (int) $user->total_points + $pointsEarned;
            $user->reputation_points = (int) $user->reputation_points + $pointsEarned;
            $user->save();
            $newBadgeLevel = $user->syncBadgeLevel();
            $newTotalPoints = $user->total_points;

            // Increment category total attempts
            $quiz->category()->increment('total_attempts');
        }

        $responsePayload = [
            'success' => true,
            'quiz_title' => $quiz->title,
            'total_questions' => $totalQuestions,
            'correct_count' => $correctCount,
            'score' => $percentageScore,
            'points_earned' => $pointsEarned,
            'new_total_points' => $newTotalPoints,
            'new_badge_level' => $newBadgeLevel,
            'is_authenticated' => (bool) $user,
            'detailed_results' => $detailedResults,
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($responsePayload);
        }

        return back()->with('quiz_result', $responsePayload);
    }

    /**
     * Display the Global Leaderboard page (Top 50 users based on points).
     */
    public function leaderboard(Request $request): View
    {
        // PRD Requirement: SELECT id, name, total_points, badge_level FROM users ORDER BY total_points DESC LIMIT 50;
        $topUsers = User::orderByDesc('total_points')
            ->orderByDesc('reputation_points')
            ->take(50)
            ->get();

        $currentUserRank = null;
        if ($request->user()) {
            $currentUserId = $request->user()->id;
            foreach ($topUsers as $index => $u) {
                if ($u->id === $currentUserId) {
                    $currentUserRank = $index + 1;
                    break;
                }
            }

            // If user is outside top 50, calculate actual rank
            if (! $currentUserRank) {
                $userPoints = max($request->user()->total_points, $request->user()->reputation_points);
                $higherCount = User::where('total_points', '>', $userPoints)->count();
                $currentUserRank = $higherCount + 1;
            }
        }

        return view('eksplor-pedia.leaderboard', [
            'topUsers' => $topUsers,
            'currentUserRank' => $currentUserRank,
        ]);
    }

    /**
     * Display the rewards and voucher redemption catalog.
     */
    public function rewards(Request $request): View
    {
        $rewards = Reward::orderBy('points_required')->get();

        $userRedemptions = collect();
        if ($request->user()) {
            $userRedemptions = UserReward::with('reward')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->get();
        }

        return view('eksplor-pedia.rewards', [
            'rewards' => $rewards,
            'userRedemptions' => $userRedemptions,
        ]);
    }

    /**
     * Redeem points for a specific reward.
     */
    public function redeem(Request $request, Reward $reward): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Silakan masuk terlebih dahulu untuk menukarkan poin.'], 401);
            }

            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk menukarkan poin.');
        }

        // Validate stock
        if ($reward->stock <= 0) {
            $message = 'Maaf, stok reward ini sedang habis.';
            if ($request->wantsJson()) {
                return response()->json(['error' => $message], 400);
            }

            return back()->with('error', $message);
        }

        // Validate points
        $userPoints = max($user->total_points, $user->reputation_points);
        if ($userPoints < $reward->points_required) {
            $message = "Poin Anda ({$userPoints} XP) tidak mencukupi untuk menukar reward ini (butuh {$reward->points_required} XP).";
            if ($request->wantsJson()) {
                return response()->json(['error' => $message], 400);
            }

            return back()->with('error', $message);
        }

        // Generate unique redemption code instance
        $uniqueVoucherCode = $reward->voucher_code.'-'.strtoupper(Str::random(5));

        // Deduct points
        $user->total_points = max(0, (int) $user->total_points - $reward->points_required);
        $user->reputation_points = max(0, (int) $user->reputation_points - $reward->points_required);
        $user->save();
        $user->syncBadgeLevel();

        // Decrement stock
        $reward->decrement('stock');

        // Record redemption
        $redemption = UserReward::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'voucher_code' => $uniqueVoucherCode,
            'redeemed_at' => now(),
        ]);

        $successMessage = "Selamat! Anda berhasil menukarkan reward '{$reward->title}'. Kode Voucher Anda: {$uniqueVoucherCode}";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'voucher_code' => $uniqueVoucherCode,
                'remaining_points' => $user->total_points,
            ]);
        }

        return back()->with('success_redeem', [
            'title' => $reward->title,
            'code' => $uniqueVoucherCode,
            'message' => $successMessage,
        ]);
    }

    /**
     * Display user's quiz completion history.
     */
    public function history(Request $request): View
    {
        $user = $request->user();

        $histories = QuizHistory::with(['quiz.category.city'])
            ->where('user_id', $user->id)
            ->latest('completed_at')
            ->paginate(15);

        return view('eksplor-pedia.history', [
            'histories' => $histories,
        ]);
    }
}
