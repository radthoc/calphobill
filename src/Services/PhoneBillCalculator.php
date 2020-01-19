<?php

namespace Calphobill\Services;

class PhoneBillCalculator
{
    /**
     * Business rules:
     *
     * Calculate the bill ammount in cents from a string with the calls duration per number
     *
     * The number with less minutes is not charged
     * Any fraction of minute will be consider as a whole minute
     * If a number has less than 5 minutes the amount is 3 cents per minute
     * If it is more or equal to 5 minutes then is 150 cents per minute
     *
     * @param string $billStr
     * @return int
     */
    public function calculatePhoneBill(string $billStr): int
    {
        $billArr = $this->getBillArray($billStr);
        $minutesPerNumber = $this->getMinutesPerNumber($billArr);

        $minutesPerNumber = $this->excludeFreeNumber($minutesPerNumber);

        return $this->calculateBillAmount($minutesPerNumber);
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
    private function excludeFreeNumber(array $billMinutesPerNumber): array
    {
        asort($billMinutesPerNumber, SORT_NUMERIC);
        array_pop($billMinutesPerNumber);

        return $billMinutesPerNumber;
    }

    /**
     * @param array $minutesPerNumber
     * @return int
     */
    private function calculateBillAmount(array $minutesPerNumber): int
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