<?php 
use common\components\Migration;

class m180208_135218_forum_host extends Migration
{
    public function safeUp()
    {
        try {
            $this->execute("UPDATE forum.phpbb_config SET config_value='forum.sdot.bilimal.kz' WHERE config_name ='server_name'");
            $this->execute("UPDATE forum.phpbb_config SET config_value='forum.sdot.bilimal.kz' WHERE config_name ='cookie_domain'");
            $this->execute("UPDATE forum.phpbb_config SET config_value='https://' WHERE config_name ='server_protocol'");
        } catch (Exception $e) {

        }

    }

    public function safeDown()
    {
        echo "m180208_135218_forum_host cannot be reverted.\n";
        return false;
    }
}
