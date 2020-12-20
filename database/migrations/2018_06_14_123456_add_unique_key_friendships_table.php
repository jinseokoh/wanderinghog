<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueKeyFriendshipsTable extends Migration
{
    public function up()
    {
        $db = \DB::connection();
        if ($db->getDriverName() === 'mysql') {
            \DB::statement("ALTER TABLE `friendships` ADD UNIQUE `friendships_sender_id_recipient_id_unique` (`sender_id`, `recipient_id`), ALGORITHM=INPLACE, LOCK=NONE");
        } else {
            Schema::table('friendships', function (Blueprint $table) {
                $table->unique(['sender_id', 'recipient_id'], 'friendships_sender_id_recipient_id_unique');
            });
        }
    }

    public function down()
    {
        Schema::table('friendships', function (Blueprint $table) {
            $table->dropUnique('friendships_sender_id_recipient_id_unique');
        });
    }
}
