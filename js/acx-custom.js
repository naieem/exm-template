alert("dfdf");
jQuery(document).ready(function () {
    alert("fdfd")
    jQuery( "li.product-category" ).hover(
        function() {
          alert('hovered')
        }, function() {
          alert('gone')
        }
      );
});