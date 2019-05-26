<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Farmer;
use App\Cow;
use App\Bunny;
use Session;

class FarmFeedController extends Controller
{
  // Member variables
  private $m_intMaxTurns = 50;
  private $m_arrstrClassAnimals = [
    '1' => Farmer::class,
    '2' => Cow::class,
    '3' => Bunny::class
  ];
  private $m_arrstrAnimals = [
    '1' => 'Farmer',
    '2' => 'Cow',
    '3' => 'Bunny'
  ];

    public function home()
    {
      return view('home');
    }

    public function feedAnimals( Request $objRequest )
    {
      $arrobjAnimals = [];
      $strDeadAnimal = '';
      $intTurn = $objRequest->get( 'turn' );

      if( $intTurn > $this->m_intMaxTurns ) {
        return [ 'status' => false, 'message' => 'Game Over.' ];
        exit;
      }

      // check turn count = 1 and generate animals
       if( '1' == $intTurn ) {
         $arrobjAnimals = $this->generateAnimalAndStoreDataInSession();
       }

       // using session to store animal obects
        $arrobjAnimals = Session::get( 'Animals' );

        // to get shuffled array
        $arrmixRandomArray = $this->ShuffleArray( $arrobjAnimals );
        // check animals exist or not
        list( $boolStatus, $strMessage ) = $this->checkAnimalExist( $arrobjAnimals );

        if( $boolStatus == true && false == empty( $strMessage ) )
        {
          return [ 'status' => true, 'message' => $strMessage ];
          exit;
        }
        if( false == $boolStatus )
        {
          return [ 'status' => false, 'message' => 'Game Over.' ];
          exit;
        }
        // logic to feed and animals
        foreach( $arrobjAnimals as $strKey => $objAnimal )
        {
          $objAnimal->setCurrentMammalFeedCount( $objAnimal->getCurrentMammalFeedCount() - 1 );
          // get first key from shuffle array
          if( $strKey == key( $arrmixRandomArray ) )
          {
            $objAnimal->setCurrentMammalFeedCount( $objAnimal->getFeedingInterval() );
          }

          // check if mammal dead then do not feed him
          if( true == $objAnimal->isMammalDead() )
          {
            $strDeadAnimal = $strKey;
            // check if farmer dead the Game is Over
            if( $objAnimal instanceof Farmer )
            {
              return [ 'status' => false, 'message' => 'Game Over. Farmer Dead.' ];
              exit;
            }
          }

          // removing dead animals and update session
          $this->removeDeadAnimalAndStoreDataInSession( $strKey, $strDeadAnimal, $arrobjAnimals );
        }
    }

    /**
    * This function is user to create animal and store in session
    *
    * @return array
    */
    private function generateAnimalAndStoreDataInSession() : array
    {
      $arrobjAnimals = $this->createAnimals();
      Session::put( 'Animals', $arrobjAnimals );
      Session::save();
      return $arrobjAnimals;
    }

    /**
    * This function is user create animals on the basis of required animla count
    *
    * @return array
    */
    protected function createAnimals() : array
    {
      $arrobjAnimals = $arrmimAimals = [];
      foreach( $this->m_arrstrClassAnimals as $intKey => $strClassAnimal ) {
        $objAnimal = new $strClassAnimal();
        $arrobjAnimals[] = $objAnimal->createMammals();
      }

      foreach ( $arrobjAnimals as $intKey => $arrobjAnimal ) {
        foreach ( $arrobjAnimal as $strKey => $objAnimal ) {
          $arrmimAimals[$strKey] = $objAnimal;
        }
      }
        return $arrmimAimals;
    }

    /**
    * This function is user to return random array with preserved key
    * @param $arrmixData array of animal obejcts to shuffle
    * @return array
    */
    private function ShuffleArray( $arrmixData ) : array
    {
      $arrintkeys = array_keys( $arrmixData );
      shuffle( $arrintkeys );
      return array_merge( array_flip( $arrintkeys ), $arrmixData );
    }

    /**
    * This function is user to return random array with preserved key
    * @param 1 $strKey key from shuffeld array object
    * @param 2 $strDeadAnimal Unique Name index for dead animal
    * @param 3 $arrobjAnimals shuffled array of animal objects
    * @return boolean
    */
    private function removeDeadAnimalAndStoreDataInSession( $strKey, $strDeadAnimal, $arrobjAnimals )
    {
      if( $strDeadAnimal == $strKey ) {
        unset( $arrobjAnimals[$strKey] );
      }
      Session::forget( 'Animals' );
      Session::put( 'Animals', $arrobjAnimals );
      Session::save();
      return true;
    }

    /**
    *This function is used to check whether minimum three animals exist or not
    * @param $arrobjAnimals current array of animals
    *@return array
    */
    private function checkAnimalExist( $arrobjAnimals ) : array    {
      $boolsWining = false;

      if( true == is_null( $arrobjAnimals ) || false == is_array( $arrobjAnimals ) )
      {
        return false;
      }

       $arrobjUniqueAnimals = array_map( function( $objAnimal ) {
      return $objAnimal->getMammalName();
      }, $arrobjAnimals );

      $arrstrUniqueAnimals = array_flip( array_unique( $arrobjUniqueAnimals ) );
      foreach ( $this->m_arrstrAnimals as $strAnimalClassName )
      {
        $boolsWining = false;
        if( true == array_key_exists( $strAnimalClassName,  $arrstrUniqueAnimals ) )
        {
          $boolsWining = true;
        }
      }

      if( true == $boolsWining && 3 == count( $arrobjAnimals ) ) {
        return [ true, 'Game Win.' ];
      }
      return [ $boolsWining, '' ];
    }
}
