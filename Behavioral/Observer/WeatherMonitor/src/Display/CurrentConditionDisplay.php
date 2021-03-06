<?php

namespace WeatherMonitor\Display;

use WeatherMonitor\ObserverInterface;
use WeatherMonitor\SubjectInterface;
use WeatherMonitor\WeatherData;

/**
 * Current Condition Display.
 */
class CurrentConditionDisplay implements ObserverInterface, DisplayElementInterface
{
    /**
     * @var float
     */
    private $temperature;

    /**
     * @var float
     */
    private $humidity;

    /**
     * Register class object to an observer of subject.
     *
     * @param SubjectInterface $subject
     */
    public function __construct(SubjectInterface $subject)
    {
        $subject->attach($this);
    }

    /**
     * {@inheritdoc}
     */
    public function update(SubjectInterface $subject)
    {
        if ($subject instanceof WeatherData) {
            $this->temperature = $subject->getTemperature();
            $this->humidity = $subject->getHumidity();

            return $this->display();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        echo sprintf(
            "Current conditions: %1\$.1fF degrees and %2\$.1f%% humidity\n",
            $this->temperature,
            $this->humidity
        );
    }
}
