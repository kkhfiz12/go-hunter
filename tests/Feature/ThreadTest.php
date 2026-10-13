<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Destination;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_forum_index_renders_thread_list_and_category_filters(): void
    {
        $user = User::factory()->create(['name' => 'Bambang Sudirman']);
        $destination = Destination::factory()->create(['name' => 'Gunung Merapi']);

        Thread::factory()->create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'title' => 'Apakah jalur pendakian Selo Merapi saat ini dibuka?',
            'category' => 'weather_update',
            'views_count' => 120,
        ]);

        $response = $this->get(route('threads.index'));

        $response->assertStatus(200);
        $response->assertSee('Community Q&A Feed', false);
        $response->assertSee('Kondisi Terkini');
        $response->assertSee('Apakah jalur pendakian Selo Merapi saat ini dibuka?');
        $response->assertSee('Gunung Merapi');
    }

    public function test_forum_index_filters_by_category(): void
    {
        Thread::factory()->create([
            'title' => 'Info Jalur Angkot Stasiun ke Malioboro',
            'category' => 'traffic_transit',
        ]);

        Thread::factory()->create([
            'title' => 'Peringatan Hujan Deras dan Longsor Puncak',
            'category' => 'weather_update',
        ]);

        $response = $this->get(route('threads.index', ['category' => 'traffic_transit']));

        $response->assertStatus(200);
        $response->assertSee('Info Jalur Angkot Stasiun ke Malioboro');
        $response->assertDontSee('Peringatan Hujan Deras dan Longsor Puncak');
    }

    public function test_thread_card_component_renders_visual_specs_from_desain_md(): void
    {
        $user = User::factory()->create(['name' => 'Citra Kirana']);
        $thread = Thread::factory()->create([
            'user_id' => $user->id,
            'title' => 'Jadwal Penyeberangan Kapal Feri Ketapang Gilimanuk',
            'content' => 'Apakah saat ini ada antrean panjang kendaraan di pelabuhan menuju Bali?',
            'category' => 'traffic_transit',
            'views_count' => 450,
        ]);

        $comment = Comment::factory()->create([
            'thread_id' => $thread->id,
            'content' => 'Info valid: antrean hanya 30 menit, kapal berangkat setiap 20 menit.',
            'is_solution' => true,
        ]);

        $thread->unsetRelation('comments');
        $thread->loadCount('comments');

        $rendered = Blade::render(
            '<x-cards.thread-card :thread="$thread" />',
            ['thread' => $thread]
        );

        // 1. Upvote counter button (Alpine.js)
        $this->assertStringContainsString('x-data', $rendered);
        $this->assertStringContainsString('Vote', $rendered);

        // 2. Kategori Pill
        $this->assertStringContainsString('Transportasi &amp; Jalur', $rendered);

        // 3. Badge Terkonfirmasi Warga Lokal
        $this->assertStringContainsString('Terkonfirmasi Warga Lokal', $rendered);

        // 4. Judul Diskusi Tebal & Preview Teks 2 Baris
        $this->assertStringContainsString('Jadwal Penyeberangan Kapal Feri Ketapang Gilimanuk', $rendered);
        $this->assertStringContainsString('Apakah saat ini ada antrean panjang kendaraan', $rendered);

        // 5. Counter komentar & Author
        $this->assertStringContainsString('1 Jawaban', $rendered);
        $this->assertStringContainsString('Citra Kirana', $rendered);
    }

    public function test_user_can_view_create_thread_page(): void
    {
        Destination::factory()->count(2)->create();

        $response = $this->get(route('threads.create'));

        $response->assertStatus(200);
        $response->assertSee('Tanyakan Situasi / Kondisi Terkini');
        $response->assertSee('Judul Pertanyaan / Topik');
        $response->assertSee('Kategori Topik');
    }

    public function test_user_can_create_thread_with_automatic_slug_and_gain_reputation(): void
    {
        $user = User::factory()->create(['reputation_points' => 15]);

        $response = $this->actingAs($user)->post(route('threads.store'), [
            'title' => 'Kondisi Ombak di Pantai Baron Hari Ini',
            'category' => 'weather_update',
            'content' => 'Apakah aman untuk berenang atau pasang air laut sedang tinggi?',
        ]);

        $thread = Thread::where('title', 'Kondisi Ombak di Pantai Baron Hari Ini')->first();
        $this->assertNotNull($thread);
        $this->assertSame('kondisi-ombak-di-pantai-baron-hari-ini', $thread->slug);

        $response->assertRedirect(route('threads.show', $thread));

        $user->refresh();
        $this->assertSame(20, $user->reputation_points);
    }

    public function test_thread_validation_fails_for_invalid_input(): void
    {
        $response = $this->post(route('threads.store'), [
            'title' => '',
            'category' => 'invalid_cat',
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'category', 'content']);
    }

    public function test_user_can_view_thread_with_nested_comments_and_solution_highlight(): void
    {
        $author = User::factory()->create(['name' => 'Doni Salman']);
        $solver = User::factory()->create(['name' => 'Pak RT Bromo']);
        $replier = User::factory()->create(['name' => 'Maya Backpacker']);

        $thread = Thread::factory()->create([
            'user_id' => $author->id,
            'title' => 'Apakah Bromo Buka Saat Nyepi?',
            'content' => 'Mohon infonya apakah kawasan wisata Bromo ditutup total saat hari raya Nyepi?',
            'views_count' => 50,
        ]);

        // Solution comment
        $solution = Comment::factory()->create([
            'thread_id' => $thread->id,
            'user_id' => $solver->id,
            'parent_id' => null,
            'content' => 'Benar, Bromo ditutup total selama 24 jam untuk menghormati perayaan Nyepi warga Tengger.',
            'is_solution' => true,
        ]);

        // Nested reply
        Comment::factory()->create([
            'thread_id' => $thread->id,
            'user_id' => $replier->id,
            'parent_id' => $solution->id,
            'content' => 'Terima kasih banyak infonya Pak, sangat membantu jadwal trip kami!',
        ]);

        $response = $this->get(route('threads.show', $thread->slug));

        $response->assertStatus(200);
        $response->assertSee('Apakah Bromo Buka Saat Nyepi?');
        $response->assertSee('Jawaban Solusi Terverifikasi / Warga Lokal');
        $response->assertSee('Pak RT Bromo');
        $response->assertSee('Benar, Bromo ditutup total selama 24 jam');
        $response->assertSee('Terima kasih banyak infonya Pak');

        // View count incremented
        $thread->refresh();
        $this->assertSame(51, $thread->views_count);
    }

    public function test_user_can_post_comment_and_nested_reply(): void
    {
        $user = User::factory()->create(['reputation_points' => 5]);
        $thread = Thread::factory()->create();

        // 1. Post Top-level Comment
        $response = $this->actingAs($user)->post(route('threads.comments.store', $thread->slug), [
            'content' => 'Jalur via Claket aman dan sudah diaspal mulus.',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'parent_id' => null,
            'content' => 'Jalur via Claket aman dan sudah diaspal mulus.',
        ]);

        $topComment = Comment::where('thread_id', $thread->id)->first();
        $user->refresh();
        $this->assertSame(7, $user->reputation_points);

        // 2. Post Nested Reply
        $replyUser = User::factory()->create(['reputation_points' => 10]);
        $replyResponse = $this->actingAs($replyUser)->post(route('threads.comments.store', $thread->slug), [
            'content' => 'Apakah tanjakannya masih curam untuk matic 110cc?',
            'parent_id' => $topComment->id,
        ]);

        $replyResponse->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'thread_id' => $thread->id,
            'user_id' => $replyUser->id,
            'parent_id' => $topComment->id,
            'content' => 'Apakah tanjakannya masih curam untuk matic 110cc?',
        ]);

        $replyUser->refresh();
        $this->assertSame(12, $replyUser->reputation_points);
    }

    public function test_thread_owner_can_mark_comment_as_solution(): void
    {
        $owner = User::factory()->create();
        $commenter = User::factory()->create(['reputation_points' => 20]);

        $thread = Thread::factory()->create(['user_id' => $owner->id]);
        $comment = Comment::factory()->create([
            'thread_id' => $thread->id,
            'user_id' => $commenter->id,
            'is_solution' => false,
        ]);

        // Owner marks as solution
        $response = $this->actingAs($owner)->post(route('comments.mark-as-solution', $comment));

        $response->assertStatus(302);
        $comment->refresh();
        $this->assertTrue($comment->is_solution);

        $commenter->refresh();
        $this->assertSame(30, $commenter->reputation_points); // +10 points

        // Another user attempts to toggle solution should get 403
        $stranger = User::factory()->create();
        $forbiddenResponse = $this->actingAs($stranger)->post(route('comments.mark-as-solution', $comment));
        $forbiddenResponse->assertStatus(403);
    }
}
