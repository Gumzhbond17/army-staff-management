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
        Schema::create('employees', function (Blueprint $table) {
            // ✅ Standard Laravel — auto-increment BIGINT UNSIGNED
            $table->id();
            // ໝວດ I: ຂໍ້ມູນທົ່ວໄປ
            $table->enum('gender', ['ຊາຍ', 'ຍິງ'])->nullable();
            $table->string('full_name');
            $table->unsignedInteger('unit_id')->comment('ກອງປະຈຳ');
            $table->date('dob')->nullable()->comment('ວັນເດືອນປີເກີດ');
            $table->string('party_duty')->nullable()->comment('ໜ້າທີ່ຮັບຜິດຊອບພັກ');
            $table->string('command_duty')->nullable()->comment('ໜ້າທີ່ຮັບຜິດຊອບບັນຊາ');
            $table->string('officer_code', 12)->nullable()->comment('ລະຫັດນາຍທະຫານ');
            $table->string('id_card_number', 25)->nullable()->comment('ລະຫັດບັດປະຈຳຕົວ');
            $table->unsignedInteger('work_status_id')->comment('ສະຖານະການເຮັດວຽກ');
            $table->enum('blood_group', ['A', 'B', 'O', 'AB'])->nullable();

            // ໝວດ II: ສະຖານທີ່ເກີດ
            $table->string('birth_village', 150)->nullable();
            $table->foreignId('birth_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('birth_province_id')->nullable()->constrained('provinces')->nullOnDelete();

            // ໝວດ III: ທີ່ຢູ່ປັດຈຸບັນ
            $table->string('current_village', 150)->nullable();
            $table->foreignId('current_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('current_province_id')->nullable()->constrained('provinces')->nullOnDelete();

            // ໝວດ IV: ການສຶກສາ
            $table->string('culture_level', 100)->nullable()->comment('ລະດັບວັດທະນະທຳ');
            $table->string('theory_level', 100)->nullable()->comment('ລະດັບທິດສະດີ');
            $table->string('theory_from')->nullable()->comment('ຮຽນທິດສະດີຈາກ');
            $table->string('profession_level', 100)->nullable()->comment('ລະດັບວິຊາສະເພາະ');
            $table->string('profession_from')->nullable()->comment('ຮຽນວິຊາສະເພາະຈາກ');

            // ໝວດ V: ສັນຊາດ-ຊົນເຜົ່າ-ຊົນຊັ້ນ
            $table->string('nationality', 100)->nullable();
            $table->string('ethnicity_group', 100)->nullable()->comment('ເຊື້ອຊາດ');
            $table->string('tribe', 100)->nullable()->comment('ຊົນເຜົ່າ');
            $table->string('religion', 100)->nullable();
            $table->string('class_before_1975', 100)->nullable();
            $table->string('class_after_1975', 100)->nullable();

            // ໝວດ VI: ວັນທີສຳຄັນ
            $table->date('join_revolution_date')->nullable()->comment('ວັນເຂົ້າປະຕິວັດ');
            $table->date('join_army_date')->nullable()->comment('ວັນເຂົ້າທະຫານ');
            $table->date('candidate_party_date')->nullable()->comment('ວັນເຂົ້າພັກສຳຮອງ');
            $table->date('full_party_date')->nullable()->comment('ວັນເຂົ້າພັກສົມບູນ');
            $table->date('current_rank_date')->nullable()->comment('ວັນໄດ້ຊັ້ນປັດຈຸບັນ');

            // ໝວດ VII: ຄອບຄົວ
            $table->string('parents_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->integer('children_count')->default(0);

            // ໝວດ VII.b: ປະຫວັດ ແລະ ວິໄນ
            $table->text('previous_units')->nullable()->comment('ກອງເກົ່າທີ່ເຄີຍສັງກັດ');
            $table->text('discipline_record')->nullable()->comment('ປະຫວັດການວິໄນ');

            // ໝວດ VIII: ປະຫວັດ ແລະ ຮູບ
            $table->longText('biography')->nullable()->comment('ປະຫວັດການເຄື່ອນໄຫວ ແຕ່ອາຍຸ 8 ປີ');
            $table->longText('photo')->nullable()->comment('Base64 ຫຼື URL ຮູບຖ່າຍ');

            $table->unsignedInteger('created_by')->comment('ຜູ້ສ້າງຂໍ້ມູນ');
            $table->unsignedInteger('updated_by')->comment('ຜູ້ແກ້ໄຂຂໍ້ມູນ');
            $table->timestamps();

            // Indexes
            $table->index('full_name', 'idx_full_name');
            $table->index('officer_code', 'idx_officer_code');
            $table->index('id_card_number', 'idx_id_card');
            $table->index('unit_id', 'idx_unit');
            $table->index('work_status_id', 'idx_work_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
