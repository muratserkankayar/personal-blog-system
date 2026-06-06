<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\CommentController;
use App\Controllers\PostController;
use App\Controllers\ProfileController;

return [
    'GET' => [
        'home' => [PostController::class, 'index', []],
        'post.show' => [PostController::class, 'show', []],
        'login' => [AuthController::class, 'loginForm', ['requireGuest']],
        'register' => [AuthController::class, 'registerForm', ['requireGuest']],
        'profile' => [ProfileController::class, 'index', ['requireLogin']],
        'post.create' => [PostController::class, 'create', ['requireLogin']],
        'post.edit' => [PostController::class, 'edit', ['requireLogin']],
        'admin' => [AdminController::class, 'dashboard', ['requireAdmin']],
        'admin.categories' => [CategoryController::class, 'index', ['requireAdmin']],
        'admin.category.create' => [CategoryController::class, 'create', ['requireAdmin']],
        'admin.category.edit' => [CategoryController::class, 'edit', ['requireAdmin']],
    ],
    'POST' => [
        'login' => [AuthController::class, 'login', ['requireGuest']],
        'register' => [AuthController::class, 'register', ['requireGuest']],
        'logout' => [AuthController::class, 'logout', ['requireLogin']],
        'profile.password' => [ProfileController::class, 'changePassword', ['requireLogin']],
        'post.create' => [PostController::class, 'store', ['requireLogin']],
        'post.edit' => [PostController::class, 'update', ['requireLogin']],
        'post.delete' => [PostController::class, 'delete', ['requireLogin']],
        'comment.create' => [CommentController::class, 'store', ['requireLogin']],
        'admin.category.create' => [CategoryController::class, 'store', ['requireAdmin']],
        'admin.category.edit' => [CategoryController::class, 'update', ['requireAdmin']],
        'admin.category.delete' => [CategoryController::class, 'delete', ['requireAdmin']],
    ],
];
