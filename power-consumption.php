<?php

class PowerConsumption
{
    protected string $path = 'inputData.txt';

    public function showPowerConsumptionValue(): void
    {
        $diagnosticReport = $this->getArrayOfBitsFromFile($this->path);
        $bitsOfEachPositions = $this->getBitsOfEachPositions($diagnosticReport);
        $gammaRate = $this->getBitsOfGammaRate($bitsOfEachPositions);
        $epsilonRate = $this->getBitsOfEpsilonRate($gammaRate);
        $result = $this->getPowerConsumptionValue($gammaRate, $epsilonRate);

        echo "The power consumption of the submarine is: " . $result . PHP_EOL;
    }

    protected function getArrayOfBitsFromFile(string $path): array
    {
        $arrayOfBits = [];

        try {
            if (!file_exists($path)) {
                throw new Exception("File not found: " . $path);
            }

            $handle = fopen($path, "r");
            if (!$handle) {
                throw new Exception('File open failed.');
            }
            while (!feof($handle)) {
                $line = trim(fgets($handle));
                $arrayOfBits[] = str_split($line);
            }

            fclose($handle);
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }

        return $arrayOfBits;
    }

    protected function getMostCommonBitFromPosition(array $bitsOfPositions): string
    {
        $counts = array_count_values($bitsOfPositions);
        krsort($counts);
        $maxValue = max($counts);

        return array_search($maxValue, $counts);
    }

    private function getBitsOfEachPositions($binaryReport): array
    {
        $rowAmount = count($binaryReport);
        $columnAmount = count($binaryReport[0]);
        $arrayOfBitsFromColumn = [];

        for ($col = 0; $col < $columnAmount; $col++) {
            $columnOfBits = [];
            for ($row = 0; $row < $rowAmount; $row++) {
                $columnOfBits[] = $binaryReport[$row][$col];
            }
            $arrayOfBitsFromColumn[] = $columnOfBits;
        }

        return $arrayOfBitsFromColumn;
    }

    private function getBitsOfGammaRate(array $bitsOfEachPositions): string
    {
        $gammaRate = '';
        foreach ($bitsOfEachPositions as $position) {
            $gammaRate .= $this->getMostCommonBitFromPosition($position);
        }

        return $gammaRate;
    }

    private function getBitsOfEpsilonRate(string $bitsOfGammaRate): string
    {
        return strtr($bitsOfGammaRate, [1, 0]);
    }

    private function getPowerConsumptionValue($gammaRate, $epsilonRate): int
    {
        return bindec($gammaRate) * bindec($epsilonRate);
    }
}


