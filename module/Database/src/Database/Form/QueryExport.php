<?php

namespace Database\Form;


class QueryExport extends Query
{

    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'type',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    'csvex' => 'CSV voor Excel'
                )
            )
        ));

        $this->get('submit')->setAttribute('value', 'export');
        $this->get('submit')->setLabel('Export');
    }
}
