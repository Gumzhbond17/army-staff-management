<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed the current hard-coded texts so nothing changes visually
        // until an admin edits them.
        $now = now();
        DB::table('site_settings')->insert([
            ['key' => 'ministry_name',   'value' => 'ກະຊວງປ້ອງກັນປະເທດ',        'created_at' => $now, 'updated_at' => $now],
            ['key' => 'department_name', 'value' => 'ກົມຄຸ້ມຄອງພະນັກງານ',        'created_at' => $now, 'updated_at' => $now],
            ['key' => 'welcome_message', 'value' => 'ຍິນດີຕ້ອນຮັບເຂົ້າສູ່ໂປຣແກມ', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
