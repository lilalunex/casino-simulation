<?php

if ($_GET['arg'] == "web") {
    session_start();
}

use JetBrains\PhpStorm\NoReturn;

class Casino
{
    // 1 = cli
    // 2 = web
    private int $env = 1;
    private float $casinoBalance;
    private int $dateStart;
    private bool $displayInstructions = true;
    private int $simulatedDays = 0;
    private int $customerCount = 0;
    const DAY_IN_SECONDS = 86400;
    const CHANCE_TO_PLAY_SLOTS = 50;
    const CHANCE_TO_PLAY_BLACK_JACK = 80;
    const CHANCE_TO_PLAY_ROULETTE = 100;

    // Win Chances are from the view of the visitor/player
    const ROULETTE_PAYOUTS = [
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

    const BLACKJACK_WIN_RATES = [
        'blackjack_win' => 4.83,
        'regular_win' => 42
    ];

    const SLOT_MACHINE_OUTCOMES = [
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
        if ($_GET['arg'] == "cli") {
            $this->env = 1;

            // 946684800 = 01.01.2000
            // 2000 for an easy year to play around with
            $this->casinoBalance = $budget;
            $this->dateStart = $date;
        } else if ($_GET['arg'] == "web") {
            $this->env = 2;

            // If there's no session data, initialize it
            if (!isset($_SESSION['casinoBalance'])) {
                $_SESSION['casinoBalance'] = $budget;
                $_SESSION['dateStart'] = $date;
                $_SESSION['simulatedDays'] = 0;
                $_SESSION['customerCount'] = 0;
            }

            $this->casinoBalance = $_SESSION['casinoBalance'];
            $this->dateStart = $_SESSION['dateStart'];
        }
    }

    public function restart(): void
    {
        $_SESSION['casinoBalance'] = 1000000;
        $_SESSION['dateStart'] = 946684800;
        $_SESSION['simulatedDays'] = 0;
        $_SESSION['customerCount'] = 0;
        $this->dateStart = $_SESSION['dateStart'];
        $this->casinoBalance = $_SESSION['casinoBalance'];
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
                if ($this->env == 1) echo "Exiting... ";
                exit;
            default:
                if ($this->env == 1) echo "Invalid input. Please try again.";
                return;
        }

        if ($this->casinoBalance <= 0) {
            $this->gameOver();
        }
    }

    private function showCurrentDate(): void
    {
        if ($this->env == 1) echo "Date:   " . date("dS m Y", $this->dateStart) . PHP_EOL;
        if ($this->env == 2) echo "Date:   " . date("dS m Y", $this->dateStart) . "<br>";
    }

    private function showCasinoBalance(): void
    {
        if ($this->env == 1) echo "Budget: " . number_format($this->casinoBalance, 2) . " €" . PHP_EOL;
        if ($this->env == 2) echo "Budget: " . number_format($this->casinoBalance, 2) . " €" . "<br>";
    }

    public function showWelcomeScreen(): void
    {
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "<p>Casino Simulation" . PHP_EOL;
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "<h1>Casino Simulation</h1>" . "<br>";
        if ($this->env == 2) echo "<br>";
    }

    private function showOptionsMenu(): void
    {
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Choose what to do next" . PHP_EOL;
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "1 or enter: Simulate a day. " . PHP_EOL;
        if ($this->env == 1) echo "2: Simulate a week. " . PHP_EOL;
        if ($this->env == 1) echo "3: Simulate a month (31 days)." . PHP_EOL;
        if ($this->env == 1) echo "4: Simulate a year (365 days)." . PHP_EOL;
        if ($this->env == 1) echo "5: Simulate until amount of digits change. " . PHP_EOL;
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Integer: Simulate for that amount of days. " . PHP_EOL;
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "h: Help, I'm lost." . PHP_EOL;
        if ($this->env == 1) echo "g: Give up." . PHP_EOL;
        if ($this->env == 1) echo "e: Exit." . PHP_EOL;
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Your input: ";


        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "Choose what to do next" . "<br>";
        if ($this->env == 2) echo "<br>";
    }

