<?php

use App\Models\{
    User,
    Preference,
    Course,
    Permission,
    Image,
    Comment,
    Tag
};
use Illuminate\Support\Facades\Route;

Route::get('/many-to-many-polymorphic', function() {
    // Tag::create(['name' => 'tag1', 'color' => 'blue']);
    // Tag::create(['name' => 'tag2', 'color' => 'red']);
    // Tag::create(['name' => 'tag3', 'color' => 'green']);

    // $course = Course::first();

    // $course->tags()->attach(2);

    // dd($course->tags);

    $tag = Tag::where('name', 'tag3')->first();
    dd($tag->users);
});

Route::get('/one-to-many-polymorphic', function () {
    //$course = Course::first();

    // $course->comments()->create([
    //     'subject' => 'Novo Comentário 2',
    //     'content' => 'Apenas (2) um comentário legal',
    // ]);

    // dd($course->comments);

    $comment = Comment::find(1);

    dd($comment->commentable);
});

Route::get('/one-to-one-polymorphic', function () {
    $user = User::first();

    $data = ['path' => 'path/nome-imageeee.png'];

    //$user->image->delete();

    if ($user->image) {
        $user->image->update($data);
    }
    else {
        // $user->image()->save(new Image($data));
        $user->image()->create($data);
    }

    dd($user->image->path);
});

Route::get('/many-to-many-pivot', function() {
    $user = User::with('permissions')->find(1);
    // $user->permissions()->attach([
    //     2 => ['active' => false],
    //     3 => ['active' => false],
    // ]);


    echo "<b>{$user->name}</b><br>";
    foreach ($user->permissions as $permission) {
        echo "{$permission->name} - {$permission->pivot->active} <br>";
    }
});

Route::get('/many-to-many', function() {
    // dd(Permission::create(['name' => 'menu_03']));

    $user = User::with('permissions')->find(1);

    // $permission = Permission::find(1);
    // $user->permissions()->save($permission);
    // $user->permissions()->saveMany([
    //     Permission::find(1),
    //     Permission::find(2),
    //     Permission::find(3),
    // ]);
    // $user->permissions()->sync([2]); exclui as permissões diferentes da indicada
    //$user->permissions()->attach([1, 3]); //inclui as permissões específicas
    $user->permissions()->detach([1, 3]); // remove as permissões indicadas

    $user->refresh();
    
    dd($user->permissions);
});

Route::get('/one-to-many', function () {
    //$course = Course::create(['name' => 'Curso de Laravel']);
    $course = Course::with('modules.lessons')->first(); //ganho de desempenho com o uso de with, fazendo apenas 2 consultas no banco

    //dd($course);
    echo $course->name;
    echo '<br>';
    foreach ($course->modules as $module) {
        echo "Módulo {$module->name} <br>";

        foreach ($module->lessons as $lesson) {
            echo "Aula {$lesson->name} <br>";
        }
        
    }

    $data = [
        'name' => 'Módulo x2'
    ];
    //$course->modules()->create($data);


    // $course->modules()->get();
    $modules = $course->modules;

    dd($modules);
});

Route::get('/one-to-one', function () {
    $user = User::with('preference')->find(2);
    $preference = $user->preference;

    $data = [
        'background_color' => '#fff',
    ];

    if ($user->preference){
        $user->preference->update($data);
    }   else {
        // $user->preference()->create($data);
        $preference = new Preference($data);
        $user->preference()->save($preference);
    }

    $user->refresh();
    var_dump($user->preference);

    $user->preference->delete();
    $user->refresh();

    dd($user->preference);
});

Route::get('/', function () {
    return view('welcome');
});
