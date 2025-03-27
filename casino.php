#!/usr/bin/php
<?php

use JetBrains\PhpStorm\NoReturn;

$casino = new Casino();
$casino->showWelcomeScreen();
$casino->gameLoop();

class Casino
{
    private float $casinoBalance;
    private int $dateStart;
    private bool $displayInstructions = true;
    private int $simulatedDays = 0;
    private int $customerCount = 0;

    const int DAY_IN_SECONDS = 86400;

    const float CHANCE_TO_PLAY_SLOTS = 50;
    const float CHANCE_TO_PLAY_BLACK_JACK = 80;
    const float CHANCE_TO_PLAY_ROULETTE = 100;

    // Win Chances are from the view of the visitor/player
    const array ROULETTE_PAYOUTS = [
        'color' => ['chance' => 48.65, 'payout' => 2],
        'odd_even' => ['chance' => 48.65, 'payout' => 2],
        '18_numbers' => ['chance' => 48.65, 'payout' => 2],
        'column' => ['chance' => 32.43, 'payout' => 3],
        'dozen' => ['chance' => 32.43, 'payout' => 3],
        'six_number' => ['chance' => 16.22, 'payout' => 6],
        'four_number' => ['chance' => 10.81, 'payout' => 9],
        'three_number' => ['chance' => 8.11, 'payout' => 12],
        'two_number' => ['chance' => 5.41, 'payout' => 18],
        'single_number' => ['chance' => 2.70, 'payout' => 36]
    ];

    const array BLACKJACK_WIN_RATES = [
        'blackjack_win' => 4.83,
        'regular_win' => 42
    ];

    const array SLOT_MACHINE_OUTCOMES = [
        'jackpot_win' => ['chance' => 0.01, 'payout' => 1000],
        'mega_win' => ['chance' => 0.1, 'payout' => 100],
        'big_win' => ['chance' => 0.5, 'payout' => 50],
        'medium_win' => ['chance' => 5, 'payout' => 10],
        'small_win' => ['chance' => 20, 'payout' => 5],
        'money_back' => ['chance' => 15, 'payout' => 1],
        'near_miss' => ['chance' => 20, 'payout' => 0.5]
    ];

    public function __construct($budget = 1000000, $date = 946684800)
    {
        // 946684800 = 01.01.2000
        // 2000 for an easy year to play around with
        $this->casinoBalance = $budget;
        $this->dateStart = $date;
    }

    public function gameLoop(): void
    {
        if (php_sapi_name() === 'cli') {
            while ($this->casinoBalance > 0) {
                $this->processTurn();
            }
        } else {
            $this->processTurn();
        }
    }

    private function processTurn(): void
    {
        if ($this->displayInstructions) {
            $this->showCurrentDate();
            $this->showCasinoBalance();
            $this->showOptionsMenu();
        }

        $this->displayInstructions = true;

        if (php_sapi_name() === 'cli') {
            $input = trim(fgets(STDIN));
        } else {
            $input = $_POST['action'] ?? 'default';
        }

        switch (strtolower($input)) {
            case '':
            case '1':
                $this->runSimulation(1);
                break;
            case '2':
                $this->runSimulation(7);
                break;
            case '3':
                $this->runSimulation(31);
                break;
            case '4':
                $this->runSimulation(365);
                break;
            case 'h':
                $this->showHelpText();
                break;
            case 'g':
                $this->giveUp();
                break;
            case 'e':
                echo "Exiting... ";
                exit;
            default:
                echo "Invalid input. Please try again.";
                return;
        }

        if ($this->casinoBalance <= 0) {
            $this->gameOver();
        }
    }

    private function showCurrentDate(): void
    {
        echo "Date:   " . date("dS m Y", $this->dateStart) . PHP_EOL;
    }

    private function showCasinoBalance(): void
    {
        echo "Budget: " . number_format($this->casinoBalance, 2) . " €" . PHP_EOL;
    }

    public function showWelcomeScreen(): void
    {
        echo PHP_EOL;
        echo strip_tags("<p>Casino Simulation" . "</p>") . PHP_EOL;
        echo PHP_EOL;
    }

