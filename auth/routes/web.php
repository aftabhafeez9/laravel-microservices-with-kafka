<?php
use Illuminate\Support\Facades\Route;
use App\Services\KafkaProducer;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/send-hello', function() {
    $producer = new KafkaProducer();
    $producer->publish('hello-world', 'Hello from Auth service!');
    return "Hello event sent from Auth!";
});
