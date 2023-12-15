<!-- Back to top button -->
<a id="buttontp"></a>
@section('foot_scripts')
<style>
#buttontp {
  display: inline-block;
  background-color: #FF9800;
  width: 50px;
  height: 50px;
  text-align: center;
  border-radius: 4px;
  position: fixed;
  bottom: 30px;
  right: 30px;
  transition: background-color .3s,
    opacity .5s, visibility .5s;
  opacity: 0;
  visibility: hidden;
  z-index: 1000;
}
#buttontp::after {
  content: "\f077";
  font-family: FontAwesome;
  font-weight: normal;
  font-style: normal;
  font-size: 2em;
  line-height: 50px;
  color: #fff;
}
#buttontp:hover {
  cursor: pointer;
  background-color: #333;
}
#buttontp:active {
  background-color: #555;
}
#buttontp.show {
  opacity: 1;
  visibility: visible;
}

/* Styles for the content section */


@media (min-width: 500px) {

  #buttontp {
    margin: 30px;
  }
}


</style>
<script>
var btn = $('#buttontp');
console.log('it starterted');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});

</script>
@endsection
