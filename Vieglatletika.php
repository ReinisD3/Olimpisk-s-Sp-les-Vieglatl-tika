<?php
class Runner
{
    public string $name;
    public int $trackPosition = 0;
    function __construct($nameSymbols)
    {
        $this->name = $nameSymbols[array_rand($nameSymbols)];
    }
}
class RunSimulator
{
    public int $runnerCount;
    private int $trackLength;
    public array $track ;
    public array $runners ;
    private array $nameSymbols = ['@','#','$','%','&','*','+','^','!'];
    public array $winners = [];

    function __construct(int $runnerCount, int $trackLength)
    {
        $this->runnerCount = $runnerCount;
        $this->trackLength = $trackLength;
        $this->makeRunners();
        $this->makeTrack();
    }
    private function makeRunners()
    {
        for ($i=0 ; $i < $this->runnerCount ; $i++)
        {
            $this->runners[] = new Runner($this->nameSymbols);
            $this->nameSymbols = array_diff($this->nameSymbols,[$this->runners[$i]->name]);
        }
    }
    private function makeTrack()
    {
        foreach (range(1,$this->trackLength) as $i)
        {
            $this->track[] = '_';
        }
    }
    public function runSection()
    {
        foreach ($this->runners as $runner)
        {
                $runner->trackPosition += rand(1,2);
        }
    }
    public function updateWinners()
    {
        foreach ($this->runners as $runner) {
            if ($runner->trackPosition >= $this->trackLength && !in_array($runner,$this->winners)) {
                $this->winners[] = $runner;
            }
        }
    }
}
class SimulatorInterface
{
    private RunSimulator $simulator;

    function __construct(RunSimulator $simulator)
    {
        $this->simulator = $simulator;
    }
    private function displaySection()
    {
        foreach ($this->simulator->runners as $runner)
        {
            foreach ($this->simulator->track as $key => $section)
            {
                echo $key === $runner->trackPosition ? $runner->name.' ' : $section.' ';
            }
            echo PHP_EOL;
        }
    }
    public function displayRun()
    {
        while(count($this->simulator->winners) < $this->simulator->runnerCount)
        {
            system('clear');
            $this->displaySection();
            usleep(250000);
            $this->simulator->runSection();
            $this->simulator->updateWinners();
        }
        system('clear');
        $this->displayWinners();
    }
    private function displayWinners()
    {

        foreach ($this->simulator->winners as $position =>$winner)
        {
            $position ++;
            echo "Position $position  is $winner->name ".PHP_EOL;
        }
    }
}
$displayRun = new SimulatorInterface(new RunSimulator(3,44));
$displayRun->displayRun();