@extends('layout.app')
@section ('content')
<h1>FARM FEED GAME</h1>
<p id="show_messages"></p>
<input type="button" id="action_start_game" value="START"/>
<input type="button" id="feed_animal" disabled="disabled" data-turn='1' value="Feed Animals"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="{{ asset( 'js/farmFeed.js' ) }}"></script>
<script type="text/javascript">
  objFarmFeeed.setUrl( 'feedAnimal', "{{ url('/feedanimal') }}" );
  objFarmFeeed.init();
</script>

@endsection
