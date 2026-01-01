<?php
declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('is_capital')->default(false);
            $table->decimal('extension', 10, 2);
            $table->unsignedMediumInteger('population')->nullable();
            $table->string('region');
            $table->string('capital');
            $table->string('code', 3)->unique();
            $table->timestamps();

            $table->index(['name', 'code'], 'departments_name_code_index');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
