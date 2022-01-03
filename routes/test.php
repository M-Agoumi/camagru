<?php

use Simfa\Framework\Router;

Router::get('/', function(){return 'Home';})->name('home.index');
Router::get('/contact', function(){return 'contact us';})->name('contact.us');
Router::get('/camera', function(){return 'camera';})->name('camera.index');
Router::get('/profile', function(){return 'profile';})->name('user.profile');
Router::get('/logout', function(){return 'logout';})->name('app.logout');
