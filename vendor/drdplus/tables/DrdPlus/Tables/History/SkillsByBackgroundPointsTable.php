<?php
declare(strict_types=1);

namespace DrdPlus\Tables\History;

use DrdPlus\Codes\ProfessionCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\Integer\PositiveInteger;

/** see PPH page 39, bottom, @link https://pph.drdplus.info/#tabulka_dovednosti */
class SkillsByBackgroundPointsTable extends AbstractFileTable
{
    /**
     * @var array
     */
    private $originalColumnsHeader;

    /**
     * @return array|string[][][]
     */
    protected function getColumnsHeader(): array
    {
        if ($this->originalColumnsHeader === null) {
            $simplifiedColumnsHeader = parent::getColumnsHeader();
            $this->originalColumnsHeader = $this->getRebuiltOriginalColumnsHeader($simplifiedColumnsHeader);
        }

        return $this->originalColumnsHeader;
    }

    /**
     * @param array $simplifiedColumnsHeader
     * @return array
     */
    private function getRebuiltOriginalColumnsHeader(array $simplifiedColumnsHeader): array
    {
        $originalColumnsHeader = [];
        $professionsPattern = implode(
            '|',
            array_map(
                function ($professionName) {
                    return preg_quote($professionName, '~');
                },
                ProfessionCode::getPossibleValues()
            )
        );
        foreach ($simplifiedColumnsHeader as $simplifiedColumnName) {
            $originalColumnHeader = ['', ''];
            preg_match('~(?<profession>' . $professionsPattern . ')\s+(?<skillType>\w+)~', $simplifiedColumnName, $matches);
            $originalColumnHeader[0] = $matches['profession'];
            $originalColumnHeader[1] = $matches['skillType'];
            $originalColumnsHeader[] = $originalColumnHeader;
        }

        return $originalColumnsHeader;
    }

    /**
     * @return array
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        $professionsWithSkillTypes = [];
        foreach (ProfessionCode::getPossibleValues() as $professionCode) {
            foreach (SkillTypeCode::getPossibleValues() as $skillTypeCode) {
                $professionsWithSkillTypes["$professionCode $skillTypeCode"] = self::INTEGER;
            }
        }

        return $professionsWithSkillTypes;
    }

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [BackgroundPointsTable::BACKGROUND_POINTS];
    }

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/skills_by_background_points.csv';
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getFighterPhysicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPhysicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::FIGHTER));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @param ProfessionCode $professionCode
     * @return int
     */
    public function getPhysicalSkillPoints(PositiveInteger $backgroundPoints, ProfessionCode $professionCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSkillPoints($backgroundPoints, $professionCode, SkillTypeCode::getIt(SkillTypeCode::PHYSICAL));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @param ProfessionCode $professionCode
     * @param SkillTypeCode $skillTypeCode
     * @return int
     * @throws \DrdPlus\Tables\History\Exceptions\UnexpectedBackgroundPoints
     * @throws \DrdPlus\Tables\History\Exceptions\UnexpectedProfessionAndSkillTypeCombination
     */
    public function getSkillPoints(
        PositiveInteger $backgroundPoints,
        ProfessionCode $professionCode,
        SkillTypeCode $skillTypeCode
    ): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($backgroundPoints, "$professionCode $skillTypeCode");
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnexpectedBackgroundPoints("Given background points {$backgroundPoints} are out of range");
        } catch (RequiredColumnNotFound $requiredColumnNotFound) {
            throw new Exceptions\UnexpectedProfessionAndSkillTypeCombination(
                "Given profession '{$professionCode}' and skill type '{$skillTypeCode}' combination is not supported"
            );
        }
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getFighterPsychicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPsychicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::FIGHTER));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @param ProfessionCode $professionCode
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getPsychicalSkillPoints(PositiveInteger $backgroundPoints, ProfessionCode $professionCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSkillPoints($backgroundPoints, $professionCode, SkillTypeCode::getIt(SkillTypeCode::PSYCHICAL));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getFighterCombinedSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getCombinedSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::FIGHTER));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @param ProfessionCode $professionCode
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getCombinedSkillPoints(PositiveInteger $backgroundPoints, ProfessionCode $professionCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSkillPoints($backgroundPoints, $professionCode, SkillTypeCode::getIt(SkillTypeCode::COMBINED));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getThiefPhysicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPhysicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::THIEF));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getThiefPsychicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPsychicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::THIEF));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getThiefCombinedSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getCombinedSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::THIEF));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getRangerPhysicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPhysicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::RANGER));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getRangerPsychicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPsychicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::RANGER));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getRangerCombinedSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getCombinedSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::RANGER));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getWizardPhysicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPhysicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::WIZARD));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getWizardPsychicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPsychicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::WIZARD));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getWizardCombinedSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getCombinedSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::WIZARD));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getTheurgistPhysicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPhysicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::THEURGIST));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getTheurgistPsychicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPsychicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::THEURGIST));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getTheurgistCombinedSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getCombinedSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::THEURGIST));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getPriestPhysicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPhysicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::PRIEST));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getPriestPsychicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPsychicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::PRIEST));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getPriestCombinedSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getCombinedSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::PRIEST));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getCommonerPhysicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPhysicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::COMMONER));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getCommonerPsychicalSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getPsychicalSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::COMMONER));
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function getCommonerCombinedSkillPoints(PositiveInteger $backgroundPoints): int
    {
        return $this->getCombinedSkillPoints($backgroundPoints, ProfessionCode::getIt(ProfessionCode::COMMONER));
    }

}