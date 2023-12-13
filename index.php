<?php

function generateBinaryNumbers(int $rowNumber = 11, int $columnNumber = 5): array
{
    $bits = [];
    for ($row = 0; $row < $rowNumber; $row++) {
        for ($col = 0; $col < $columnNumber; $col++) {
            $bits[$row][$col] = rand(0, 1);
        }
    }

    return $bits;
}

function showBinaryNumbersReport(array $bits): void
{
    $rowNumber = count($bits);
    $columnNumber = count($bits[0]);

    for ($row = 0; $row < $rowNumber; $row++) {
        for ($col = 0; $col < $columnNumber; $col++) {
            echo $bits[$row][$col];
        }
        echo "\n";
    }
    echo "\n";
}

function getBitsOfGammaRate(array $binaryReport)
{
    $arrayOfBitsFromColumn = [];
    $bitsOfGammaRate = null;
    for ($row = 0; $row < 5; $row++) {
        for ($col = 0; $col < 11; $col++) {
            $arrayOfBitsFromColumn[] = $binaryReport[$col][$row];
        }

        $counts = array_count_values($arrayOfBitsFromColumn);
        arsort($counts);
        $maxValue = max($counts);
        $mostCommonBitOfColumn = array_search($maxValue, $counts);
        $arrayOfBitsFromColumn = null;
        $bitsOfGammaRate .= $mostCommonBitOfColumn;
    }

    return $bitsOfGammaRate;
}

function getBitsOfEpsilonRate($bitsOfGammaRate)
{
    return strtr($bitsOfGammaRate, [1, 0]);
}

function getPowerConsumptionValue($bitsOfGammaRate, $bitsOfEpsilonRate): int
{
    return bindec($bitsOfGammaRate) * bindec($bitsOfEpsilonRate);
}

$binaryReport = generateBinaryNumbers();
showBinaryNumbersReport($binaryReport);

$bitsOfGammaRate = getBitsOfGammaRate($binaryReport);
$bitsOfEpsilonRate = getBitsOfEpsilonRate($bitsOfGammaRate);

echo "The power consumption of the submarine is: " . getPowerConsumptionValue($bitsOfGammaRate, $bitsOfEpsilonRate);

?>
