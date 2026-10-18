<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function project(string $slug): View
    {
        $project = DB::table('projects')
            ->leftJoin('project_categories', 'projects.project_category_id', '=', 'project_categories.id')
            ->select('projects.*', 'project_categories.name as category_name')
            ->where('projects.slug', $slug)
            ->where('projects.is_published', true)
            ->where(function ($query) {
                $query->whereNull('projects.published_at')->orWhere('projects.published_at', '<=', now());
            })
            ->first();
        abort_unless($project, 404);
        $data = [
            'title' => $project->title,
            'type' => $project->category_name,
            'location' => $project->client_name,
            'year' => $project->completed_at ? date('Y', strtotime($project->completed_at)) : null,
            'image' => $project->cover_image,
            'description' => $project->description,
        ];

        return view('home.project-detail', compact('data'));
    }

    public function news(string $slug): View
    {
        $article = DB::table('news')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->first();
        abort_unless($article, 404);

        return view('home.news-detail', compact('article'));
    }
}
