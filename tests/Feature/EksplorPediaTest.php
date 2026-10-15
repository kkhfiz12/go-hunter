<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizCategory;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EksplorPediaTest extends TestCase
{
    use RefreshDatabase;

    private City $city;

    private QuizCategory $category;

    private Quiz $quiz;

    private Question $question1;

    private Question $question2;

    private Reward $reward;

    protected function setUp(): void
    {
        parent::setUp();

        $this->city = City::create([
            'name' => 'DKI Jakarta',
            'slug' => 'jakarta',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $this->category = QuizCategory::create([
            'city_id' => $this->city->id,
            'title' => 'Eksplorasi Sejarah Jakarta Vol. 1',
            'slug' => 'eksplorasi-sejarah-jakarta-vol-1',
            'description' => 'Telusuri jejak transformasi Sunda Kelapa dan Batavia.',
            'thumbnail_url' => 'https://example.com/jakarta.jpg',
            'total_attempts' => 10,
        ]);

        $this->quiz = Quiz::create([
            'category_id' => $this->category->id,
            'title' => 'Paket 1: Ikon Monas & Batavia',
            'slug' => 'paket-1-ikon-monas-dan-batavia',
            'difficulty' => 'easy',
            'points_per_question' => 10,
            'time_limit_minutes' => 10,
            'total_questions' => 2,
        ]);

        $this->question1 = Question::create([
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Berapa tinggi total Monas?',
            'options_json' => ['112 M', '125 M', '132 M', '150 M'],
            'correct_answer_index' => 2,
            'explanation' => 'Tinggi Monas tepat 132 meter.',
        ]);

        $this->question2 = Question::create([
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Siapa arsitek Monas?',
            'options_json' => ['Frederich Silaban', 'Han Awal', 'Soejoedi', 'Ciputra'],
            'correct_answer_index' => 0,
            'explanation' => 'Arsiteknya adalah Frederich Silaban.',
        ]);

        $this->reward = Reward::create([
            'title' => 'Tiket Masuk Monas Gratis',
            'description' => 'Akses terusan gratis ke Monas.',
            'points_required' => 20,
            'stock' => 5,
            'voucher_code' => 'GH-MONAS-FREE',
            'category' => 'Tiket Wisata',
        ]);
    }

    public function test_can_access_eksplor_pedia_catalog_page(): void
    {
        $response = $this->get(route('eksplor-pedia.index'));

        $response->assertStatus(200);
        $response->assertSee('Eksplor Pedia');
        $response->assertSee('Eksplorasi Sejarah Jakarta Vol. 1');
        $response->assertSee('DKI Jakarta');
        $response->assertSee('Mulai Mengerjakan');
    }

    public function test_sidebar_displays_eksplor_pedia_navigation(): void
    {
        $response = $this->get(route('eksplor-pedia.index'));

        $response->assertStatus(200);
        $response->assertSee('Eksplor Pedia');
        $response->assertSee('Katalog Kuis Kota');
        $response->assertSee('Leaderboard');
        $response->assertSee('Tukar Reward');
    }

    public function test_can_view_category_sub_level_with_quizzes_and_difficulty(): void
    {
        $response = $this->get(route('eksplor-pedia.category', $this->category->slug));

        $response->assertStatus(200);
        $response->assertSee('Paket 1: Ikon Monas & Batavia');
        $response->assertSee('Mudah (+10 XP/soal)');
        $response->assertSee('Durasi 10 Menit');
        $response->assertSee('Maksimal +20 XP');
    }

    public function test_can_view_interactive_quiz_play_page(): void
    {
        $response = $this->get(route('eksplor-pedia.quiz', $this->quiz->slug));

        $response->assertStatus(200);
        $response->assertSee('Paket 1: Ikon Monas & Batavia');
        $response->assertSee('Berapa tinggi total Monas?');
    }

    public function test_can_submit_quiz_and_calculate_score_and_mutate_user_points(): void
    {
        $user = User::factory()->create([
            'total_points' => 0,
            'reputation_points' => 0,
            'badge_level' => 'Penjelajah Pemula',
        ]);

        $response = $this->actingAs($user)->postJson(route('eksplor-pedia.submit', $this->quiz->slug), [
            'answers' => [
                (string) $this->question1->id => 2, // Correct
                (string) $this->question2->id => 0, // Correct
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total_questions' => 2,
            'correct_count' => 2,
            'score' => 100,
            'points_earned' => 20,
            'new_total_points' => 20,
        ]);

        $user->refresh();
        $this->assertEquals(20, $user->total_points);
        $this->assertEquals(20, $user->reputation_points);

        $this->assertDatabaseHas('quiz_histories', [
            'user_id' => $user->id,
            'quiz_id' => $this->quiz->id,
            'score' => 100,
            'points_earned' => 20,
        ]);

        $this->category->refresh();
        $this->assertEquals(11, $this->category->total_attempts);
    }

    public function test_leaderboard_displays_top_users_ordered_by_points(): void
    {
        $userA = User::factory()->create(['name' => 'Alif Top', 'total_points' => 500, 'reputation_points' => 500]);
        $userB = User::factory()->create(['name' => 'Budi Mid', 'total_points' => 200, 'reputation_points' => 200]);

        $response = $this->get(route('eksplor-pedia.leaderboard'));

        $response->assertStatus(200);
        $response->assertSee('Alif Top');
        $response->assertSee('Budi Mid');
        $response->assertSee('★ 500');
    }

    public function test_can_view_rewards_catalog_and_redeem_with_sufficient_points(): void
    {
        $user = User::factory()->create([
            'total_points' => 50,
            'reputation_points' => 50,
        ]);

        $response = $this->actingAs($user)->post(route('eksplor-pedia.redeem', $this->reward->id));

        $response->assertRedirect();
        $response->assertSessionHas('success_redeem');

        $user->refresh();
        $this->assertEquals(30, $user->total_points);
        $this->assertEquals(30, $user->reputation_points);

        $this->reward->refresh();
        $this->assertEquals(4, $this->reward->stock);

        $this->assertDatabaseHas('user_rewards', [
            'user_id' => $user->id,
            'reward_id' => $this->reward->id,
        ]);
    }

    public function test_cannot_redeem_reward_with_insufficient_points(): void
    {
        $user = User::factory()->create([
            'total_points' => 10,
            'reputation_points' => 10,
        ]);

        $response = $this->actingAs($user)->post(route('eksplor-pedia.redeem', $this->reward->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertEquals(5, $this->reward->fresh()->stock);
    }
}