    public function showHelpText(): void
    {
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) $this->printDivider();
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Casino Simulation:" . PHP_EOL;

        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Idea:" . PHP_EOL;
        if ($this->env == 1) echo "Can a casino make money using mathematically correct odds?" . PHP_EOL;

        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Games:" . PHP_EOL;
        if ($this->env == 1) echo "1 Roulette table, 2 Black Jack tables & 9 Slot machines. This numbers don't do anything in the logic . " .
            "There are just there to give the user an idea or prompt the AI how many visitors we would have with this" .
            "machines . " . PHP_EOL;

        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Customers" . PHP_EOL;
        if ($this->env == 1) echo "Everyday your casino will be visited by a random amount of customers(50 - 200) . With a random amount of " .
            "money to play(50 EUR - 10.000 EUR). Each will play a random game until he / she run's out of money or played " .
            "enough (short tests were like 21-30 games)." . PHP_EOL;

        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Game Statistics:" . PHP_EOL;
        if ($this->env == 1) echo "  Roulette Bets:" . PHP_EOL;
        foreach (self::ROULETTE_PAYOUTS as $bet => $info) {
            if ($this->env == 1) echo "    - " . ucfirst(str_replace('_', ' ', $bet)) . ": " .
                "Chance: " . $info['chance'] . "%, " .
                "Payout: " . $info['payout'] . "x" . PHP_EOL;
        }
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "  Blackjack Chances:" . PHP_EOL;
        foreach (self::BLACKJACK_WIN_RATES as $outcome => $chance) {
            if ($this->env == 1) echo "    - " . ucfirst(str_replace('_', ' ', $outcome)) . ": " .
                "Chance: " . $chance . "%" . PHP_EOL;
        }
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "  Slot Machine Wins:" . PHP_EOL;
        foreach (self::SLOT_MACHINE_OUTCOMES as $winType => $info) {
            if ($this->env == 1) echo "    - " . ucfirst(str_replace('_', ' ', $winType)) . ": " .
                "Chance: " . $info['chance'] . "%, " .
                "Payout: " . $info['payout'] . "x" . PHP_EOL;
        }
        if ($this->env == 1) echo PHP_EOL;

        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) $this->printDivider();
        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "Casino Simulation:" . "<br>";

        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "Idea:" . "<br>";
        if ($this->env == 2) echo "Can a casino make money using mathematically correct odds?" . "<br>";

        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "Games:" . "<br>";
        if ($this->env == 2) echo "1 Roulette table, 2 Black Jack tables & 9 Slot machines. This numbers don't do anything in the logic . " .
            "There are just there to give the user an idea or prompt the AI how many visitors we would have with this" .
            "machines . " . "<br>";

        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "Customers" . "<br>";
        if ($this->env == 2) echo "Everyday your casino will be visited by a random amount of customers(50 - 200) . With a random amount of " .
            "money to play(50 EUR - 10.000 EUR). Each will play a random game until he / she run's out of money or played " .
            "enough (short tests were like 21-30 games)." . "<br>";

        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "Game Statistics:" . "<br>";
        if ($this->env == 2) echo "  Roulette Bets:" . "<br>";
        foreach (self::ROULETTE_PAYOUTS as $bet => $info) {
            if ($this->env == 2) echo "    - " . ucfirst(str_replace('_', ' ', $bet)) . ": " .
                "Chance: " . $info['chance'] . "%, " .
                "Payout: " . $info['payout'] . "x" . "<br>";
        }
        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "  Blackjack Chances:" . "<br>";
        foreach (self::BLACKJACK_WIN_RATES as $outcome => $chance) {
            if ($this->env == 2) echo "    - " . ucfirst(str_replace('_', ' ', $outcome)) . ": " .
                "Chance: " . $chance . "%" . "<br>";
        }
        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "  Slot Machine Wins:" . "<br>";
        foreach (self::SLOT_MACHINE_OUTCOMES as $winType => $info) {
            if ($this->env == 2) echo "    - " . ucfirst(str_replace('_', ' ', $winType)) . ": " .
                "Chance: " . $info['chance'] . "%, " .
                "Payout: " . $info['payout'] . "x" . "<br>";
        }
        if ($this->env == 2) $this->printDivider();
    }

    public function runSimulation($days, $displayEcho = true): void
    {
        $visitorsThisSimulation = 0;
        $totalRevenue = 0;
        $budgetStartOfDay = $this->casinoBalance;

        if ($displayEcho) {
            $this->printDivider();
            if ($this->env == 1) echo PHP_EOL;
            if ($this->env == 1) echo "Running simulation for $days day(s)." . PHP_EOL;
            if ($this->env == 1) echo PHP_EOL;
            if ($this->env == 2) echo "<br>";
            if ($this->env == 2) echo "Running simulation for $days day(s)." . "<br>";
            if ($this->env == 2) echo "<br>";
        }

        // for days
        for ($i = 0; $i < $days; $i++) {

            // simulating we will have 50-200 visitors per day
            $visitors = mt_rand(50, 200);

            // for evey visitor for this day
            for ($j = 0; $j < $visitors; $j++) {

                $this->customerCount++;
                $visitor = new Customer($this->env);
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

            $_SESSION['casinoBalance'] = $this->casinoBalance;
            $_SESSION['dateStart'] = $this->dateStart;
            $_SESSION['simulatedDays'] = $this->simulatedDays;
            $_SESSION['customerCount'] = $this->customerCount;
        }

        $this->updateDate($days);

        if ($displayEcho) {
            if ($this->env == 1) echo "Total visitors: $visitorsThisSimulation" . PHP_EOL;
            if ($this->env == 2) echo "Total visitors: $visitorsThisSimulation" . "<br>";
            $this->showDailyProfit($budgetStartOfDay);
        }

        //$this->randomEvent();
    }

    private function randomEvent(): void
    {
        $chance = (mt_rand(100, 10000) / 100);

        switch (true) {
            case ($chance <= 1):
                if ($this->env == 1) echo PHP_EOL;
                if ($this->env == 1) echo PHP_EOL;
                if ($this->env == 2) echo "<br>";
                if ($this->env == 2) echo "<br>";
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
     * @param int|null $days The amount of days simulating.
     */
    private function updateDate($days): void
    {
        $this->simulatedDays += $days;
        $this->dateStart += $days * self::DAY_IN_SECONDS;
        $_SESSION['dateStart'] = $this->dateStart;
    }

    public function giveUp(): void // Loser...
    {
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Loser..." . PHP_EOL;
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "Loser..." . "<br>";
        if ($this->env == 2) echo "<br>";
        $this->casinoBalance = 0;

        $_SESSION['casinoBalance'] = $this->casinoBalance;
        $_SESSION['dateStart'] = $this->dateStart;
        $_SESSION['simulatedDays'] = 0;
        $_SESSION['customerCount'] = 0;
    }

    //    private function gameOver($totalRevenue, $i)
    #[NoReturn] private function gameOver(): void
    {
        //        $this->totalDays += $i;

        //        echo "Lost after simulating: $i day(s)" . PHP_EOL;
        if ($this->env == 1) echo "Lost on Date: " . date("dS m Y", $this->dateStart) . PHP_EOL;
        if ($this->env == 1) echo "Lost after days: " . $this->simulatedDays . PHP_EOL;
        if ($this->env == 1) echo "Total visitors: " . $this->customerCount . PHP_EOL;
        //        if ($this->budget > $totalRevenue) {
        //            echo "Casino won: " . (number_format($this->budget - $totalRevenue)) . " €" . PHP_EOL;
        //        } else {
        //            echo "Casino lost: " . (number_format($totalRevenue - $this->budget)) . " €" . PHP_EOL;
        //        }
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 1) echo "Game Over. You're out of money!" . PHP_EOL;
        if ($this->env == 1) echo PHP_EOL;


        if ($this->env == 2) echo "Lost on Date: " . date("dS m Y", $this->dateStart) . "<br>";
        if ($this->env == 2) echo "Lost after days: " . $this->simulatedDays . "<br>";
        if ($this->env == 2) echo "Total visitors: " . $this->customerCount . "<br>";
        if ($this->env == 2) echo "<br>";
        if ($this->env == 2) echo "Game Over. You're out of money!" . "<br>";
        if ($this->env == 2) echo "<br>";
        exit;
    }

    private function printDivider(): void
    {
        if ($this->env == 1) echo "__________________________________________" . PHP_EOL;
        if ($this->env == 2) echo "__________________________________________" . "<br>";
    }

    /**
     * @param float $budgetBefore
     * @return void
     */
    public function showDailyProfit(float $budgetBefore): void
    {
        if ($this->casinoBalance > $budgetBefore) {
            if ($this->env == 1) echo "Casino won: " . (number_format($this->casinoBalance - $budgetBefore)) . " €" . PHP_EOL;
            if ($this->env == 2) echo "Casino won: " . (number_format($this->casinoBalance - $budgetBefore)) . " €" . "<br>";
        } else {
            if ($this->env == 1) echo "Casino lost: " . (number_format($budgetBefore - $this->casinoBalance)) . " €" . PHP_EOL;
            if ($this->env == 2) echo "Casino lost: " . (number_format($budgetBefore - $this->casinoBalance)) . " €" . "<br>";
        }
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 2) echo "<br>";
        $this->printDivider();
        if ($this->env == 1) echo PHP_EOL;
        if ($this->env == 2) echo "<br>";
    }
}

