<?php

use App\enums\Role;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guild_members', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Guild::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('role', Role::values());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guild_members');
    }
};
