 # Casino Simulation

A fun little idea to learn / improve on my PHP.

---

## run in cli

```
php casino-cli.php
```

___

## play at 
```
https://lilalunex.dev/casino-simulation/index.php
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
  - 50% Slots
  - 30% Black Jack
  - 20% Roulette
- Visitors plays just one type of game
- Visitors stops playing after ~21-30 times

---

## Maybe useful Information

Programmed on Windows WSL (Maybe important fo know if this doesn't work on your machine at first).

Only tested on my Windows machine. 

---

## Ideas / CANDOs:

[ ] Print day of random event

[ ] Chance user plays with counterfeit money -> The casino doesn't recognize at first -> So loses that money -> 
echo stats & info for that

[ ] You have to purchase a license for gambling, paid annually

[ ] Option to take another credit -> Choice of 3, different capital & interest rates 

[ ] Event: Casino has to close down for a week, for whatever reason (water damage)

[ ] Idea: Gang wants protection money

[ ] Idea: Fight happened in the Casino

[ ] Chance Dealer doesn't show up for work -> no income for that game for the day

[ ] Statistics for everything

[ ] Idea: Marketing campaign

[ ] Idea: Open up new branches

[ ] Idea: New machines have to be purchased. Players starts with few machines. Caluculations have to take done
accordingly to amount of owned machines -> turning this into an incremental game.

[ ] Idea: Start with a loan you have to pay back

[ ] Idea: Storyline -> User has to take loan first, so he/she understands there is one and gives immersion
to the """world"""

[ ] Daily Login Bonus

[ ] User can give Casino a name

[ ] (Holding the users hand through the entire process: Printing step by step and teaching what is up, instead of ...)

[ ] When doing actual game gamePlay loop: Simulate until action of user is required or random event happened. + Option 
to not hold on event + input to stop simulation

[ ] Don't redo the entire output always. Update onscreen vars.

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

[ ] Build CLI version in rust to compare speed

[ ] purchasable: money to check if money is counterfeit

[ ] improve EOL logic for consistency

[ ] game modes: regular, play one day per real life day

[ ] daily login bonus

[ ] Random Event User Input: -> Friend asks you to loan money -> Chance to gain money / Chance to never see tha Loan 
again

[ ] Idea -> buy better slot machines -> Higher chances for the casino to win -> chances adjustable when casino is 
closed overnight -> customer will visit you less if they win less and less (so the player doesn't just win 100% and 
gets penalty for greed)

[ ] Feature: Moving city. Make the starting city having a population of 20k. Customers remember they experience. 
Opens up features like reputation. Option: Move Casino Location for money -> reset reputation -> exploit other people 

[ ] Define echo Texts in nowdocs or vars. Maybe create a class just for that.

---

## Future music

Different level of dealers. A more expensive one, can deal more cards, does fewer mistakes i.e. makes you more money.

When doing a renovation, you won't have visitors during this time. Cuz you are renovating (duh...).

Chance dealer quits. No income until you hire a new one.

Actual game gameplay loop like you have an open button, see each visitor coming on, what's he/she's doing...
