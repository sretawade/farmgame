<?php

namespace App;

abstract class Mammals
{
    /**
    *
    * Member vaiables
    */
    protected $m_intFeedingInterval;
    protected $m_intCurrentMammalFeedCount;
    protected $m_intMammalCount;

    protected $m_strMammalName;

    /**
    *set and get functions
    *
    */
    public function setFeedingInterval( $intFeedingInterval )
    {
      $this->m_intFeedingInterval = $mintFeedingInterval;
    }

    public function getFeedingInterval()
    {
      return $this->m_intFeedingInterval;
    }

    public function setCurrentMammalFeedCount( $intCurrentMammalFeedCount )
    {
      $this->m_intCurrentMammalFeedCount = $intCurrentMammalFeedCount;
    }

    public function getCurrentMammalFeedCount()
    {
      return $this->m_intCurrentMammalFeedCount;
    }

    public function setMammalCount( $intMammalCount )
    {
      $this->m_intMammalCount = $intMammalCount;
    }

    public function getMammalCount()
    {
      return $this->m_intMammalCount;
    }

    public function setMammalName( $strMammalName )
    {
      $this->m_strMammalName = $strMammalName;
    }

    public function getMammalName()
    {
      return $this->m_strMammalName;
    }

    public function setIsMammalDead( $boolIsMammalDead )
    {
      $this->m_boolIsMammalDead = $boolIsMammalDead;
    }

    public function getIsMammalDead()
    {
      return $this->m_boolIsMammalDead;
    }

    /**
    * this function is use to check where Mammal is Dead
    * @return bool
    */
    public function isMammalDead() : bool
    {
      $BoolIsDied = false;
      if( $this->getCurrentMammalFeedCount() <=0 )
      {
        $BoolIsDied = true;
      }
      return $BoolIsDied;
    }

    /**
    * this function is use to create animals objects as per the animal count given
    * @return array
    */
    public function createMammals() : array
    {
      $arrobjMammals = [];
      for( $intI = 1; $intI <= $this->getMammalCount(); $intI++ ) {
        $objAnimal = new $this;
        // setting current animal feed as feeding interval
        $objAnimal->setCurrentMammalFeedCount( $objAnimal->getFeedingInterval() );
        // added logic to create key name based animal objects
        $arrobjMammals[$this->getMammalName() . $intI] = $objAnimal;
      }
      return $arrobjMammals;
    }
}
