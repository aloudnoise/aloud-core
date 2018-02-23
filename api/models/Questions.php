<?php
namespace api\models;

class Questions extends \common\models\Questions
{

    public function filterAttributes()
    {
        return ['thieme_id','name'];
    }

    public function applyFilter(&$query)
    {

        $query->byOrganization();
        $query->with([
            "answers"
        ]);

        if ($this->theme_id) {
            $query->andWhere([
                "theme_id" => $this->theme_id
            ]);
        }

        if ($this->name) {
            $search_request = str_replace("&", "", $this->name);
            $search_request = explode(" ", $search_request);
            $search_string = "";
            foreach ($search_request as $s) {
                $search_string .= "$s:*&";
            }
            $search_string = rtrim($search_string, "&");
            $search_cond = "to_tsvector('russian', questions.name) @@ to_tsquery(:search_string)";

            $query->andWhere($search_cond, [
                ":saerch_string" => $search_string
            ]);

        }

    }

    public function extraFields()
    {
        return ['answers'];
    }

}