var objFarmFeeed = function() {
  var objUrls = {};

  function setUrl( strVarName, strUrl ) {
    objUrls[strVarName] = strUrl;
  }

  function init(){
    startGame();
    feedFarmAnimals();
  }

  function startGame() {
    $('#action_start_game').on( 'click', function() {
      $('#feed_animal').removeAttr('disabled');
      $(this).attr('disabled', 'disabled');
    } );
  }

  function feedFarmAnimals() {
    $( '#feed_animal' ).on( 'click', function() {
      alert( objUrls.feedAnimal );
      $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
              }
          });
      var objFeed = $(this);
      var intcurrentFeedTurn = objFeed.data('turn');
      $.ajax({
         url: objUrls.feedAnimal,
         method: 'post',
         data: { turn: intcurrentFeedTurn },
         success: function( response ) {
           var strtextColor = ( true == response.status ) ? 'green' : 'red';
           $('#show_messages').html( response.message ).css( 'color', strtextColor );
           $('#feed_animal').data( 'turn', ( parseInt( intcurrentFeedTurn ) + 1 ) );
           if( false == response.status ) {
             $('#feed_animal').attr('disabled', 'disabled');
             $('#action_start_game').removeAttr('disabled');
           }
         }
       });
      });
  }

  return {
    setUrl: setUrl,
    init: init
  }

}();
