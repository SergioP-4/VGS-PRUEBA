<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ArticleController extends Controller
{
  /**
   * @return \Inertia\Response
   */
  public function index(): \Inertia\Response
  {
    return Inertia::render('Article/Index', [
      'filters' => '',
      'article' => Auth::user()->account->article()
        ->paginate(10)
        ->withQueryString()
        ->through(fn ($article) => [
          'id' => $article->id,
          'title' => $article->Title,
          'slug' => $article->Slug,
          'content' => $article->Content
        ]),
    ]);
  }

  /**
   * @return \Inertia\Response
   */
  public function create(): \Inertia\Response
  {
    return Inertia::render('Article/Create');
  }

  /**
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(): \Illuminate\Http\RedirectResponse
  {
    Auth::user()->account->article()->create(
      Request::validate([
        'title' => ['required', 'max:255'],
        'slug' => ['required', 'max:100'],
        'content' => ['required', 'max:100'],
      ])
    );

    return Redirect::route('article')->with('success', 'Article created.');
  }

  /**
   * @param Article $article
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Article $article): \Illuminate\Http\RedirectResponse
  {
    $article->update(
      Request::validate([
        'title' => ['required', 'max:255'],
        'slug' => ['required', 'max:100'],
        'content' => ['required', 'max:100'],
      ])
    );

    return Redirect::back()->with('success', 'Article updated.');
  }

  /**
   * @param Article $article
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Article $article): \Illuminate\Http\RedirectResponse
  {

    $article->delete();

    return Redirect::back()->with('success', 'Categories deleted.');
  }

  /**
   * @param Article $article
   * @return \Illuminate\Http\RedirectResponse
   */
  public function restore(Article $article): \Illuminate\Http\RedirectResponse
  {
    $article->restore();

    return Redirect::back()->with('success', 'Article restored.');
  }
}
