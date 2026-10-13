<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created answer/reply for a thread.
     */
    public function store(Request $request, Thread $thread): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        if (! empty($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);
            if (! $parent || $parent->thread_id !== $thread->id) {
                return back()->with('error', 'Komentar induk tidak valid.');
            }
        }

        $user = $request->user();

        if (! $user) {
            $user = User::first() ?? User::factory()->create([
                'name' => 'Kontributor Komunitas',
                'email' => 'kontributor@gohunter.id',
            ]);
        }

        // Reward user reputation points for contributing an answer
        $user->increment('reputation_points', 2);

        $comment = $thread->comments()->create([
            'user_id' => $user->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
            'is_solution' => false,
        ]);

        return redirect()
            ->to(route('threads.show', $thread).'#comment-'.$comment->id)
            ->with('success', 'Balasan Anda berhasil dikirim! Anda mendapatkan +2 poin reputasi.');
    }

    /**
     * Mark or unmark a comment as the verified solution.
     * Allowed for the thread creator or authenticated host.
     */
    public function markAsSolution(Comment $comment): RedirectResponse
    {
        $thread = $comment->thread;
        $user = auth()->user();

        // Only thread owner can mark solution if authenticated
        if ($user && $user->id !== $thread->user_id) {
            abort(403, 'Hanya pembuat thread yang berhak menandai jawaban solusi.');
        }

        $willBeSolution = ! $comment->is_solution;

        if ($willBeSolution) {
            // Unmark any existing solution in this thread
            $thread->comments()->where('id', '!=', $comment->id)->update(['is_solution' => false]);
            $comment->update(['is_solution' => true]);

            // Reward comment author with +10 reputation points for verified answer
            $comment->user->increment('reputation_points', 10);
            $message = 'Komentar berhasil ditandai sebagai Jawaban Solusi / Terkonfirmasi Warga Lokal (+10 Poin).';
        } else {
            $comment->update(['is_solution' => false]);
            $message = 'Tanda solusi terverifikasi berhasil dilepas.';
        }

        return back()->with('success', $message);
    }
}
