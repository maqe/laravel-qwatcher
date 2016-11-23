<?php

Route::get('tracks', 'Maqe\Qwatcher\TracksController@index');
Route::get('tracks/status/{status}', 'Maqe\Qwatcher\TracksController@getByStatus');
Route::get('tracks/jobname/{job_name}', 'Maqe\Qwatcher\TracksController@getByJobName');
Route::get('tracks/{id}', 'Maqe\Qwatcher\TracksController@show');