    private function showOptionsMenu(): void
    {
        echo PHP_EOL;
        echo "Choose what to do next" . PHP_EOL;
        echo PHP_EOL;
        echo "1 or enter: Simulate a day. " . PHP_EOL;
        echo "2: Simulate a week. " . PHP_EOL;
        echo "3: Simulate a month (31 days)." . PHP_EOL;
        echo "4: Simulate a year (365 days)." . PHP_EOL;
        echo "5: Simulate until amount of digits change. " . PHP_EOL;
        echo PHP_EOL;
        echo "Integer: Simulate for that amount of days. " . PHP_EOL;
        echo PHP_EOL;
        echo "h: Help, I'm lost." . PHP_EOL;
        echo "g: Give up." . PHP_EOL;
        echo "e: Exit." . PHP_EOL;
        echo PHP_EOL;
        echo "Your input: ";
    }

    private function showHelpText(): void
    {
        echo PHP_EOL;
        $this->printDivider();
        echo PHP_EOL;
        echo "Casino Simulation:" . PHP_EOL;

        echo PHP_EOL;
        echo "Idea:" . PHP_EOL;
        echo "Can a casino make money using mathematically correct odds?" . PHP_EOL;

        echo PHP_EOL;
        echo "Games:" . PHP_EOL;
        echo "1 Roulette table, 2 Black Jack tables & 9 Slot machines. This numbers don't do anything in the logic . " .
            "There are just there to give the user an idea or prompt the AI how many visitors we would have with this" .
            "machines . " . PHP_EOL;

        echo PHP_EOL;
        echo "Customers" . PHP_EOL;
        echo "Everyday your casino will be visited by a random amount of customers(50 - 200) . With a random amount of " .
            "money to play(50 EUR - 10.000 EUR). Each will play a random game until he / she run's out of money or played " .
            "enough (short tests were like 21-30 games)." . PHP_EOL;

        echo PHP_EOL;
        echo "Game Statistics:" . PHP_EOL;
        echo "  Roulette Bets:" . PHP_EOL;
        foreach (self::ROULETTE_PAYOUTS as $bet => $info) {
            echo "    - " . ucfirst(str_replace('_', ' ', $bet)) . ": " .
                "Chance: " . $info['chance'] . "%, " .
                "Payout: " . $info['payout'] . "x" . PHP_EOL;
        }
        echo PHP_EOL;
        echo "  Blackjack Chances:" . PHP_EOL;
        foreach (self::BLACKJACK_WIN_RATES as $outcome => $chance) {
            echo "    - " . ucfirst(str_replace('_', ' ', $outcome)) . ": " .
                "Chance: " . $chance . "%" . PHP_EOL;
        }
        echo PHP_EOL;
        echo "  Slot Machine Wins:" . PHP_EOL;
        foreach (self::SLOT_MACHINE_OUTCOMES as $winType => $info) {
            echo "    - " . ucfirst(str_replace('_', ' ', $winType)) . ": " .
                "Chance: " . $info['chance'] . "%, " .
                "Payout: " . $info['payout'] . "x" . PHP_EOL;
        }
        echo PHP_EOL;
        $this->printDivider();
    }

    public function runSimulation($days, $displayEcho = true): void
    {
        $visitorsThisSimulation = 0;
        $totalRevenue = 0;
        $budgetStartOfDay = $this->casinoBalance;

        if ($displayEcho) {
            $this->printDivider();
            echo PHP_EOL;
            echo "Running simulation for $days day(s)." . PHP_EOL;
            echo PHP_EOL;
        }

        // for days
        for ($i = 0; $i < $days; $i++) {

            // simulating we will have 50-200 visitors per day
            $visitors = mt_rand(50, 200);

            // for evey visitor for this day
            for ($j = 0; $j < $visitors; $j++) {

                $this->customerCount++;
                $visitor = new Customer();
                $gameChoice = mt_rand(100, 10000) / 100;

                if ($gameChoice <= self::CHANCE_TO_PLAY_SLOTS) {
                    $this->adjustCasinoBalance(
                        $visitor->playSlots(
                            self::SLOT_MACHINE_OUTCOMES
                        ));
                } elseif ($gameChoice <= self::CHANCE_TO_PLAY_ROULETTE) {
                    // Roulette
                    $this->adjustCasinoBalance(
                        $visitor->playRoulette(
                            self::ROULETTE_PAYOUTS
                        ));
                } elseif ($gameChoice <= self::CHANCE_TO_PLAY_BLACK_JACK) {
                    // Black Jack
                    $this->adjustCasinoBalance(
                        $visitor->playBlackJack(
                            self::BLACKJACK_WIN_RATES
                        ));
                }

                // If chances not add up to 100, those visitors don't play any games.

                // $totalRevenue += $moneySpendPerVisitor;
            }

            $visitorsThisSimulation += $visitors;
        }

        $this->updateDate($days);

        if ($displayEcho) {
            echo "Total visitors: $visitorsThisSimulation" . PHP_EOL;
            $this->showDailyProfit($budgetStartOfDay);
        }

        //$this->randomEvent();
    }

