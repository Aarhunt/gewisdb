<?php

declare(strict_types=1);

namespace Database\Model\SubDecision;

use Database\Model\Decision;
use Database\Model\SubDecision;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use RuntimeException;

/**
 * Entity for undefined decisions.
 */
#[Entity]
class Other extends SubDecision
{
    /**
     * Reference to the link of a decision.
     */
    #[OneToOne(
        targetEntity: Decision::class,
        inversedBy: 'linkedTo',
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
        referencedColumnName: 'point',
    )]
    #[JoinColumn(
        name: 'r_decision_number',
        referencedColumnName: 'number',
    )]
    protected Decision $target;

    /**
     * Get the target.
     */
    public function getTarget(): Decision
    {
        return $this->target;
    }

    /**
     * Set the target.
     */
    public function setTarget(Decision $target): void
    {
        $this->target = $target;
    }

    /**
     * Textual content for the decision.
     */
    #[Column(type: 'text')]
    protected string $content;

    /**
     * Get the content.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the content.
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    protected function getTemplate(): string
    {
        throw new RuntimeException('Not implemented');
    }

    protected function getAlternativeTemplate(): string
    {
        throw new RuntimeException('Not implemented');
    }

    public function getAlternativeContent(): string
    {
        // No alternative content exists for a custom decision.
        return 'If you are reading this, the secretary has not done their job.';
    }
}
