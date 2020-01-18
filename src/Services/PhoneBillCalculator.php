<?php

namespace Intcal\Services;


class PhoneBillCalculator
{
    /**
     * @param string $billStr
     * @return int
     */
    public function calculatePhoneBill(string $billStr): int
    {
        $billArr = $this->getBillArray($billStr);
        $minutesPerNumber = $this->getMinutesPerNumber($billArr);

        $minutesPerNumber = $this->excludeFreeCall($minutesPerNumber);

        return $this->calulateBillamount($minutesPerNumber);
    }

    /**
     * @param string $billStr
     * @return array
     */
    private function getBillArray(string $billStr): array
    {
        $tempArr = explode(PHP_EOL, $billStr);

        $explodeBillDetails = function ($row) {
            return explode(',', $row);
        };

        return array_map($explodeBillDetails, $tempArr);
    }

    /**
     * @param array $billArr
     * @return array
     */
    private function getMinutesPerNumber(array $billArr): array
    {
        $minutesPerNumber = [];

        foreach ($billArr as $billRow) {
            $callDuration = explode(':', trim($billRow[0]));
            $minutes = ceil($callDuration[0] * 60 + $callDuration[1] + $callDuration[2] / 60);

            if (isset($minutesPerNumber[$billRow[1]])) {
                $minutesPerNumber[$billRow[1]] += $minutes;
            } else {
                $minutesPerNumber[$billRow[1]] = $minutes;
            }
        }

        return $minutesPerNumber;
    }

    /**
     * @param array $billMinutesPerNumber
     * @return array
     */
    private function excludeFreeCall(array $billMinutesPerNumber): array
    {
        asort($billMinutesPerNumber, SORT_NUMERIC);
        array_pop($billMinutesPerNumber);

        return $billMinutesPerNumber;
    }

    private function calulateBillamount(array $minutesPerNumber)
    {
        $getAmount = function ($amount, $minutes) {
            if ($minutes < 5) {
                $amount += $minutes * 3;
            } else {
                $amount += $minutes * 150;
            }

            return $amount;
        };

        return array_reduce($minutesPerNumber, $getAmount);
    }
}