class Customer
{
    private $env = 1;
    private float $money;
    private int $gamesPlayed;
    private bool $hasFakeMoney = false;
    private bool $isMillionaire = false; // other name option: highRoller

    public function __construct($env)
    {
        $this->env = $env;

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
            if ($this->env == 1) echo PHP_EOL;
            if ($this->env == 1) echo "Ultra Rare Event: A customer with a million budget hat counterfeit money D: D: D:!" . PHP_EOL;;
            if ($this->env == 1) echo PHP_EOL;
            if ($this->env == 2) echo "<br>";
            if ($this->env == 2) echo "Ultra Rare Event: A customer with a million budget hat counterfeit money D: D: D:!. <br>";
            if ($this->env == 2) echo "<br>";
        } elseif ($this->isMillionaire) {
            if ($this->env == 1) echo "Rare Event: You have a visitor with a budget of 1 million!\n";
            if ($this->env == 1) echo PHP_EOL;
            if ($this->env == 2) echo "Rare Event: You have a visitor with a budget of 1 million!<br>";
            if ($this->env == 2) echo "<br>";
        } elseif ($this->hasFakeMoney) {
            if ($this->env == 1) echo "Rare Event: One of your visitor bought chips with counterfeit money! (You lose the cash he/she bought chips with)" . PHP_EOL;
            if ($this->env == 1) echo PHP_EOL;
            if ($this->env == 2) echo "Rare Event: One of your visitor bought chips with counterfeit money! (You lose the cash he/she bought chips with)<br>";
            if ($this->env == 2) echo "<br>";
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
            $chosenVariants = (array)array_rand($rouletteBets, $amountOfBets);

            foreach ($chosenVariants as $choosenVariant) {
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
