
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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nisn', 10)->unique();
            $table->string('nama');
            $table->string('kelas');
            $table->string('jenis_kelamin', 1);
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
