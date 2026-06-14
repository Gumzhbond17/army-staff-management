<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Make created_by / updated_by nullable so inserts don't fail
        //    when the controller hasn't supplied them yet (e.g. seeding).
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->default(null)->change();
            $table->unsignedInteger('updated_by')->nullable()->default(null)->change();
        });

        // 2. Rename children_count → child_count to match model & controller
        Schema::table('employees', function (Blueprint $table) {
            $table->renameColumn('children_count', 'child_count');
        });

        // 3. Fix gender enum in employees: Lao labels → English values used by
        //    the form and validation rules ('in:male,female')
        DB::statement("ALTER TABLE employees MODIFY COLUMN gender ENUM('male','female') NULL");

        // 4. Same fix for employee_children
        DB::statement("ALTER TABLE employee_children MODIFY COLUMN gender ENUM('male','female') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE employee_children MODIFY COLUMN gender ENUM('ຊາຍ','ຍິງ') NULL");
        DB::statement("ALTER TABLE employees MODIFY COLUMN gender ENUM('ຊາຍ','ຍິງ') NULL");

        Schema::table('employees', function (Blueprint $table) {
            $table->renameColumn('child_count', 'children_count');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable(false)->change();
            $table->unsignedInteger('updated_by')->nullable(false)->change();
        });
    }
};
