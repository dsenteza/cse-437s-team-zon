$( "#faqHow" ).click(function () {
  if ( $( "#ansHow" ).is( ":hidden" ) ) {
    $( "#ansHow" ).slideDown( "slow" );
  } else {
    $( "#ansHow" ).hide();
  }
});
