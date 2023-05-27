<?php

declare(strict_types=1);

namespace Database\Form;

use Database\Model\MailingList as MailingListModel;
use DateTime;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Mvc\I18n\Translator as MvcTranslator;
use Laminas\Validator\Identical;

use function sprintf;

class MemberRenewal extends Form implements InputFilterProviderInterface
{
    /** @var MailingListModel[] $lists */
    protected array $lists;

    public function __construct(
        protected readonly MvcTranslator $translator,
    ) {
        parent::__construct();

        $this->add([
            'name' => 'lastName',
            'type' => Text::class,
            'options' => [
                'label' => $translator->translate('Last Name'),
            ],
            'attributes' => [
                'readonly' => true,
            ],
        ]);

        $this->add([
            'name' => 'middleName',
            'type' => Text::class,
            'options' => [
                'label' => $translator->translate('Last Name Prepositional Particle'),
            ],
            'attributes' => [
                'readonly' => true,
            ],
        ]);

        $this->add([
            'name' => 'initials',
            'type' => Text::class,
            'options' => [
                'label' => $translator->translate('Initial(s)'),
            ],
            'attributes' => [
                'readonly' => true,
            ],
        ]);

        $this->add([
            'name' => 'firstName',
            'type' => Text::class,
            'options' => [
                'label' => $translator->translate('First Name'),
            ],
            'attributes' => [
                'readonly' => true,
            ],
        ]);

        $this->add([
            'name' => 'email',
            'type' => Email::class,
            'options' => [
                'label' => $translator->translate('E-mail Address'),
            ],
        ]);

        $this->add([
            'name' => 'newExpiration',
            'type' => Date::class,
            'options' => [
                'label' => $translator->translate('Renew until'),
            ],
            'attributes' => [
                'readonly' => true,
            ],
        ]);

        $this->add([
            'name' => 'supremum',
            'type' => Checkbox::class,
            'options' => [
                'label' => $translator->translate('I\'d like to receive the Supremum magazine 3 times a year'),
            ],
        ]);

        $this->add([
            'name' => 'privacy',
            'type' => Checkbox::class,
            'options' => [
                'label' => sprintf(
                    // phpcs:ignore -- user-visible strings should not be split
                    $translator->translate('I have read the privacy statement of %s and consent to the processing of my data.'),
                    $translator->translate('Gemeenschap van Wiskunde en Informatica Studenten'),
                ),
            ],
        ]);

        $this->add([
            'name' => 'agreed',
            'type' => Checkbox::class,
            'options' => [
                // phpcs:ignore -- user-visible strings should not be split
                'label' => $translator->translate('I am familiar with the contents of the Articles of Association and the Internal Regulations of GEWIS and I would like to renew my status as a graduate'),
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'value' => $translator->translate('Renew'),
            ],
        ]);
    }

    public function setNewExpiration(DateTime $date): void
    {
        $this->get('newExpiration')->setValue($date);
    }

    /**
     * Specification of input filter.
     */
    public function getInputFilterSpecification(): array
    {
        return [
            'agreed' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Identical::class,
                        'options' => [
                            'token' => '1',
                            'messages' => [
                                Identical::NOT_SAME => $this->translator->translate(
                                    'You have to agree to the Articles of Association and the Internal Regulations',
                                ),
                            ],
                        ],
                    ],
                ],
            ],
            'privacy' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Identical::class,
                        'options' => [
                            'token' => '1',
                            'messages' => [
                                Identical::NOT_SAME => $this->translator->translate(
                                    'You have to consent to processing your data',
                                ),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
