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

## Features

- You can simulate days in your casino
- Visitors come to your Casino to play games
- Your casino has the games: Roulette, Black Jack & Slots
- There are calculations happening for every type of game
- Visitor stop playing after ~21-30 rounds per game
- Every visitor just plays one type of game
- Random Events

---

## Numbers used for simulation

- Visitors per Day: 50 - 200 visitors
- Budget to play per Visitor: 50 - 10.000 €
- Visitor chance to play game:
  - 20% Roulette
  - 30% Black Jack
  - 50% Slots
- Visitors plays just one type of game
- Visitors stops playing after ~21-30 times

---

## Maybe useful Information

Programmed on Windows WSL (Maybe important fo know if this doesn't work on your machine at first).

Only tested on my Windows machine. 

---

## Ideas / CANDOs:

[ ] Test if 33% per game chances the outcome

[ ] Statistics for everything

[ ] Idea: Marketing campaign

[ ] Idea: Open up new branches

[ ] Idea: New machines have to be purchased. Players starts with few machines. Caluculations have to take done
accordingly to amount of owned machines -> turning this into an incremental game.

[ ] Idea: Start with a loan you have to pay back

[ ] Idea: Storyline -> User has to take loan first, so he/she understands there is one and gives immersion
to the """world"""

[ ] Add expanses: employees, rent, lights, water, food, misc

[ ] Event: Repair/buy machines / furniture again

[ ] Calc months & years correct (28 days, 366 days)

[ ] Have closed on day X? And/Or holidays?

[ ] As of now the casino only wins... Hm...

[ ] Test execution on Ubuntu

[ ] GUI or other ways to control this than CLI

[ ] User can add own timezone, budget & days to skip

[ ] Save & resume option

[ ] Able to play yourself (splitting up in multiple files)

[ ] Choose games to play

[ ] Make all options parameters

[ ] Make all options chooseable in the CLI

[ ] On Game Over show the stats of the day

[ ] Improve code

[ ] Write Tests

[ ] detailed statistics for played games

[ ] check for game over when budget is updated?

[ ] Test: Modify loss numbers to test output of giveup

[ ] Konami Cheat for moneys

---

## Future music

Different level of dealers. A more expensive one, can deal more cards, does fewer mistakes i.e. makes you more money.