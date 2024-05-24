<?php

class Element {
    private $name;
    private $winsAgainst;

    public function __construct($name, $winsAgainst) {
        $this->name = $name;
        $this->winsAgainst = $winsAgainst;
    }

    public function getName() {
        return $this->name;
    }

    public function winsAgainst(Element $opponent) {
        return in_array($opponent->getName(), $this->winsAgainst);
    }
}

class SlotMachine {
    private $balance;
    private $boardSize;
    private $winConditions;

    public function __construct($initialBalance, $boardSize = 3, $winConditions = []) {
        $this->balance = $initialBalance;
        $this->boardSize = $boardSize;
        $this->winConditions = $winConditions;
    }

    public function play($bet) {
        if ($this->balance < $bet) {
            echo "Not enough coins to bet.\n";
            return;
        }

        $this->balance -= $bet;
        $board = $this->generateBoard();
        $this->displayBoard($board);

        $winAmount = $this->calculateWin($board, $bet);
        if ($winAmount > 0) {
            $this->balance += $winAmount;
            echo "You win: $winAmount coins!\n";
        } else {
            echo "You lose!\n";
        }

        echo "Current balance: " . $this->balance . " coins\n";
    }

    private function generateBoard() {
        $board = [];
        for ($i = 0; $i < $this->boardSize; $i++) {
            $row = [];
            for ($j = 0; $j < $this->boardSize; $j++) {
                $row[] = rand(1, 5);
            }
            $board[] = $row;
        }
        return $board;
    }

    private function displayBoard($board) {
        foreach ($board as $row) {
            echo implode(' ', $row) . "\n";
        }
    }

    private function calculateWin($board, $bet) {
        $winAmount = 0;
        foreach ($this->winConditions as $condition) {
            foreach ($board as $row) {
                if (count(array_unique($row)) === 1 && $row[0] == $condition) {
                    $winAmount += $condition * $bet;
                }
            }
        }
        return $winAmount;
    }

    public function getBalance() {
        return $this->balance;
    }
}

$initialCoins = (int) readline("Enter the start amount of virtual coins: ");

$winConditions = [1, 2, 3, 4, 5];
$slotMachine = new SlotMachine($initialCoins, 3, $winConditions);

while ($slotMachine->getBalance() > 0) {
    $bet = (int) readline("Enter the bet amount per spin: ");
    $slotMachine->play($bet);

    $continue = readline("Do you want to play again? (yes/no): ");
    if (strtolower($continue) !== 'yes') {
        break;
    }
}

echo "Game over! You have " . $slotMachine->getBalance() . " coins left.\n";