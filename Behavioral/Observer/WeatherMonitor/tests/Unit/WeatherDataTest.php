<?php

namespace WeatherMonitor\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WeatherMonitor\Display\HeatIndexDisplay;
use WeatherMonitor\ObserverInterface;
use WeatherMonitor\WeatherData;

/**
 * WeatherData unit test class.
 */
class WeatherDataTest extends TestCase
{
    public function testAttach()
    {
        $heatIndexDisplay = $this->createMock(HeatIndexDisplay::class);

        $weatherData = new WeatherData();
        $weatherData->attach($heatIndexDisplay);

        $this->assertAttributeContains($heatIndexDisplay, 'observers', $weatherData);
    }

    public function testDetach()
    {
        $heatIndexDisplay = $this->createMock(HeatIndexDisplay::class);
        $weatherData = new WeatherData();

        $weatherData->attach($heatIndexDisplay);
        $this->assertAttributeContains($heatIndexDisplay, 'observers', $weatherData);

        $weatherData->detach($heatIndexDisplay);
        $this->assertAttributeNotContains($heatIndexDisplay, 'observers', $weatherData);
    }

    public function testNotify()
    {
        $mock = $this->getMockBuilder(HeatIndexDisplay::class)
            ->disableOriginalConstructor()
            ->setMethods(['update'])
            ->getMock();
        $mock->expects($this->once())->method('update');

        /** @var ObserverInterface $heatIndexDisplay */
        $heatIndexDisplay = $mock;
        $weatherData = new WeatherData();
        $weatherData->attach($heatIndexDisplay);
        $weatherData->notify();
    }

    public function testMeasurementsChanged()
    {
        $mock = $this->getMockBuilder(WeatherData::class)->setMethods(['notify'])->getMock();
        $mock->expects($this->once())->method('notify');

        /** @var WeatherData $weatherData */
        $weatherData = $mock;
        $weatherData->measurementsChanged();
    }

    public function testSetMeasurements()
    {
        $temperature = 80;
        $humidity = 65;
        $pressure = 30.4;

        $mock = $this->getMockBuilder(WeatherData::class)->setMethods(['measurementsChanged'])->getMock();
        $mock->expects($this->once())->method('measurementsChanged');

        /** @var WeatherData $weatherData */
        $weatherData = $mock;
        $weatherData->setMeasurements($temperature, $humidity, $pressure);

        $this->assertEquals($temperature, $weatherData->getTemperature());
        $this->assertEquals($humidity, $weatherData->getHumidity());
        $this->assertEquals($pressure, $weatherData->getPressure());
    }

    public function testGetTemperature()
    {
        $temperature = 80;

        $weatherData = new WeatherData();
        $weatherData->setMeasurements($temperature, 0.0, 0.0);

        $this->assertEquals($temperature, $weatherData->getTemperature());
    }

    public function testGetHumidity()
    {
        $humidity = 65;

        $weatherData = new WeatherData();
        $weatherData->setMeasurements(0.0, $humidity, 0.0);

        $this->assertEquals($humidity, $weatherData->getHumidity());
    }

    public function testGetPressure()
    {
        $pressure = 30.4;

        $weatherData = new WeatherData();
        $weatherData->setMeasurements(0.0, 0.0, $pressure);

        $this->assertEquals($pressure, $weatherData->getPressure());
    }
}