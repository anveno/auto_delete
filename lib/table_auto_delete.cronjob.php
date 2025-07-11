<?php

class rex_cronjob_table_auto_delete extends rex_cronjob
{
    public function execute()
    {
        $sql = rex_sql::factory();
        $sql->setQuery('DELETE FROM ' . $this->getParam('rex_table') . ' WHERE ' . (string) $this->getParam('field') . ' < DATE_SUB(NOW(), INTERVAL ' . (int) $this->getParam('interval') . ' MONTH)');

        $this->setMessage('Datensätze in der Tabelle ' . $this->getParam('rex_table') . ' gelöscht, die älter als ' . $this->getParam('interval') . ' Monate waren.');
        return true;
    }

    public function getTypeName()
    {
        return rex_i18n::msg('auto_delete_table');
    }

    public function getParamFields()
    {
        // Eingabefelder des Cronjobs definieren
        $fields = [
            [
                'label' => rex_i18n::msg('auto_delete_table_cronjob_rex_table_label'),
                'name' => 'rex_table',
                'type' => 'select',
                'options' => array_column(rex_sql::factory()->getArray('SELECT TABLE_NAME as id, TABLE_NAME as name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME LIKE "rex_%"'), 'id', 'name'),
                'notice' => rex_i18n::msg('auto_delete_table_cronjob_rex_table_notice'),
            ],
            [
                'label' => rex_i18n::msg('auto_delete_table_cronjob_field_label'),
                'name' => 'field',
                'type' => 'text',
                'default' => 'createdate',
                'notice' => rex_i18n::msg('auto_delete_table_cronjob_field_notice'),
            ],
            [
                'label' => rex_i18n::msg('auto_delete_table_cronjob_interval_label'),
                'name' => 'interval',
                'default' => '6',
                'type' => 'text',
                'notice' => rex_i18n::msg('auto_delete_table_cronjob_interval_notice'),
            ],
        ];

        return $fields;
    }
}
