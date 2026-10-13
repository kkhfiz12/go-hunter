<?php

namespace Tests\Feature;

use App\Models\BuddyRequest;
use App\Models\Comment;
use App\Models\Destination;
use App\Models\PriceReport;
use App\Models\Review;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaAndRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user_with_profile_fields(): void
    {
        $user = User::factory()->create([
            'avatar' => 'avatars/johndoe.png',
            'bio' => 'Adventurer exploring Indonesia.',
            'phone_number' => '081234567890',
            'reputation_points' => 150,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'avatar' => 'avatars/johndoe.png',
            'phone_number' => '081234567890',
            'reputation_points' => 150,
        ]);
    }

    public function test_can_create_destination_with_all_attributes(): void
    {
        $destination = Destination::factory()->create([
            'name' => 'Kawah Ijen',
            'slug' => 'kawah-ijen',
            'city' => 'Banyuwangi',
            'province' => 'Jawa Timur',
            'official_ticket_price' => 15000.00,
            'parking_fee_motor' => 5000.00,
            'parking_fee_car' => 10000.00,
            'operating_hours' => ['open' => '02:00', 'close' => '12:00'],
        ]);

        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
            'name' => 'Kawah Ijen',
            'slug' => 'kawah-ijen',
            'city' => 'Banyuwangi',
        ]);

        $this->assertIsArray($destination->fresh()->operating_hours);
        $this->assertSame('02:00', $destination->fresh()->operating_hours['open']);
    }

    public function test_price_report_relations(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create();

        $priceReport = PriceReport::factory()->create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'item_name' => 'Ojek Troli Kawah Ijen',
            'price_paid' => 400000.00,
            'fairness_level' => 'fair',
        ]);

        $this->assertTrue($priceReport->user->is($user));
        $this->assertTrue($priceReport->destination->is($destination));
        $this->assertTrue($user->priceReports->contains($priceReport));
        $this->assertTrue($destination->priceReports->contains($priceReport));
    }

    public function test_threads_and_nested_comments_relations(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create();

        $thread = Thread::factory()->create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'category' => 'weather_update',
        ]);

        $comment = Comment::factory()->create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'content' => 'Top level comment',
        ]);

        $reply = Comment::factory()->create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'parent_id' => $comment->id,
            'content' => 'Nested reply',
        ]);

        $this->assertTrue($thread->user->is($user));
        $this->assertTrue($thread->destination->is($destination));
        $this->assertTrue($user->threads->contains($thread));
        $this->assertTrue($destination->threads->contains($thread));

        $this->assertTrue($comment->thread->is($thread));
        $this->assertTrue($comment->user->is($user));
        $this->assertTrue($reply->parent->is($comment));
        $this->assertTrue($comment->replies->contains($reply));
        $this->assertTrue($user->comments->contains($comment));
    }

    public function test_buddy_request_relations(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create();

        $buddyRequest = BuddyRequest::factory()->create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'status' => 'open',
            'slots_needed' => 2,
        ]);

        $this->assertTrue($buddyRequest->user->is($user));
        $this->assertTrue($buddyRequest->destination->is($destination));
        $this->assertTrue($user->buddyRequests->contains($buddyRequest));
        $this->assertTrue($destination->buddyRequests->contains($buddyRequest));
    }

    public function test_review_relations(): void
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create();

        $review = Review::factory()->create([
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'rating' => 5,
        ]);

        $this->assertTrue($review->user->is($user));
        $this->assertTrue($review->destination->is($destination));
        $this->assertTrue($user->reviews->contains($review));
        $this->assertTrue($destination->reviews->contains($review));
    }

    public function test_cascade_delete_on_destination_deletion(): void
    {
        $destination = Destination::factory()->create();
        $priceReport = PriceReport::factory()->create(['destination_id' => $destination->id]);
        $buddyRequest = BuddyRequest::factory()->create(['destination_id' => $destination->id]);
        $review = Review::factory()->create(['destination_id' => $destination->id]);
        $thread = Thread::factory()->create(['destination_id' => $destination->id]);

        $destination->delete();

        $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
        $this->assertDatabaseMissing('price_reports', ['id' => $priceReport->id]);
        $this->assertDatabaseMissing('buddy_requests', ['id' => $buddyRequest->id]);
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);

        // Thread destination_id is null on delete as specified in PRD 4.4
        $this->assertDatabaseHas('threads', [
            'id' => $thread->id,
            'destination_id' => null,
        ]);
    }

    public function test_cascade_delete_on_user_deletion(): void
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['user_id' => $user->id, 'thread_id' => $thread->id]);
        $priceReport = PriceReport::factory()->create(['user_id' => $user->id]);
        $buddyRequest = BuddyRequest::factory()->create(['user_id' => $user->id]);
        $review = Review::factory()->create(['user_id' => $user->id]);

        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertDatabaseMissing('price_reports', ['id' => $priceReport->id]);
        $this->assertDatabaseMissing('buddy_requests', ['id' => $buddyRequest->id]);
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
