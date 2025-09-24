<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Get article workflow demo data
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function demo(Request $request, $id = 1)
    {
        try {
            // Simulate article data
            $articles = [
                1 => [
                    'id' => 1,
                    'title' => 'Sample Article',
                    'content' => 'This is a sample article content.',
                    'status' => 'draft'
                ],
                2 => [
                    'id' => 2,
                    'title' => 'Sample Article',
                    'content' => 'This is a sample article content.',
                    'status' => 'review'
                ],
                3 => [
                    'id' => 3,
                    'title' => 'Sample Article',
                    'content' => 'This is a sample article content.',
                    'status' => 'published'
                ],
                4 => [
                    'id' => 4,
                    'title' => 'Sample Article',
                    'content' => 'This is a sample article content.',
                    'status' => 'rejected'
                ]
            ];

            // Get the article or default to draft
            $article = $articles[$id] ?? $articles[1];

            // Define available transitions based on current status
            $transitions = [
                'draft' => ['submit'],
                'review' => ['approve', 'reject'],
                'published' => [],
                'rejected' => ['resubmit']
            ];

            $availableTransitions = $transitions[$article['status']] ?? [];

            return response()->json([
                'success' => true,
                'data' => [
                    'article' => $article,
                    'availableTransitions' => $availableTransitions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply a transition to an article
     *
     * @param Request $request
     * @param int $id
     * @param string $transition
     * @return JsonResponse
     */
    public function transition(Request $request, $id, $transition)
    {
        try {
            // Simulate article data
            $articles = [
                1 => [
                    'id' => 1,
                    'title' => 'Sample Article',
                    'content' => 'This is a sample article content.',
                    'status' => 'draft'
                ],
                2 => [
                    'id' => 2,
                    'title' => 'Sample Article',
                    'content' => 'This is a sample article content.',
                    'status' => 'review'
                ],
                3 => [
                    'id' => 3,
                    'title' => 'Sample Article',
                    'content' => 'This is a sample article content.',
                    'status' => 'published'
                ],
                4 => [
                    'id' => 4,
                    'title' => 'Sample Article',
                    'content' => 'This is a sample article content.',
                    'status' => 'rejected'
                ]
            ];

            // Get the article or default to draft
            $article = $articles[$id] ?? $articles[1];

            // Define transition rules
            $transitionRules = [
                'draft' => ['submit' => 'review'],
                'review' => ['approve' => 'published', 'reject' => 'rejected'],
                'published' => [],
                'rejected' => ['resubmit' => 'review']
            ];

            // Check if transition is valid
            $availableTransitions = $transitionRules[$article['status']] ?? [];
            
            if (!isset($availableTransitions[$transition])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid transition',
                    'current_status' => $article['status'],
                    'available_transitions' => array_keys($availableTransitions)
                ], 400);
            }

            $newStatus = $availableTransitions[$transition];

            // Update article status
            $article['status'] = $newStatus;

            // Get new available transitions for the new status
            $newAvailableTransitions = array_keys($transitionRules[$newStatus] ?? []);

            return response()->json([
                'success' => true,
                'data' => [
                    'article' => $article,
                    'previous_status' => $articles[$id]['status'] ?? 'draft',
                    'transition_applied' => $transition,
                    'new_status' => $newStatus,
                    'available_transitions' => $newAvailableTransitions,
                    'message' => "Article successfully transitioned from {$articles[$id]['status']} to {$newStatus} via {$transition}"
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}