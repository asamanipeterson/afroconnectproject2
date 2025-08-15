<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Story;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketPlaceController extends Controller
{
    public function index(Request $request)
    {
        $stories = [];
        $query = Item::with('photos');

        // Filter by category if provided
        if ($request->has('category') && !empty($request->input('category'))) {
            $query->where('category', 'like', '%' . $request->input('category') . '%');
        }

        $items = $query->paginate(15);

        return view('market.index', [
            'stories' => $stories,
            'items' => $items,
        ]);
    }

    public function getItemData(Item $item): JsonResponse
    {
        // Eager load the photos and user relationship
        $item->load(['photos', 'user']);
        return response()->json($item);
    }

    public function newListing()
    {
        $stories = [];
        $user = Auth::user();
        // Logic to display the new listing form
        return view('market.newlist', [
            'stories' => $stories,
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming request data, including photos.
        $request->merge([
            'price' => str_replace(',', '', $request->price)
        ]);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'photos' => 'required|array|min:1|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // 2. Create the new Item record in the database.
        $item = Item::create([
            'title' => $validatedData['title'],
            'price' => $validatedData['price'],
            'category' => $validatedData['category'],
            'condition' => $validatedData['condition'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'user_id' => Auth::id(),
        ]);

        // 3. Store each photo and create a new record for it.
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = Storage::disk('public')->putFile('items', $photo);
                ItemPhoto::create([
                    'item_id' => $item->id,
                    'path' => $path,
                ]);
            }
        }

        // Redirect to the marketplace index with a success message
        return redirect()->route('marketshowroom')
            ->with('success', 'Listing created successfully!');
    }

    public function show(Request $request, Item $item)
    {
        // Eager load the photos and user relationships
        $item->load(['photos', 'user']);

        if ($request->ajax()) {
            return view('market.show', compact('item'));
        }

        $user = Auth::user();
        $followingIds = $user->following()->pluck('users.id');
        $visibleUserIds = $followingIds->push($user->id);

        $stories = Story::with('user')
            ->active()
            ->whereIn('user_id', $visibleUserIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');

        return view('market.show', compact('item', 'stories'));
    }
}
