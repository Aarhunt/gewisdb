<?php

declare(strict_types=1);

namespace Database\Model\SubDecision;

use Application\Model\Enums\AppLanguages;
use Application\Model\Enums\OrganTypes;
use Database\Model\SubDecision;
use Database\Model\Trait\FormattableDateTrait;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Laminas\Translator\TranslatorInterface;
use ValueError;

#[Entity]
class OrganRegulation extends SubDecision
{
    use FormattableDateTrait;

    /**
     * Abbreviation of the organ.
     */
    #[Column(type: 'string')]
    protected string $abbr;

    /**
     * Type of the organ.
     */
    #[Column(
        type: 'string',
        enumType: OrganTypes::class,
    )]
    protected OrganTypes $organType;

    /**
     * Version of the regulation.
     */
    #[Column(
        type: 'string',
        length: 32,
    )]
    protected string $version;

    /**
     * Date of the regulation.
     */
    #[Column(type: 'date')]
    protected DateTime $date;

    /**
     * If the regulation was approved.
     */
    #[Column(type: 'boolean')]
    protected bool $approval;

    /**
     * If there were changes made.
     */
    #[Column(type: 'boolean')]
    protected bool $changes;

    /**
     * Get the type.
     */
    public function getOrganType(): OrganTypes
    {
        return $this->organType;
    }

    /**
     * Set the organ type
     */
    public function setOrganType(OrganTypes $organType): void
    {
        $this->organType = $organType;
    }

    /**
     * Get the abbreviation.
     */
    public function getAbbr(): string
    {
        return $this->abbr;
    }

    /**
     * Set the abbreviation.
     */
    public function setAbbr(string $abbr): void
    {
        $this->abbr = $abbr;
    }

    /**
     * Get the version.
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set the version.
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * Get the date.
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Set the date.
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * Get approval status.
     */
    public function getApproval(): bool
    {
        return $this->approval;
    }

    /**
     * Set approval status.
     */
    public function setApproval(bool $approval): void
    {
        $this->approval = $approval;
    }

    /**
     * Get if changes were made.
     */
    public function getChanges(): bool
    {
        return $this->changes;
    }

    /**
     * Set if changes were made.
     */
    public function setChanges(bool $changes): void
    {
        $this->changes = $changes;
    }

    protected function getTranslatedTemplate(
        TranslatorInterface $translator,
        AppLanguages $language,
    ): string {
        return $translator->translate(
            'Het %DOCUMENTTYPE% van %NAME% door %AUTHOR%, versie %VERSION% van %DATE% wordt %APPROVAL%%CHANGES%.',
            locale: $language->getLangParam(),
        );
    }

    public function getTranslatedContent(
        TranslatorInterface $translator,
        AppLanguages $language,
    ): string {
        if (
            OrganTypes::Committee === $this->getOrganType()
            || OrganTypes::KCC === $this->getOrganType()
        ) {
            $documentType = $translator->translate('commissiereglement', locale: $language->getLangParam());
        } elseif (OrganTypes::Fraternity === $this->getOrganType()) {
            $documentType = $translator->translate('dispuutsreglement', locale: $language->getLangParam());
        } else {
            throw new ValueError();
        }

        $replacements = [
            '%NAME%' => $this->getAbbr(),
            '%AUTHOR%' => null === $this->getMember()
                ? $translator->translate('onbekend', locale: $language->getLangParam())
                : $this->getMember()->getFullName(),
            '%DOCUMENTTYPE%' => $documentType,
            '%VERSION%' => $this->getVersion(),
            '%DATE%' => $this->formatDate($this->getDate(), $language),
            '%APPROVAL%' => $this->getApproval()
                ? $translator->translate('goedgekeurd', locale: $language->getLangParam())
                : $translator->translate('afgekeurd', locale: $language->getLangParam()),
            '%CHANGES%' => $this->getApproval() && $this->getChanges()
                ? $translator->translate(' met genoemde wijzigingen', locale: $language->getLangParam())
                : '',
        ];

        return $this->replaceContentPlaceholders($this->getTranslatedTemplate($translator, $language), $replacements);
    }
}
