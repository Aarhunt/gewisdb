<?php

namespace Database\Form;

use Zend\Filter\StringToLower;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class Address extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'country',
            'type' => 'text',
            'options' => array(
                'label' => 'Land',
                'value' => 'netherlands'
            )
        ));

        $this->add(array(
            'name' => 'street',
            'type' => 'text',
            'options' => array(
                'label' => 'Straat'
            )
        ));

        $this->add(array(
            'name' => 'number',
            'type' => 'text',
            'options' => array(
                'label' => 'Huisnummer'
            )
        ));

        $this->add(array(
            'name' => 'postalCode',
            'type' => 'text',
            'options' => array(
                'label' => 'Postcode'
            )
        ));

        $this->add(array(
            'name' => 'city',
            'type' => 'text',
            'options' => array(
                'label' => 'Stad'
            )
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => 'text',
            'options' => array(
                'label' => 'Telefoonnummer'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Wijzig adres'
            )
        ));
    }

    /**
     * Specification of input filter.
     */
    public function getInputFilterSpecification(): array
    {
        return array(
            'country' => array(
                'required' => true,
                'filters' => array(
                    array('name' => StringToLower::class)
                ),
                'validators' => array(
                    array(
                        'name' => StringLength::class,
                        'options' => array(
                            'min' => 2,
                            'max' => 32
                        )
                    )
                )
            ),
            'street' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => StringLength::class,
                        'options' => array(
                            'min' => 2,
                            'max' => 32
                        )
                    )
                )
            ),
            'number' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => Regex::class,
                        'options' => array(
                            'pattern' => '/^[0-9]+[a-zA-Z]*/'
                        )
                    )
                )
            ),
            'postalCode' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => StringLength::class,
                        'options' => array(
                            'min' => 2,
                            'max' => 16
                        )
                    )
                )
            ),
            'city' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => StringLength::class,
                        'options' => array(
                            'min' => 2,
                            'max' => 32
                        )
                    )
                )
            )
            // TODO: phone number validation
        );
    }
}
