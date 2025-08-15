<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\MarketPlaceController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ReportController;



use App\Events\TestMessageReceived;

Route::get('/broadcast-test', function () {
    broadcast(new TestMessageReceived('Hello from Laravel Reverb!'));
    return 'Event has been broadcast!';
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'homepage')->name('welcome')->middleware('auth');
    Route::get('/admin', 'homepage')->name('admin.dashboard')->middleware('auth'); // Changed to 'homepage'
    // Route::get('/userstable', 'usertable')->name('users.table')->middleware('auth');
});

// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'registerPage')->name('register')->middleware('guest');
    Route::post('register', 'registerLogic')->name('register.submit')->middleware('guest');

    Route::get('login', 'loginPage')->name('login')->middleware('guest');
    Route::post('login', 'loginLogic')->name('login.submit')->middleware('guest');

    Route::get('logout', 'logout')->name('logout')->middleware('auth');
    Route::patch('update-profile', 'updateProfile')->name('update-profile')->middleware('auth');
    Route::get('/search-users', 'search')->name('users.search');
    Route::get('user/{user}', 'show')->name('user.profile')->middleware('auth');
});

//Posting routes
Route::controller(PostController::class)->group(function () {
    Route::get('create-post', 'index')->name('create-post')->middleware('auth');
    Route::post('create-post', 'store')->name('create-post.submit')->middleware('auth');
    Route::get('posts/{post}', 'show')->name('posts.show')->middleware('auth');

    // Route::get('edit-post/{id}', 'edit')->name('edit-post')->middleware('auth');
    // Route::put('edit-post/{id}', 'update')->name('edit-post.submit')->middleware('auth');

    Route::delete('delete-post/{id}', 'destroy')->name('delete-post')->middleware('auth');
    Route::post('report-post/{id}', 'report')->name('report-post')->middleware('auth');
    Route::post('mark-as-not-interested/{id}', 'markAsNotInterested')->name('mark-as-not-interested')->middleware('auth');
    Route::post('/posts/share',  'share')->name('post.share')->middleware('auth');
})->middleware('auth');

// Route for liking and unliking posts
Route::controller(LikeController::class)->group(function () {
    Route::post('/posts/{post}/like', 'toggleLike')->name('post.like');
})->middleware('auth');



Route::post('/posts/{post}/share', function ($post) {
    return redirect()->back();
})->name('post.share');

Route::post('/posts/{post}/bookmark', function ($post) {
    return redirect()->back();
})->name('post.bookmark');

// Route for user profile
Route::get('/user/{user}', [ProfileController::class, 'index'])->middleware('auth')->name('user.profile');

//Route for notifications
Route::controller(NotificationController::class)->middleware('auth')->prefix('notifications')->group(function () {
    Route::get('/', 'index')->name('notifications.index');
    Route::get('/read/{id}', 'markAsRead')->name('notifications.read');
});



Route::controller(CommentController::class)->prefix('posts/{post}')->middleware('auth')->group(function () {
    Route::post('/comments', 'store')->name('comment.store');
    Route::get('/comments', 'index')->name('post.comments');
    Route::post('/comments/{comment}/reply', 'replyStore')->name('comment.reply.store')->middleware('auth');
});


Route::middleware('auth')->group(function () {
    Route::post('/toggle-follow/{id}', [FollowController::class, 'toggleFollow'])->middleware('auth')->name('toggle.follow');
    Route::delete('/user/{user}/unfollow', [FollowController::class, 'unfollow'])->name('user.unfollow');
});

Route::controller(StoryController::class)
    ->prefix('stories')
    ->middleware('auth')
    ->group(function () {
        Route::post('/', 'store')->name('stories.store');
        Route::get('/', 'index')->name('stories.index');
        Route::get('/user/{user}', 'fetchUserStories')->name('stories.fetchUserStories');
        Route::get('/all', 'fetchAllActiveStories')->name('stories.fetchAllActiveStories'); // 👈 this is the new one
    });


// Marketplace routes
Route::controller(MarketPlaceController::class)->prefix('marketplace')->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('marketshowroom');
    Route::get('/new-listing', 'newListing')->name('marketplace.newlisting');
    Route::post('/store', 'store')->name('marketplace.store');
    Route::get('/item/{item}', 'show')->name('marketplace.show');
    // The new route to fetch item data as JSON, using the group's prefix and controller.
    Route::get('/items/{item}/data', 'getItemData');
});

Route::prefix('admin/users')->controller(UserManagementController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('admin.users.index');
    Route::get('/{user}', 'show')->name('admin.users.show');
    Route::patch('/{user}/block', 'block')->name('admin.users.block');
    Route::patch('/{user}/unblock', 'unblock')->name('admin.users.unblock');
    Route::patch('/{user}/verify', 'verify')->name('admin.users.verify');
    Route::patch('/{user}/suspend', 'suspend')->name('admin.users.suspend');
    Route::patch('/{user}/unsuspend', 'unsuspend')->name('admin.users.unsuspend');
    Route::delete('/{user}', 'destroy')->name('admin.users.destroy');
});
Route::controller(ReportController::class)->prefix('reports')->middleware(['auth'])->group(function () {
    Route::post('/users/{user}/report', 'store')->name('users.report');
    Route::post('/posts/{post}/report', 'storePost')->name('posts.report');
    Route::get('/', 'index')->name('admin.reports.index');
    Route::patch('/{report}/resolve', 'resolve')->name('reports.resolve');
    Route::patch('/post/{report}/resolve', 'resolvePost')->name('reports.resolve.post');
});
