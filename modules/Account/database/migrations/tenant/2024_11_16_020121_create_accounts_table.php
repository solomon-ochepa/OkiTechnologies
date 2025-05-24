<?php

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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 16)->index();
            $table->integer('number')->nullable()->unique();
            $table->decimal('amount', 18)->default(0);
            $table->char('currency', 3)->nullable()->default('NGN');
            $table->boolean('primary')->nullable();
            $table->uuidMorphs('accountable');
            $table->nullableUuidMorphs('manageable');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'number', 'currency', 'accountable_type', 'accountable_id', 'manageable_type', 'manageable_id'], 'unique_account_name');
            $table->unique(['primary', 'number', 'currency', 'accountable_type', 'accountable_id', 'manageable_type', 'manageable_id'], 'unique_primary_account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
