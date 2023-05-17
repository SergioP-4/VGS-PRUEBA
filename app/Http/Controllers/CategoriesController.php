<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;


class CategoriesController extends Controller
{
  /**
   * @return \Inertia\Response
   */
    public function index(): \Inertia\Response
    {
      return Inertia::render('Categories/Index', [
        'filters' => '',
        'categories' => Auth::user()->account->categories()
        ->paginate(10)
        ->withQueryString()
        ->through(fn ($categories) => [
          'id' => $categories->id,
          'name' => $categories->name,
          'slug' => $categories->slug,
        ]),
      ]);
    }

  /**
   * @return \Inertia\Response
   */
    public function create(): \Inertia\Response
    {
      return Inertia::render('Categories/Create');
    }

  /**
   * @return \Illuminate\Http\RedirectResponse
   */
    public function store(): \Illuminate\Http\RedirectResponse
    {
      Auth::user()->account->categories()->create(
        Request::validate([
          'name' => ['required', 'max:255'],
          'slug' => ['required', 'max:100']
        ])
      );

      return Redirect::route('categories')->with('success', 'Categories created.');
    }

  /**
   * @param Categories $categories
   * @return \Illuminate\Http\RedirectResponse
   */
    public function update(Categories $categories): \Illuminate\Http\RedirectResponse
    {
      $categories->update(
        Request::validate([
          'name' => ['required', 'max:255'],
          'slug' => ['required', 'max:255'],
        ])
      );

      return Redirect::back()->with('success', 'Categories updated.');
    }

  /**
   * @param Categories $categories
   * @return \Illuminate\Http\RedirectResponse
   */
    public function destroy(Categories $categories): \Illuminate\Http\RedirectResponse
    {

      $categories->delete();

      return Redirect::back()->with('success', 'Categories deleted.');
    }

  /**
   * @param Categories $categories
   * @return \Illuminate\Http\RedirectResponse
   */
    public function restore(Categories $categories): \Illuminate\Http\RedirectResponse
    {
      $categories->restore();

      return Redirect::back()->with('success', 'Categories restored.');
    }

}