    private function randomEvent(): void
    {
        $chance = (mt_rand(100, 10000) / 100);

        switch (true) {
            case ($chance <= 1):
                echo PHP_EOL;

                echo PHP_EOL;
                break;
        }
    }

    private function adjustCasinoBalance($value): void
    {
        $this->casinoBalance += $value;
    }

    /**
     * Update the date based on days the program is simulating.
     *
     * @param int $days The amount of days simulating.
     */
    private function updateDate(int $days): void
    {
        $this->simulatedDays += $days;
        $this->dateStart += $days * self::DAY_IN_SECONDS;
    }

    private function giveUp(): void // Loser...
    {
        echo "Loser..." . PHP_EOL;
        echo PHP_EOL;
        $this->casinoBalance = 0;
    }

    //    private function gameOver($totalRevenue, $i)
    #[NoReturn] private function gameOver(): void
    {
        //        $this->totalDays += $i;

        //        echo "Lost after simulating: $i day(s)" . PHP_EOL;
        echo "Lost on Date: " . date("dS m Y", $this->dateStart) . PHP_EOL;
        echo "Lost after days: " . $this->simulatedDays . PHP_EOL;
        echo "Total visitors: " . $this->customerCount . PHP_EOL;
        //        if ($this->budget > $totalRevenue) {
        //            echo "Casino won: " . (number_format($this->budget - $totalRevenue)) . " €" . PHP_EOL;
        //        } else {
        //            echo "Casino lost: " . (number_format($totalRevenue - $this->budget)) . " €" . PHP_EOL;
        //        }
        echo PHP_EOL;
        echo "Game Over. You're out of money!" . PHP_EOL;
        echo PHP_EOL;
        exit;
    }

    private function printDivider(): void
    {
        echo "__________________________________________" . PHP_EOL;
    }

    /**
     * @param float $budgetBefore
     * @return void
     */
    public function showDailyProfit(float $budgetBefore): void
    {
        if ($this->casinoBalance > $budgetBefore) {
            echo "Casino won: " . (number_format($this->casinoBalance - $budgetBefore)) . " €" . PHP_EOL;
        } else {
            echo "Casino lost: " . (number_format($budgetBefore - $this->casinoBalance)) . " €" . PHP_EOL;
        }
        echo PHP_EOL;
        $this->printDivider();
        echo PHP_EOL;
    }
}

class Customer
{
    private float $money;
    private int $gamesPlayed;
    private bool $hasFakeMoney = false;
    private bool $isMillionaire = false; // other name option: highRoller

    public function __construct()
    {
        $randomEventIsMillionaire = mt_rand(1, 10000);
        $randomEventHasCounterfeitMoney = mt_rand(1, 100);

        $this->money = mt_rand(50, 10000);
        $this->gamesPlayed = 0;

        if ($randomEventIsMillionaire == 1) {
            $this->isMillionaire = true;
        }

        if ($randomEventHasCounterfeitMoney == 1) {
            $this->hasFakeMoney = true;
        }

        if ($this->isMillionaire && $this->hasFakeMoney) {
            echo PHP_EOL;
            echo "Ultra Rare Event: A customer with a million budget hat counterfeit money D: D: D:!\n";
            echo PHP_EOL;
        } elseif ($this->isMillionaire) {
            echo "Rare Event: You have a visitor with a budget of 1 million!\n";
            echo PHP_EOL;
        } elseif ($this->hasFakeMoney) {
            echo "Rare Event: One of your visitor bought chips with counterfeit money D:! (You lose this money)\n";
            echo PHP_EOL;
        }
    }

