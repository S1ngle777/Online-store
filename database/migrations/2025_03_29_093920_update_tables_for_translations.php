<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Необходимо зарегистрировать типы Doctrine для изменения типов колонок
        if (!Type::hasType('json')) {
            Type::addType('json', \Doctrine\DBAL\Types\JsonType::class);
        }
        
        $schemaBuilder = Schema::getConnection()->getDoctrineSchemaManager();
        $schemaBuilder->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'json');

        // Продукты
        Schema::table('products', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
        });
        
        // Категории
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
        });
        
        // Посты блога
        Schema::table('posts', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('content')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем обратно к строковому типу
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->change();
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->change();
        });
        
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('content')->change();
        });
    }
};
