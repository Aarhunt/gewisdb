<?php

declare(strict_types=1);

namespace Database\Model\SubDecision\Key;

use Database\Model\SubDecision;
use Database\Model\Trait\FormattableDateTrait;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity]
class Withdrawal extends SubDecision
{
    use FormattableDateTrait;

    /**
     * Reference to the granting of a keycode.
     */
    #[OneToOne(
        targetEntity: Granting::class,
        inversedBy: 'withdrawal',
    )]
    #[JoinColumn(
        name: 'r_meeting_type',
        referencedColumnName: 'meeting_type',
    )]
    #[JoinColumn(
        name: 'r_meeting_number',
        referencedColumnName: 'meeting_number',
    )]
    #[JoinColumn(
        name: 'r_decision_point',
        referencedColumnName: 'decision_point',
    )]
    #[JoinColumn(
        name: 'r_decision_number',
        referencedColumnName: 'decision_number',
    )]
    #[JoinColumn(
        name: 'r_sequence',
        referencedColumnName: 'sequence',
    )]
    protected Granting $granting;

    /**
     * When the granted keycode is prematurely revoked.
     */
    #[Column(type: 'date')]
    protected DateTime $withdrawnOn;

    /**
     * Get the granting of the keycode.
     */
    public function getGranting(): Granting
    {
        return $this->granting;
    }

    /**
     * Set the granting of the keycode.
     */
    public function setGranting(Granting $granting): void
    {
        $this->granting = $granting;
    }

    /**
     * Get the date.
     */
    public function getWithdrawnOn(): DateTime
    {
        return $this->withdrawnOn;
    }

    /**
     * Set the date.
     */
    public function setWithdrawnOn(DateTime $withdrawnOn): void
    {
        $this->withdrawnOn = $withdrawnOn;
    }

    protected function getTemplate(): string
    {
        return 'De sleutelcode van %GRANTEE% van GEWIS wordt ingetrokken vanaf %WITHDRAWAL%.';
    }

    protected function getAlternativeTemplate(): string
    {
        return 'The key code of %GRANTEE% of GEWIS is withdrawn from %WITHDRAWAL%.';
    }

    public function getContent(): string
    {
        $replacements = [
            '%GRANTEE%' => $this->getGranting()->getMember()->getFullName(),
            '%WITHDRAWAL%' => $this->formatDate($this->getWithdrawnOn()),
        ];

        return $this->replaceContentPlaceholders($this->getTemplate(), $replacements);
    }

    public function getAlternativeContent(): string
    {
        $replacements = [
            '%GRANTEE%' => $this->getGranting()->getMember()->getFullName(),
            '%WITHDRAWAL%' => $this->formatDate($this->getWithdrawnOn(), 'en_GB'),
        ];

        return $this->replaceContentPlaceholders($this->getAlternativeTemplate(), $replacements);
    }
}
