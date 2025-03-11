#!/usr/bin/php
<?php declare(strict_types=1);

$casino = new Casino();
$casino->displayIntro();
$casino->gameLoop();

class Casino
{
    private $budget;
    private $date;
    private $displayInstructions = true;
    private $totalDays = 0;
    private $totalVisitors = 0;

    const SECONDS_IN_A_DAY = 86400;

    // Win Chances are from the view of the visitor/player
    const ROULETTE_WIN_CHANCE = 0.47;
    const BLACKJACK_WIN_CHANCE = 0.42;
    const SLOT_CHANCE_BIG_WIN = 0.05;
    const SLOT_CHANCE_MEDIUM_WIN = 10;
    const SLOT_CHANCE_SMALL_WIN = 35;
    const SLOT_CHANCE_NO_WIN = 100;

    public function __construct($budget = 1000000, $date = 946684800)
    {
        // 946684800 = 01.01.2000
        // 2000 for an easy year to play around with
        $this->budget = $budget;
        $this->date = $date;
    }

    public function gameLoop()
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

    private function displayDate()
    {
        echo "Date:   " . date("dS m Y", $this->date) . PHP_EOL;
    }

    private function displayBudget()
    {
        echo "Budget: " . number_format($this->budget, 2) . " €" . PHP_EOL;
    }

    public function displayIntro()
    {
        echo PHP_EOL;
        echo "Casino Simulation." . PHP_EOL;
        echo PHP_EOL;
    }

    private function displayInstructions()
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

    private function displayHelp()
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
        echo "1 Roulette table, 2 Black Jack tables & 9 Slot machines." . PHP_EOL;

        echo PHP_EOL;
        echo "Customers" . PHP_EOL;
        echo "Everyday your casino will be visited by a random amount of customers, with random amount of money. They will play a random amount of games." . PHP_EOL;

        echo PHP_EOL;
        echo "Randomness:" . PHP_EOL;
        echo "The randomness will be in legitimate numbers: You won't have an unrealistic amount of customers per day. The math are doing by mathematically correct odds." . PHP_EOL;
        echo PHP_EOL;
        $this->displaySeparator();
    }

    private function simulate($days)
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
                $moneySpendPerVisitor = rand(50, 10000);
                $visitor = new Visitor();

                $gameChoice = rand(1, 100);

                if ($gameChoice <= 50) {
                    $this->updateBudget(
                        $visitor->playSlots(
                            self::SLOT_CHANCE_BIG_WIN,
                            self::SLOT_CHANCE_MEDIUM_WIN,
                            self::SLOT_CHANCE_SMALL_WIN,
                            self::SLOT_CHANCE_NO_WIN
                        ));
                } elseif ($gameChoice <= 70) {
                    // Roulette
                    $winChance = self::ROULETTE_WIN_CHANCE;
                    $visitor->spendMoney($moneySpendPerVisitor, $winChance);
                    $this->updateBudget($moneySpendPerVisitor, $winChance);
                } else {
                    // Black Jack
                    $winChance = self::BLACKJACK_WIN_CHANCE;
                    $visitor->spendMoney($moneySpendPerVisitor, $winChance);
                }

//                $totalRevenue += $moneySpendPerVisitor;
            }

            $visitorsThisSimulation += $visitors;
            $this->updateDate($days);
        }

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
    private function updateBudgetOld($moneySpent, $winChance)
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
    private function updateDate($days)
    {
        $this->totalDays += $days;
        $this->date = $this->date + ($days * self::SECONDS_IN_A_DAY);
    }

    private function giveUp() // Loser...
    {
        echo "Loser..." . PHP_EOL;
        echo PHP_EOL;
        $this->budget = 0;
    }

//    private function gameOver($totalRevenue, $i)
    private function gameOver()
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

    private function displaySeparator()
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

    public function playSlots($chanceBigWin, $chanceMediumWin, $chanceSmallWin, $chanceNoWin): float
    {
        $spinCount = 0;
        $moneyBeforePlaying = $this->money;

        while ($this->money > 0) {
            $this->gamesPlayed++;
            $bet = min(rand(5, 100), $this->money);
            $winChance = rand(0, 100);

            switch (true) {
                case ($winChance <= $chanceBigWin):
                    $winning = $bet * (rand(50, 1000) / 100);
                    $this->money += $winning;
                    break;
                case ($winChance <= $chanceMediumWin):
                    $this->money += $bet * (rand(5, 20) / 100);
                    break;
                case ($winChance <= $chanceSmallWin):
                    $this->money += $bet * (rand(2, 5) / 100);
                    break;
                case ($winChance <= $chanceNoWin):
                    $this->money -= $bet;
                    break;
            }

            $spinCount++;

            // chance that the player doesn't want to play anymore
            if (100 < (rand(1, 5) * $spinCount)) {
                break;
            }
        }

        return $moneyBeforePlaying - $this->money;
    }
}