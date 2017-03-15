/**
 * Created by MeGa on 09.11.2016.
 */
jQuery(document).ready(function ($) {
   $('#rasschet').click(function (e){
       e.preventDefault();
       var data = 'nBig='+$('#nBig').val()+'&nSmall='+$('#nSmall').val()+'&queueMax='+$('#queueMax').val()+'&lambda='+$('#lambda').val()+'&nu='+$('#nu').val();
       $.ajax({
           method: "POST",
           url: "model.php",
           data:  data
       }).done(function( msg ) {
            $('#results').html(msg);
           });
   });
});