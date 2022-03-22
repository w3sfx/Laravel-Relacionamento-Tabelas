<?php

use App\Models\{
    User,
    Preference,
    Course
};
use Illuminate\Support\Facades\Route;

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
