<?php

namespace Report\Service;

use Doctrine\ORM\EntityManager;
use Report\Model\BoardMember as BoardMemberModel;

class Board
{
    /** @var EntityManager $emReport */
    private $emReport;

    /**
     * @param EntityManager $emReport
     */
    public function __construct(EntityManager $emReport)
    {
        $this->emReport = $emReport;
    }

    /**
     * Export board info.
     */
    public function generate()
    {
        $repo = $this->emReport->getRepository('Report\Model\SubDecision\Board\Installation');

        $installs = $repo->findAll();
        foreach ($installs as $install) {
            $boardMember = $install->getBoardMember();

            if (null === $boardMember) {
                $boardMember = new BoardMemberModel();
                $boardMember->setInstallationDec($install);
            }

            $boardMember->setMember($install->getMember());
            $boardMember->setFunction($install->getFunction());
            $boardMember->setInstallDate($install->getDate());

            $release = $install->getRelease();
            if (null !== $release) {
                $boardMember->setReleaseDate($release->getDate());
            }

            $discharge = $install->getDischarge();

            if (null !== $discharge) {
                $boardMember->setDischargeDate($discharge->getDecision()->getMeeting()->getDate());
            }

            $this->emReport->persist($boardMember);
        }

        $this->emReport->flush();
    }
}