    public function playSlots($slotChances): float
    {
        $playCount = 0;
        $moneyBeforePlaying = $this->money;

        while ($this->money > 0) {
            $this->gamesPlayed++;
            $bet = min(mt_rand(5, 100), $this->money);
            $winChance = mt_rand(0, 10000);

            $this->money += match (true) {
                $winChance <= $slotChances['jackpot_win']['chance'] => ($bet * $slotChances['jackpot_win']['payout']) - $bet,
                $winChance <= $slotChances['mega_win']['chance'] => ($bet * $slotChances['mega_win']['payout']) - $bet,
                $winChance <= $slotChances['big_win']['chance'] => ($bet * $slotChances['big_win']['payout']) - $bet,
                $winChance <= $slotChances['medium_win']['chance'] => ($bet * $slotChances['medium_win']['payout']) - $bet,
                $winChance <= $slotChances['small_win']['chance'] => ($bet * $slotChances['small_win']['payout']) - $bet,
                $winChance <= $slotChances['money_back']['chance'] => 0, // No change
                $winChance <= $slotChances['near_miss']['chance'] => ($bet * $slotChances['near_miss']['payout']) - $bet,
                default => -$bet,
            };

            $playCount++;

            if ($this->calculateChanceToStopPlaying($playCount)) {
                break;
            }
        }

        return $this->calculateCasinoEarnings($moneyBeforePlaying);
    }

    public function playRoulette(
        $rouletteBets
    ): float
    {
        $playCount = 0;
        $moneyBeforePlaying = $this->money;

        while ($this->money > 0) {
            $this->gamesPlayed++;

            // simulating the player choose between 1-9 bets per round
            $amountOfBets = mt_rand(1, 9);

            // making also the choosen games random
            $choosenVariants = (array)array_rand($rouletteBets, $amountOfBets);

            foreach ($choosenVariants as $choosenVariant) {
                $bet = min(mt_rand(5, 100), $this->money);
                $winChance = mt_rand(0, 10000) / 100;

                $chance = $rouletteBets[$choosenVariant]['chance'];
                $payout = $rouletteBets[$choosenVariant]['payout'];

                $this->money += match (true) {
                    $winChance <= $chance => ($bet * $payout) - $bet,
                    default => -$bet,
                };

            }

            $playCount++;

            if ($this->calculateChanceToStopPlaying($playCount)) {
                break;
            }
        }

        return $this->calculateCasinoEarnings($moneyBeforePlaying);
    }

    public function playBlackJack($blackJackChances): float
    {
        $playCount = 0;
        $moneyBeforePlaying = $this->money;

        while ($this->money > 0) {
            $this->gamesPlayed++;
            $bet = min(mt_rand(5, 100), $this->money);
            $winChance = mt_rand(0, 10000) / 100;

            $this->money += match (true) {
                $winChance <= $blackJackChances['blackjack_win'] => $bet * 1.5,
                $winChance <= $blackJackChances['regular_win'] => $bet,
                default => -$bet,
            };

            $playCount++;

            if ($this->calculateChanceToStopPlaying($playCount)) {
                break;
            }
        }

        return $this->calculateCasinoEarnings($moneyBeforePlaying);
    }

    private function calculateChanceToStopPlaying($timesPlayedTheSameGame): bool
    {
        // chance that the player doesn't want to play anymore
        // maybe adjustable per game
        if (100 < (mt_rand(1, 5) * $timesPlayedTheSameGame)) {
            return true;
        } else {
            return false;
        }
    }

    private function calculateCasinoEarnings($moneyBeforePlaying): float
    {
        // if diff positive = visitor lost, casino won
        // if diff negative = visitor won, casino lost
        $diff = $moneyBeforePlaying - $this->money;

        if ($this->hasFakeMoney) {
            if ($diff >= 0) {
                return 0;
            } else {
                // since the visitor pays the chips with his counterfeit money, it is already within our system
                // so him/her winning, doesn't mean he/she will get the same bills back
                return $diff - $moneyBeforePlaying;
            }
        }

        return $diff;
    }
}
