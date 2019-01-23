[![Build Status](https://travis-ci.org/jaroslavtyc/drd-dice-roll.svg?branch=master)](https://travis-ci.org/jaroslavtyc/drd-dice-rolls)
[![Test Coverage](https://codeclimate.com/github/jaroslavtyc/drd-dice-roll/badges/coverage.svg)](https://codeclimate.com/github/jaroslavtyc/drd-dice-rolls/coverage)
[![License](https://poser.pugx.org/drd/dice-rolls/license)](https://packagist.org/packages/drd/dice-rolls)

[Let's roll!](#lets-roll)

[Custom dices & rolls](#custom-dices--rolls)

[Install](#install)

## Let's roll!

```php
<?php
use Granam\DiceRolls\Templates\Rollers\Roller1d6;
use Granam\DiceRolls\Templates\Rollers\Roller2d6Granam;

$roller1d6 = new Roller1d6();
$rolledValue = $roller1d6->roll();
if ($rolledValue === 6) {
    echo 'Hurray! You win!';
} else {
    echo 'Try harder';
}

$roller2d6Granam = new Roller2d6Granam();
while (($roll = $roller2d6Granam->roll()) && $roll->getValue() <= 12) {
    echo 'Still no bonus :( ...';
}
echo 'There it is! Bonus roll comes, with final value of '
. $roll->getValue() . '
Rolls were quite dramatic, consider by yourself: ';
foreach ($roll->getDiceRolls() as $diceRoll) {
    echo 'Rolled number ' . $diceRoll->getRolledNumber() . ', evaluated as value ' . $diceRoll->getValue(); 
}
```
There are plenty of predefined templates of dices and rolls as 1d4, 1d6, 1d10.
You can mix those and any else you create by `CustomDice` class.

Just think about your needs and check templates. Your requirements may be already satisfied by them.


## Custom dices & rolls
There can be situations, where you need crazy combinations. Let's say one roll with 1d5 dice and three rolls with 1d74 dice.

It is easy. The hard part is only to find the way:
```php
<?php
use Granam\DiceRolls\Templates\Dices\CustomDice;
use Granam\Integer\IntegerObject;
use Granam\DiceRolls\Templates\Dices\Dices;
use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;

$dice1d5 = new CustomDice(new IntegerObject(1) /* minimum of the dice */, new IntegerObject(5) /* maximum of the dice */);
$dice1d74 = new CustomDice(new IntegerObject(1) /* minimum of the dice */, new IntegerObject(74) /* maximum of the dice */);
$diceCombo = new Dices([$dice1d5, $dice1d74, $dice1d74, $dice1d74]);

$roller = new Roller(
    $diceCombo,
    new IntegerObject(1) /* roll with them all just once */,
    new OneToOneEvaluator() /* "what you roll is what you get" */,
    new NoRollOn() /* no bonus roll at all */,
    new NoRollOn() /* no malus roll at all */
);

// here it is!
$roller->roll();

```

## Install
- order [composer](https://getcomposer.org/download/) to add new requirement
```
composer require drd/dice-roll
```
