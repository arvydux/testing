<?php

require_once 'power-consumption.php';

class LifeSupportRating extends PowerConsumption
{
    public function showOxygenValue(): void
    {
        $diagnosticReport = $this->getArrayOfBitsFromFile($this->path);
        $oxygenGeneratorRating = $this->getOxygenGeneratorRating($diagnosticReport);
        $co2ScrubberRating = $this->getCo2ScrubberRating($diagnosticReport);
        $result = $this->getLifeSupportRatingValue($oxygenGeneratorRating, $co2ScrubberRating);

        echo "The life support rating of the submarine: " . $result . PHP_EOL;
    }

    private function getOxygenGeneratorRating($binaryReport): string
    {
        $rowAmount = count($binaryReport);
        $columnAmount = count($binaryReport[0]);

        for ($col = 0; $col < $columnAmount; $col++) {
            $columnOfBits = [];
            for ($row = 0; $row < $rowAmount; $row++) {
                $columnOfBits[] = $binaryReport[$row][$col];
            }
            $mostCommonBit = $this->getMostCommonBitFromPosition($columnOfBits);
            $deletedRows = $this->removeRowOfNumbersByMostCommonBitCriteria($binaryReport, $col, $mostCommonBit);
            $rowAmount -= $deletedRows;
            if (count($binaryReport) === 1) {
                break;
            }
        }

        return implode($binaryReport[0]);
    }

    private function getCo2ScrubberRating($binaryReport): string
    {
        $rowAmount = count($binaryReport);
        $columnAmount = count($binaryReport[0]);

        for ($col = 0; $col < $columnAmount; $col++) {
            $columnOfBits = [];
            for ($row = 0; $row < $rowAmount; $row++) {
                $columnOfBits[] = $binaryReport[$row][$col];
            }
            $mostCommonBit = $this->getLeastCommonBitFromPosition($columnOfBits);
            $deletedRows = $this->removeRowOfNumbersByLeastCommonBitCriteria($binaryReport, $col, $mostCommonBit);
            $rowAmount -= $deletedRows;
            if (count($binaryReport) === 1) {
                break;
            }
        }

        return implode($binaryReport[0]);
    }

    private function getLeastCommonBitFromPosition(array $bitsOfPositions): string
    {
        $counts = array_count_values($bitsOfPositions);
        krsort($counts);
        $maxValue = max($counts);

        return array_search($maxValue, $counts);
    }

    private function removeRowOfNumbersByMostCommonBitCriteria(
        array &$binaryReport,
        int $position,
        bool $mostCommonBit
    ): int {
        $deletedRows = 0;
        foreach ($binaryReport as $key => $row) {
            if ($row[$position] != $mostCommonBit) {
                unset($binaryReport[$key]);
                $deletedRows++;
            }
        }
        $binaryReport = array_values($binaryReport);

        return $deletedRows;
    }

    private function removeRowOfNumbersByLeastCommonBitCriteria(
        array &$binaryReport,
        int $position,
        bool $mostCommonBit
    ): int {
        $deletedRows = 0;
        foreach ($binaryReport as $key => $row) {
            if ($row[$position] == $mostCommonBit) {
                unset($binaryReport[$key]);
                $deletedRows++;
            }
        }
        $binaryReport = array_values($binaryReport);

        return $deletedRows;
    }

    private function getLifeSupportRatingValue($OxygenGeneratorRating, $Co2ScrubberRating): int
    {
        return bindec($OxygenGeneratorRating) * bindec($Co2ScrubberRating);
    }
}

$diagnostic = new LifeSupportRating;
$diagnostic->showPowerConsumptionValue();
$diagnostic->showOxygenValue();
