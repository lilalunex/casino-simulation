# Casino Simulation

A fun little idea to learn / improve on my PHP.

---

## run

CLI
```
php casino.php
```

---

## Showcase

```
user@machine:~/code/casino-simulation$ php casino.php

Casino Simulation.

Date:   01st 01 2000
Budget: 1,000,000.00 €

Choose what to do next

1 or enter: Simulate a day.
2: Simulate a week.
3: Simulate a month (31 days).
4: Simulate a year (365 days).
5: Simulate until amount of digits change.

Integer: Simulate for that amount of days.

h: Help, I'm lost.
g: Give up.
e: Exit.

Your input: 1

__________________________________________

Running simulation for 1 day(s).

Total visitors: 126
Casino won: 79,843 €

__________________________________________

Date:   02nd 01 2000
Budget: 1,079,842.50 €

Choose what to do next

1 or enter: Simulate a day.
2: Simulate a week.
3: Simulate a month (31 days).
4: Simulate a year (365 days).
5: Simulate until amount of digits change.

Integer: Simulate for that amount of days.

h: Help, I'm lost.
g: Give up.
e: Exit.

Your input:

```

---

## Maybe useful Information

Programmed on Windows WSL (Maybe important fo know if this doesn't work on your machine at first).

Only tested on my Windows machine. 

---

## Ideas / CANDOs:

[ ] Add expanses: employees, rent, lights, water, food, misc

[ ] Event: Repair/buy machines / furniture again

[ ] Calc months & years correct (28 days, 366 days)

[ ] Have closed on day X? And/Or holidays?

[ ] As of now the casino only wins... Hm...

[ ] Test execution on Ubuntu

[ ] GUI or other ways to control this than CLI

[ ] User can add own timezone, budget & days to skip

[X] Change Repo name to include simulation

[ ] Save & resume option

[ ] Able to play yourself (splitting up in multiple files)

[ ] Choose games to play

[ ] Make all options parameters

[ ] Make all options chooseable in the CLI

[X] Write possibilities in help

[X] Random Event: A visitor comes and plays with 1.000.000 EUR

[X] User can type in how many days to simulate

[X] Simulate until (maybe) a new digit is appended.

[X] Print stats of each day

[ ] On Game Over show the stats of the day

[ ] Improve code

[ ] Write Tests

[X] Player will stop playing after some games and not until he/her runs out of money

[ ] detailed statistics for played games

[ ] check for game over when budget is updated?

[ ] Test: Modify loss numbers to test output of giveup

[ ] Konami Cheat for moneys