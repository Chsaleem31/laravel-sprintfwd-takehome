<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{TeamController, MemberController, ProjectController};
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
    Route::resource('teams', TeamController::class);
    Route::resource('members', MemberController::class);
    Route::resource('projects', ProjectController::class);
});

Route::get('api/teams/{teamId}/members', [TeamController::class, 'getMembers']);
Route::get('api/projects/{projectId}/members', [ProjectController::class, 'getMembers']);
Route::post('api/projects/{projectId}/add-member/{memberId}', [ProjectController::class, 'addMember']);
// Route::get('api/projects/{projectId}/members', [ProjectController::class, 'getProjectMembers']);

Route::patch('/api/members/{id}/update-team', [MemberController::class, 'updateTeam']);


// Route::put('api/member/{id}/update_team', [MemberController::class, 'updateTeam']);
// Route::get('/teams/{teamId}/members', 'MemberController@getTeamMembers');
// Route::post('projects/{projectId}/members/{memberId}', [MemberController::class, 'addMemberToProject']);
