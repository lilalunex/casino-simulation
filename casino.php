#!/usr/bin/php
<?php declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;

$casino = new Casino();
$casino->displayIntro();
$casino->gameLoop();

class Casino
{
    private float $budget;
    private int $date;
    private bool $displayInstructions = true;
    private int $totalDays = 0;
    private int $totalVisitors = 0;

    const int SECONDS_IN_A_DAY = 86400;

    // Win Chances are from the view of the visitor/player
    const array ROULETTE_BETS = [
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

    const array BLACKJACK_CHANCES = [
        'blackjack_win' => 4.83,
        'regular_win' => 42
    ];

    const array SLOT_CHANCES = [
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
        $this->budget = $budget;
        $this->date = $date;
    }

    public function gameLoop(): void
    {
        while ($this->budget > 0) {

            if ($this->displayInstructions) {
                $this->displayDate();
                $this->displayBudget();
                $this->displayInstructions();
            }

            $this->displayInstructions = true;

            $input = trim(fgets(STDIN));

            switch (strtolower($input)) {
                case '': // Enter
                case '1':
                    $this->simulate(1);
                    break;
                case '2':
                    $this->simulate(7);
                    break;
                case '3':
                    $this->simulate(31);
                    break;
                case '4':
                    $this->simulate(365);
                    break;
//                case '5':
//                    $this->simulate();
//                    break;
                case 'h':
                    $this->displayHelp();
                    break;
                case 'g':
                    $this->giveUp();
                    break;
                case 'e':
                    echo "Exiting." . PHP_EOL;
                    echo PHP_EOL;
                    exit;
                default:
                    $this->displayInstructions = false;
                    echo PHP_EOL;
                    echo "Invalid input. Please try again." . PHP_EOL;
                    echo "Your input: ";
            }

            if ($this->budget <= 0) {
                $this->gameOver();
            }
        }
    }

    private function displayDate(): void
    {
        echo "Date:   " . date("dS m Y", $this->date) . PHP_EOL;
    }

    private function displayBudget(): void
    {
        echo "Budget: " . number_format($this->budget, 2) . " €" . PHP_EOL;
    }

    public function displayIntro(): void
    {
        echo PHP_EOL;
        echo "Casino Simulation." . PHP_EOL;
        echo PHP_EOL;
    }

    private function displayInstructions(): void
    {
        echo PHP_EOL;
        echo "Choose what to do next" . PHP_EOL;
        echo PHP_EOL;
        echo "1 or enter: Simulate a day." . PHP_EOL;
        echo "2: Simulate a week." . PHP_EOL;
        echo "3: Simulate a month (31 days)." . PHP_EOL;
        echo "4: Simulate a year (365 days)." . PHP_EOL;
//        echo "5: Simulate until (maybe) a new digit is appended." . PHP_EOL;
        echo PHP_EOL;
        echo "h: Help, I'm lost." . PHP_EOL;
        echo "g: Give up." . PHP_EOL;
        echo "e: Exit." . PHP_EOL;
        echo PHP_EOL;
        echo "Your input: ";
    }

    private function displayHelp(): void
    {
        echo PHP_EOL;
        $this->displaySeparator();
        echo PHP_EOL;
        echo "Casino Simulation:" . PHP_EOL;

        echo PHP_EOL;
        echo "Idea:" . PHP_EOL;
        echo "Can a casino make money using mathematically correct odds?" . PHP_EOL;

        echo PHP_EOL;
        echo "Games:" . PHP_EOL;
        echo "1 Roulette table, 2 Black Jack tables & 9 Slot machines. This numbers don't do anything in the logic." .
            "There are just there to give the user an idea or prompt the AI how many visitors we would have with this" .
            "machines." . PHP_EOL;

        echo PHP_EOL;
        echo "Customers" . PHP_EOL;
        echo "Everyday your casino will be visited by a random amount of customers, with random amount of money." .
            "They will play a random amount of games." . PHP_EOL;

        echo PHP_EOL;
        echo "Randomness:" . PHP_EOL;
        echo "The randomness will be in legitimate numbers: You won't have an unrealistic amount of customers per day." .
            "The math are doing by mathematically correct odds." . PHP_EOL;
        echo PHP_EOL;
        $this->displaySeparator();
    }

    private function simulate($days): void
    {
        $visitorsThisSimulation = 0;
        $totalRevenue = 0;
        $budgetStartOfDay = $this->budget;

        echo PHP_EOL;
        $this->displaySeparator();
        echo PHP_EOL;
        echo "Running simulation for $days day(s)." . PHP_EOL;
        echo PHP_EOL;

        // for days
        for ($i = 0; $i < $days; $i++) {

            // simulating we will have 50-200 visitors per day
            $visitors = rand(50, 200);

            // for evey visitor for this day
            for ($j = 0; $j < $visitors; $j++) {

                $this->totalVisitors++;
                $visitor = new Visitor();

                $gameChoice = rand(1, 100);

                if ($gameChoice <= 50) {
                    $this->updateBudget(
                        $visitor->playSlots(
                            self::SLOT_CHANCES
                        ));
                } elseif ($gameChoice <= 70) {
                    // Roulette
                    $this->updateBudget(
                        $visitor->playRoulette(
                            self::ROULETTE_BETS
                        ));
                } else {
                    // Black Jack
                    $this->updateBudget(
                        $visitor->playBlackJack(
                            self::BLACKJACK_CHANCES
                        ));
                }

//                $totalRevenue += $moneySpendPerVisitor;
            }

            $visitorsThisSimulation += $visitors;
        }

        $this->updateDate($days);

        echo "Total visitors: $visitorsThisSimulation" . PHP_EOL;
        if ($this->budget > $budgetStartOfDay) {
            echo "Casino won: " . (number_format($this->budget - $budgetStartOfDay)) . " €" . PHP_EOL;
        } else {
            echo "Casino lost: " . (number_format($budgetStartOfDay - $this->budget)) . " €" . PHP_EOL;
        }

        echo PHP_EOL;
        $this->displaySeparator();
        echo PHP_EOL;
    }

    /**
     * Update the budget based on the outcome of a bet.
     *
     * @param float $moneySpent The amount spent by the player.
     * @param float $winChance The probability of the player winning.
     */
    private function updateBudgetOld($moneySpent, $winChance): void
    {
        $visitorWin = (rand(0, 100) <= $winChance * 100);
        if ($visitorWin) {
            $this->budget = $this->budget - $moneySpent;
        } else {
            $this->budget = $this->budget + $moneySpent;
        }
    }

    private function updateBudget($value): void
    {
        $this->budget += $value;
    }

    /**
     * Update the date based on days the program is simulating.
     *
     * @param int $days The amount of days simulating.
     */
    private function updateDate(int $days): void
    {
        $this->totalDays += $days;
        $this->date += $days * self::SECONDS_IN_A_DAY;
    }

    private function giveUp(): void // Loser...
    {
        echo "Loser..." . PHP_EOL;
        echo PHP_EOL;
        $this->budget = 0;
    }

//    private function gameOver($totalRevenue, $i)
    #[NoReturn] private function gameOver(): void
    {
//        $this->totalDays += $i;

//        echo "Lost after simulating: $i day(s)" . PHP_EOL;
        echo "Lost on Date: " . date("dS m Y", $this->date) . PHP_EOL;
        echo "Lost after days: " . $this->totalDays . PHP_EOL;
        echo "Total visitors: " . $this->totalVisitors . PHP_EOL;
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

    private function displaySeparator(): void
    {
        echo "__________________________________________" . PHP_EOL;
    }
}

class Visitor
{
    private float $money;
    private int $gamesPlayed;

    public function __construct()
    {
        $this->money = rand(50, 10000);;
        $this->gamesPlayed = 0;
    }

    public function spendMoney($amount, $winChance): void
    {
        $win = (rand(0, 100) <= $winChance * 100);
        if ($win) {
            $this->money -= $amount;
        } else {
            $this->money += $amount;
        }
        $this->gamesPlayed++;
    }

    public function playSlots($slotChances): float
    {
        $playCount = 0;
        $moneyBeforePlaying = $this->money;

        while ($this->money > 0) {
            $this->gamesPlayed++;
            $bet = min(rand(5, 100), $this->money);
            $winChance = rand(0, 10000);

            switch (true) {
                case ($winChance <= $slotChances['jackpot_win']['chance']):
                    $this->money += ($bet * $slotChances['jackpot_win']['payout']) - $bet;
                    break;
                case ($winChance <= $slotChances['mega_win']['chance']):
                    $this->money += ($bet * $slotChances['mega_win']['payout']) - $bet;
                    break;
                case ($winChance <= $slotChances['big_win']['chance']):
                    $this->money += ($bet * $slotChances['big_win']['payout']) - $bet;
                    break;
                case ($winChance <= $slotChances['medium_win']['chance']):
                    $this->money += ($bet * $slotChances['medium_win']['payout']) - $bet;
                    break;
                case ($winChance <= $slotChances['small_win']['chance']):
                    $this->money += ($bet * $slotChances['small_win']['payout']) - $bet;
                    break;
                case ($winChance <= $slotChances['money_back']['chance']):
                    break;
                case ($winChance <= $slotChances['near_miss']['chance']):
                    $this->money += ($bet * $slotChances['near_miss']['payout']) - $bet;
                    break;
                default:
                    $this->money -= $bet;
                    break;
            }

            $playCount++;

            if ($this->calcuateChanceToStopPlaying($playCount)) {
                break;
            }
        }

        return $moneyBeforePlaying - $this->money;
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
            $amountOfBets = rand(1, 9);

            // making also the choosen games random
            $choosenVariants = (array)array_rand($rouletteBets, $amountOfBets);

            foreach ($choosenVariants as $choosenVariant) {
                $bet = min(rand(5, 100), $this->money);
                $winChance = rand(0, 10000) / 100;

                $chance = $rouletteBets[$choosenVariant]['chance'];
                $payout = $rouletteBets[$choosenVariant]['payout'];

                switch (true) {
                    case ($winChance <= $chance):
                        $this->money += ($bet * $payout) - $bet;
                        break;
                    default:
                        $this->money -= $bet;
                        break;
                }
            }

            $playCount++;

            if ($this->calcuateChanceToStopPlaying($playCount)) {
                break;
            }
        }

        return $moneyBeforePlaying - $this->money;
    }

    public function playBlackJack($blackJackChances): float
    {
        $playCount = 0;
        $moneyBeforePlaying = $this->money;

        while ($this->money > 0) {
            $this->gamesPlayed++;
            $bet = min(rand(5, 100), $this->money);
            $winChance = rand(0, 10000) / 100;

            switch (true) {
                case ($winChance <= $blackJackChances['blackjack_win']):
                    $this->money += $bet * 1.5;
                    break;
                case ($winChance <= $blackJackChances['regular_win']):
                    $this->money += $bet;
                    break;
                default:
                    $this->money -= $bet;
                    break;
            }

            $playCount++;

            if ($this->calcuateChanceToStopPlaying($playCount)) {
                break;
            }
        }

        return $moneyBeforePlaying - $this->money;
    }

    private function calcuateChanceToStopPlaying($timesPlayedTheSameGame): bool
    {
        // chance that the player doesn't want to play anymore
        // maybe adjustable per game
        if (100 < (rand(1, 5) * $timesPlayedTheSameGame)) {
            return true;
        } else {
            return false;
        }
    }
}