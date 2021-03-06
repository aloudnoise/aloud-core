<?php
namespace aloud_core\common\components;

interface Filterable
{
    /**
     * Переопределяйте данный метод в своей модели и применяйте к $query любый условия и тд нужные для выборки
     * @param ActiveQuery $query
     * @return mixed
     */
    public function applyFilter(&$query);
    public function filterAttributes();

    public function applyFilterOne(&$query);
    public function filterOneAttributes();
}

?>